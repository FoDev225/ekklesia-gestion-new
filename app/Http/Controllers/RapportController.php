<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Rapport;
use App\Http\Requests\StoreRapportRequest;
use Illuminate\Support\Facades\Storage;

class RapportController extends Controller
{
    public function store(StoreRapportRequest $request, Group $group)
    {
        $data = $request->validated();

        if ($request->hasFile('fichier')) {
            $data['fichier'] = $request->file('fichier')
                ->store("groups/{$group->id}/rapports", 'public');
        }

        $group->rapports()->create($data);

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Rapport ajouté avec succès.');
    }

    public function destroy(Group $group, Rapport $rapport)
    {
        if ($rapport->fichier) {
            Storage::disk('public')->delete($rapport->fichier);
        }

        $rapport->delete();

        return redirect()
            ->route('groups.show', $group)
            ->with('success', 'Rapport supprimé.');
    }
}