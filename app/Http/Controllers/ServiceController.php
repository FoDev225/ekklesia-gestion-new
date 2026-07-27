<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use App\Models\Service;
use App\Models\ServiceRole;
use App\Models\ServiceAssignment;
use App\Models\ActeurCulte;
use App\Models\WorshipGroup;
use App\Models\Church;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // ═══════════════════════════════════════════
    // PÉRIODES
    // ═══════════════════════════════════════════

    public function periodes()
    {
        $periodes = Periode::withCount('services')
            ->orderByDesc('start_date')
            ->paginate(10);

        return view('cultes.periodes.index', compact('periodes'));
    }

    public function createPeriode()
    {
        return view('cultes.periodes.create');
    }

    public function storePeriode(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:150',
            'general_theme' => 'nullable|string|max:255',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
        ]);

        $periode = Periode::create($request->only('name', 'general_theme', 'start_date', 'end_date'));

        if ($request->boolean('is_active')) {
            $periode->activate();
        }

        return redirect()->route('cultes.periodes')
            ->with('success', 'Période créée avec succès.');
    }

    public function editPeriode(Periode $periode)
    {
        return view('cultes.periodes.create', compact('periode'));
    }

    public function updatePeriode(Request $request, Periode $periode)
    {
        $request->validate([
            'name'          => 'required|string|max:150',
            'general_theme' => 'nullable|string|max:255',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
        ]);

        $periode->update($request->only('name', 'general_theme', 'start_date', 'end_date'));

        if ($request->boolean('is_active')) {
            $periode->activate();
        }

        return redirect()->route('cultes.periodes')
            ->with('success', 'Période mise à jour.');
    }

    public function activatePeriode(Periode $periode)
    {
        $periode->activate();
        return redirect()->back()->with('success', "Période \"{$periode->name}\" activée.");
    }

    public function archivePeriode(Periode $periode)
    {
        $periode->update(['is_active' => false, 'is_archive' => true]);
        return redirect()->back()->with('success', 'Période archivée.');
    }

    // ═══════════════════════════════════════════
    // CULTES (SERVICES)
    // ═══════════════════════════════════════════

    public function services(Periode $periode)
    {
        $services = $periode->services()->with('assignments.believer', 'assignments.role', 'assignments.worshipGroup')->get();
        $roles    = ServiceRole::all();
        $nextSundayService = $this->getNextSundayService();

        return view('cultes.services.index', compact('periode', 'services', 'roles', 'nextSundayService'));
    }

    public function createService(Periode $periode)
    {
        return view('cultes.services.create', compact('periode'));
    }

    public function storeService(Request $request, Periode $periode)
    {
        $request->validate([
            'service_date'  => 'required|date',
            'service_theme' => 'nullable|string|max:255',
            'service_type'  => 'required|in:commun,francais,senoufo,special',
        ]);

        // Vérifier qu'il n'y a pas déjà un culte ce jour
        $exists = $periode->services()->where('service_date', $request->service_date)->exists();
        if ($exists) {
            return redirect()->back()->withErrors(['service_date' => 'Un culte existe déjà à cette date dans cette période.'])->withInput();
        }

        $periode->services()->create($request->only('service_date', 'service_theme', 'service_type'));

        return redirect()->route('cultes.services', $periode)
            ->with('success', 'Culte ajouté.');
    }

    public function destroyService(Service $service)
    {
        $periode = $service->periode;
        $service->delete();
        return redirect()->route('cultes.services', $periode)->with('success', 'Culte supprimé.');
    }

    // ═══════════════════════════════════════════
    // ASSIGNATIONS
    // ═══════════════════════════════════════════

    public function assignations(Service $service)
    {
        $service->load(['assignments.believer', 'assignments.role', 'assignments.worshipGroup', 'periode']);
        $roles         = ServiceRole::all();
        $worshipGroups = WorshipGroup::orderBy('name')->get();
        $acteurs       = ActeurCulte::with(['believer', 'role'])
            ->where('is_active', true)
            ->get()
            ->groupBy('service_role_id');

        // Nombre de groupes de louange déjà programmés / attendus pour ce culte
        $louangeRole = ServiceRole::where('slug', 'louange')->first();
        $louangeCount = $louangeRole
            ? $service->assignments->where('service_role_id', $louangeRole->id)->count()
            : 0;
        $maxGroups = match($service->service_type) {
            'francais', 'senoufo' => 2,
            'commun', 'special'   => 3,
            default               => 3,
        };

        return view('cultes.services.assignations', compact(
            'service', 'roles', 'worshipGroups', 'acteurs', 'louangeCount', 'maxGroups'
        ));
    }

    public function storeAssignation(Request $request, Service $service)
    {
        $role = ServiceRole::findOrFail($request->service_role_id);
        $isLouange = $role->slug === 'louange';

        $request->validate([
            'believer_id'      => $isLouange ? 'nullable' : 'required|exists:believers,id',
            'service_role_id'  => 'required|exists:service_roles,id',
            'worship_group_id' => $isLouange ? 'required|exists:worship_groups,id' : 'nullable|exists:worship_groups,id',
            'is_backup'        => 'boolean',
        ]);

        if ($isLouange) {
            // ── Logique spécifique Louange : plusieurs groupes autorisés ──

            $maxGroups = match($service->service_type) {
                'francais', 'senoufo' => 2,
                'commun', 'special'   => 3,
                default               => 3,
            };

            $currentCount = ServiceAssignment::where('service_id', $service->id)
                ->where('service_role_id', $role->id)
                ->count();

            if ($currentCount >= $maxGroups) {
                return redirect()->back()
                    ->withErrors(['worship_group_id' => "Le nombre maximum de groupes de louange ({$maxGroups}) est déjà atteint pour ce culte."])
                    ->withInput();
            }

            // Empêcher d'assigner deux fois le même groupe au même culte
            $alreadyAssigned = ServiceAssignment::where('service_id', $service->id)
                ->where('service_role_id', $role->id)
                ->where('worship_group_id', $request->worship_group_id)
                ->exists();

            if ($alreadyAssigned) {
                $group = WorshipGroup::find($request->worship_group_id);
                return redirect()->back()
                    ->withErrors(['worship_group_id' => "Le groupe \"{$group->name}\" est déjà programmé pour ce culte."])
                    ->withInput();
            }

            ServiceAssignment::create([
                'service_id'       => $service->id,
                'believer_id'      => null,
                'service_role_id'  => $role->id,
                'worship_group_id' => $request->worship_group_id,
                'is_backup'        => false,
            ]);

            return redirect()->route('cultes.assignations', $service)
                ->with('success', 'Groupe de louange programmé.');
        }

        // ── Logique standard pour les autres rôles (prédicateur, président, etc.) ──

        $alreadyScheduled = ServiceAssignment::where('believer_id', $request->believer_id)
            ->whereHas('service', fn($q) => $q->where('service_date', $service->service_date))
            ->exists();

        if ($alreadyScheduled) {
            $believer = \App\Models\Believer::find($request->believer_id);
            return redirect()->back()
                ->withErrors(['believer_id' => "{$believer->full_name} est déjà programmé(e) ce dimanche."])
                ->withInput();
        }

        $exists = ServiceAssignment::where('service_id', $service->id)
            ->where('service_role_id', $role->id)
            ->where('is_backup', $request->boolean('is_backup'))
            ->exists();

        if ($exists && !$request->boolean('is_backup')) {
            return redirect()->back()
                ->withErrors(['service_role_id' => 'Ce rôle a déjà un titulaire pour ce culte.'])
                ->withInput();
        }

        ServiceAssignment::create([
            'service_id'      => $service->id,
            'believer_id'     => $request->believer_id,
            'service_role_id' => $role->id,
            'worship_group_id'=> null,
            'is_backup'       => $request->boolean('is_backup'),
        ]);

        return redirect()->route('cultes.assignations', $service)
            ->with('success', 'Attribution ajoutée.');
    }

    public function destroyAssignation(ServiceAssignment $assignment)
    {
        $service = $assignment->service;
        $assignment->delete();
        return redirect()->route('cultes.assignations', $service)->with('success', 'Attribution supprimée.');
    }

    public function promoteAssignation(ServiceAssignment $assignment)
    {
        $existingTitulaire = ServiceAssignment::where('service_id', $assignment->service_id)
            ->where('service_role_id', $assignment->service_role_id)
            ->where('is_backup', false)
            ->where('id', '!=', $assignment->id)
            ->exists();

        if ($existingTitulaire) {
            return redirect()->back()->withErrors([
                'promote' => 'Ce rôle a déjà un titulaire pour ce culte. Retirez-le avant de promouvoir ce suppléant.',
            ]);
        }

        $assignment->update(['is_backup' => false]);

        return redirect()->route('cultes.assignations', $assignment->service)
            ->with('success', "{$assignment->believer->full_name} est maintenant titulaire.");
    }

    // ═══════════════════════════════════════════
    // ACTEURS DE CULTE
    // ═══════════════════════════════════════════

    public function acteurs()
    {
        $acteurs = ActeurCulte::with(['believer', 'role'])
            ->where('is_active', true)
            ->orderBy('service_role_id')
            ->get()
            ->groupBy('role.name');

        $roles    = ServiceRole::all();
        $believers = \App\Models\Believer::whereNotIn('status', ['parti', 'decede'])
            ->orderBy('lastname')
            ->get(['id', 'lastname', 'firstname']);

        return view('cultes.acteurs.index', compact('acteurs', 'roles', 'believers'));
    }

    public function storeActeur(Request $request)
    {
        $request->validate([
            'believer_id'     => 'required|exists:believers,id',
            'service_role_id' => 'required|exists:service_roles,id',
        ]);

        $exists = ActeurCulte::where('believer_id', $request->believer_id)
            ->where('service_role_id', $request->service_role_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['believer_id' => 'Ce fidèle a déjà ce rôle.']);
        }

        ActeurCulte::create([
            'believer_id'     => $request->believer_id,
            'service_role_id' => $request->service_role_id,
            'is_active'       => true,
        ]);

        return redirect()->route('cultes.acteurs')->with('success', 'Acteur ajouté.');
    }

    public function destroyActeur(ActeurCulte $acteur)
    {
        $acteur->delete();
        return redirect()->route('cultes.acteurs')->with('success', 'Acteur retiré.');
    }

    // ═══════════════════════════════════════════
    // PROGRAMME PDF
    // ═══════════════════════════════════════════

    public function programmePdf(Periode $periode)
    {
        $periode->load([
            'services.assignments.believer',
            'services.assignments.role',
            'services.assignments.worshipGroup',
        ]);

        $roles  = ServiceRole::all()->keyBy('slug');
        $church = Church::instance();

        $pdf = Pdf::loadView('cultes.programme-pdf', compact('periode', 'roles', 'church'))
            ->setPaper('a4', 'landscape')
            ->setOption([
                'defaultFont'     => 'Arial',
                'isRemoteEnabled' => true,
                'margin_top'      => 8,
                'margin_bottom'   => 8,
                'margin_left'     => 8,
                'margin_right'    => 8,
                'dpi'             => 150,
            ]);

        $filename = 'programme-cultes-' . strtolower(str_replace(' ', '-', $periode->name)) . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Retourne le culte du dimanche de la semaine en cours, uniquement
     * à partir du mercredi (jour où les affectations sont généralement
     * finalisées). Avant mercredi, la carte reste masquée (retourne null).
     */
    private function getNextSundayService(): ?Service
    {
        if (now()->dayOfWeekIso < 3) { // avant mercredi (1=lundi, 3=mercredi)
            return null;
        }

        return Service::whereDate('service_date', Service::currentWeekSunday()->toDateString())
            ->with('assignments.believer', 'assignments.role', 'assignments.worshipGroup')
            ->first();
    }
}