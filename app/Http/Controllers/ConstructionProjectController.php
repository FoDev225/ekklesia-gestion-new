<?php

namespace App\Http\Controllers;

use App\Models\ProjetConstruction;
use App\Http\Requests\ConstructionProjectRequest;
use Illuminate\Support\Facades\Storage;

class ConstructionProjectController extends Controller
{
    public function index()
    {
        $projects = ProjetConstruction::orderByDesc('date_lancement')->paginate(15);

        $stats = [
            'total'         => ProjetConstruction::count(),
            'realises'      => ProjetConstruction::where('status', 'realise')->count(),
            'en_cours'      => ProjetConstruction::where('status', 'en_cours')->count(),
            'cout_total'    => ProjetConstruction::sum('cout'),
        ];

        return view('construction.projects', compact('projects', 'stats'));
    }

    public function store(ConstructionProjectRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('rapport')) {
            $data['rapport_path'] = $request->file('rapport')->store('construction-projects', 'public');
        }

        ProjetConstruction::create($data);

        return redirect()
            ->route('construction.projects')
            ->with('success', 'Projet enregistré avec succès.');
    }

    public function update(ConstructionProjectRequest $request, ProjetConstruction $project)
    {
        $data = $request->validated();

        if ($request->hasFile('rapport')) {
            if ($project->rapport_path) {
                Storage::disk('public')->delete($project->rapport_path);
            }
            $data['rapport_path'] = $request->file('rapport')->store('construction-projects', 'public');
        }

        $project->update($data);

        return redirect()
            ->route('construction.projects')
            ->with('success', 'Projet mis à jour.');
    }

    public function destroy(ProjetConstruction $project)
    {
        if ($project->rapport_path) {
            Storage::disk('public')->delete($project->rapport_path);
        }

        $project->delete();

        return redirect()
            ->route('construction.projects')
            ->with('success', 'Projet supprimé.');
    }
}