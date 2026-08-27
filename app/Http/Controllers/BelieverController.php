<?php

namespace App\Http\Controllers;

use App\Models\Believer;
use App\Models\Team;
use App\Models\Sanction;
use App\Models\Departure;
use App\Models\Church;
use App\Services\ActivityLogger;
use App\Http\Requests\BelieverFormRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class BelieverController extends Controller
{
    public function index(Request $request)
    {
        $believers = Believer::with(['address', 'teams', 'worshipGroups'])
            ->search($request->search)
            ->byGender($request->gender)
            ->byMaritalStatus($request->marital_status)
            ->byAgeGroup($request->age_group)
            ->byTeam($request->team_id)
            ->byWorshipGroup($request->worship_group_id)
            ->orderBy('lastname')
            ->paginate(20)
            ->withQueryString();

        $teams = Team::orderBy('name')->get();
        $worshipGroups = \App\Models\WorshipGroup::orderBy('name')->get();

        return view('believers.index', compact('believers', 'teams', 'worshipGroups'));
    }

    public function create()
    {
        $teams  = Team::orderBy('name')->get();
        $groups = \App\Models\Group::orderBy('name')->get();
        $cells  = \App\Models\Cell::orderBy('name')->get();
        $worshipGroups = \App\Models\WorshipGroup::orderBy('name')->get();
        $languages = \App\Models\Language::orderBy('name')->get();

        return view('believers.create', compact('teams', 'groups', 'cells', 'worshipGroups', 'languages'));
    }

    public function store(BelieverFormRequest $request)
    {
        $data = $request->validated();

        // Extraire le fichier avant la création (sinon Eloquent essaie de stocker l'objet UploadedFile)
        unset($data['profile_picture']);

        $believer = Believer::create($data);

        if ($request->hasFile('profile_picture')) {
            $believer->update([
                'profile_picture' => $request->file('profile_picture')
                    ->store('believers/profile-pictures', 'public'),
            ]);
        }

        // Sous-tables one-to-one
        $believer->address()->create($request->input('address', []));
        $believer->churchInformation()->create($request->input('church', []));
        $believer->education()->create($request->input('education', []));
        $believer->profession()->create($request->input('profession', []));
        $believer->responsibility()->create($request->input('responsibility', []));

        // Relations many-to-many
        if ($request->filled('teams')) {
            $believer->teams()->sync($request->teams);
        }
        if ($request->filled('groups')) {
            $believer->groups()->sync($request->groups);
        }
        if ($request->filled('worship_groups')) {
            $believer->worshipGroups()->sync($request->worship_groups);
        }
        if ($request->filled('cell_id')) {
            $believer->cells()->sync([$request->cell_id]);
        }
        if ($request->filled('languages')) {
            $syncData = [];
            foreach ($request->languages as $languageId => $skills) {
                // On ne synchronise que les langues où au moins une compétence est cochée
                if (empty($skills)) {
                    continue;
                }
                $syncData[$languageId] = [
                    'lu'    => isset($skills['lu']),
                    'parle' => isset($skills['parle']),
                    'ecrit' => isset($skills['ecrit']),
                ];
            }
            $believer->languages()->sync($syncData);
        } else {
            $believer->languages()->sync([]);
        }

        return redirect()
            ->route('believers.show', $believer)
            ->with('success', 'Fidèle ajouté avec succès.');
    }

    public function show(Believer $believer)
    {
        $believer->load([
            'address',
            'churchInformation',
            'education',
            'profession',
            'responsibility',
            'languages',
            'teams',
            'groups',
            'worshipGroups',
            'cells',
            'family',
        ]);

        return view('believers.show', compact('believer'));
    }

    public function edit(Believer $believer)
    {
        $believer->load([
            'address', 'churchInformation', 'education',
            'profession', 'responsibility', 'teams', 'groups', 'worshipGroups', 'cells', 'languages'
        ]);

        $teams  = Team::orderBy('name')->get();
        $groups = \App\Models\Group::orderBy('name')->get();
        $cells  = \App\Models\Cell::orderBy('name')->get();
        $worshipGroups = \App\Models\WorshipGroup::orderBy('name')->get();
        $languages = \App\Models\Language::orderBy('name')->get();

        return view('believers.edit', compact('believer', 'teams', 'groups', 'cells', 'worshipGroups', 'languages'));
    }

    public function update(BelieverFormRequest $request, Believer $believer)
    {
        $data = $request->validated();
        unset($data['profile_picture']);

        $believer->update($data);

        // Remplacement de la photo uniquement si un nouveau fichier est envoyé
        if ($request->hasFile('profile_picture')) {
            // Supprimer l'ancienne photo si elle existe
            if ($believer->profile_picture) {
                \Storage::disk('public')->delete($believer->profile_picture);
            }

            $believer->update([
                'profile_picture' => $request->file('profile_picture')
                    ->store('believers/profile-pictures', 'public'),
            ]);
        }

        // Suppression explicite de la photo demandée (checkbox "retirer la photo")
        if ($request->boolean('remove_profile_picture') && $believer->profile_picture) {
            \Storage::disk('public')->delete($believer->profile_picture);
            $believer->update(['profile_picture' => null]);
        }

        // Mise à jour des sous-tables (updateOrCreate pour sécurité)
        $believer->address()->updateOrCreate(
            ['believer_id' => $believer->id],
            $request->input('address', [])
        );
        $believer->churchInformation()->updateOrCreate(
            ['believer_id' => $believer->id],
            $request->input('church', [])
        );
        $believer->education()->updateOrCreate(
            ['believer_id' => $believer->id],
            $request->input('education', [])
        );
        $believer->profession()->updateOrCreate(
            ['believer_id' => $believer->id],
            $request->input('profession', [])
        );
        $believer->responsibility()->updateOrCreate(
            ['believer_id' => $believer->id],
            $request->input('responsibility', [])
        );

        // Sync many-to-many
        $believer->teams()->sync($request->input('teams', []));
        $believer->worshipGroups()->sync($request->input('worship_groups', []));
        $believer->groups()->sync($request->input('groups', []));

        if ($request->filled('cell_id')) {
            $believer->cells()->sync([$request->cell_id]);
        }

        // ── Langues avec compétences (lu/parlé/écrit) ──
        if ($request->filled('languages')) {
            $syncData = [];
            foreach ($request->languages as $languageId => $skills) {
                // On ne synchronise que les langues où au moins une compétence est cochée
                if (empty($skills)) {
                    continue;
                }
                $syncData[$languageId] = [
                    'lu'    => isset($skills['lu']),
                    'parle' => isset($skills['parle']),
                    'ecrit' => isset($skills['ecrit']),
                ];
            }
            $believer->languages()->sync($syncData);
        } else {
            $believer->languages()->sync([]);
        }

        return redirect()
            ->route('believers.show', $believer)
            ->with('success', 'Fidèle mis à jour avec succès.');
    }

    // -------------------------------------------------------
    // SANCTION
    // -------------------------------------------------------
 
    public function sanction(Request $request, Believer $believer)
    {
        $request->validate([
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after:start_date',
            'reason' => 'required|string|max:1000',
            'decided_by'     => 'nullable|string|max:150',
        ]);
 
        // Créer la sanction
        Sanction::create([
            'believer_id' => $believer->id,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'reason'      => $request->reason,
            'decided_by'  => $request->decided_by,
            'is_active'   => true,
        ]);
        
        ActivityLogger::log("A sanctionné {$believer->full_name} — motif : {$request->reason}");
 
        // Mettre à jour le statut du fidèle
        $believer->update([
            'status'    => 'sanctionne',
            'is_active' => false,
        ]);
 
        return redirect()
            ->route('believers.index')
            ->with('success', "Sanction appliquée à {$believer->full_name}.");
    }

    // -------------------------------------------------------
    // LEVER LA SANCTION
    // -------------------------------------------------------
    public function liftSanction(Request $request, Believer $believer)
    {
        $request->validate([
            'lift_note' => 'nullable|string|max:1000',
        ]);
 
        $sanction = $believer->sanctions()->where('is_active', true)->latest()->first();
 
        if ($sanction) {
            $sanction->update([
                'is_active'  => false,
                'end_date'  => now(),
                'lifted_at'  => now(),
                'lift_note'  => $request->lift_note,
            ]);
        }
        
        ActivityLogger::log("A levé la sanction de {$believer->full_name}");
 
        $believer->update([
            'status'    => 'actif',
            'is_active' => true,
        ]);
 
        return redirect()
            ->route('believers.index')
            ->with('success', "La sanction de {$believer->full_name} a été levée.");
    }
 
    // -------------------------------------------------------
    // DÉPART / DÉCÈS
    // -------------------------------------------------------
 
    public function depart(Request $request, Believer $believer)
    {
        $request->validate([
            'departure_type'   => 'required|in:depart,deces',
            'departure_date'   => 'required|date',
            'departure_destination' => 'nullable|string|max:200',
            'departure_reason' => 'nullable|string|max:1000',
        ]);
 
        Departure::create([
            'believer_id'    => $believer->id,
            'type'           => $request->departure_type,
            'departure_date' => $request->departure_date,
            'destination'    => $request->departure_destination,
            'reason'         => $request->departure_reason,
            'recorded_by'    => auth()->user()->name,
        ]);
        
        ActivityLogger::log("A enregistré le départ de {$believer->full_name} — type : {$request->departure_type}");
 
        // Mettre à jour le statut
        $status = $request->departure_type === 'deces' ? 'decede' : 'parti';
        $believer->update([
            'status'    => $status,
            'is_active' => false,
        ]);
 
        return redirect()
            ->route('believers.index')
            ->with('success', "Le départ de {$believer->full_name} a été enregistré.");
    }

    public function reinstate(Believer $believer)
    {
        if ($believer->status === 'decede') {
            return redirect()->back()
                ->with('error', 'Un fidèle décédé ne peut pas être réintégré.');
        }
 
        // Supprimer l'enregistrement de départ lié
        $believer->departures()->latest()->first()?->delete();
 
        $believer->update([
            'status'    => 'actif',
            'is_active' => true,
        ]);
 
        return redirect()
            ->route('believers.index')
            ->with('success', "{$believer->full_name} a été réintégré(e) dans la communauté.");
    }

    // -------------------------------------------------------
    // TELECHARGER LA FICHE DU FIDÈLE EN PDF
    // -------------------------------------------------------

     public function downloadFiche(Believer $believer)
    {
        $believer->load([
            'address',
            'churchInformation',
            'education',
            'profession',
            'responsibility',
            'teams',
            'groups',
            'cells',
            'family',
            'sanctions' => fn($q) => $q->where('is_active', true)->latest(),
        ]);
 
        $church = \App\Models\Church::instance();
 
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('believers.fiche-fidele', compact('believer', 'church'))
            ->setPaper('a4', 'portrait')
            ->setOption([
                'defaultFont'     => 'Arial',
                'isRemoteEnabled' => true,
                'margin_top'      => 10,   // mm
                'margin_bottom'   => 10,
                'margin_left'     => 12,
                'margin_right'    => 12,
                'dpi'             => 150,
            ]);
 
        $filename = 'fiche-' . strtolower($believer->lastname) . '-' . strtolower($believer->firstname) . '.pdf';
 
        return $pdf->download($filename);
    }

    /**
     * MEMBERSHIP CARD
     */
    public function membershipCard(Believer $believer)
    {
        $believer->load(['address', 'churchInformation', 'profession']);
        $church = Church::instance();

        $pdf = Pdf::loadView('believers.membership-card', compact('believer', 'church'))
            ->setOption([
                'isRemoteEnabled' => true,
                'dpi'             => 150,
            ]);

        return $pdf->download('carte-membre-' . \Illuminate\Support\Str::slug($believer->full_name) . '.pdf');
    }

    public function destroy(Believer $believer)
    {
        ActivityLogger::log("A archivé le fidèle {$believer->full_name} (#{$believer->id})");
        $believer->delete();

        return redirect()
            ->route('believers.index')
            ->with('success', 'Fidèle archivé avec succès.');
    }
}