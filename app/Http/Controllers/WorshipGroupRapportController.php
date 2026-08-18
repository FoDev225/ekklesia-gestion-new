<?php

namespace App\Http\Controllers;

use App\Models\WorshipGroup;
use App\Models\WorshipGroupRapport;
use App\Http\Requests\StoreWorshipGroupRapportRequest;
use Illuminate\Support\Facades\Storage;

class WorshipGroupRapportController extends Controller
{
    public function store(StoreWorshipGroupRapportRequest $request, WorshipGroup $worshipGroup)
    {
        $data = $request->validated();

        if ($request->hasFile('fichier')) {
            $data['fichier'] = $request->file('fichier')
                ->store("worship-groups/{$worshipGroup->id}/rapports", 'public');
        }

        $worshipGroup->rapports()->create($data);

        return redirect()
            ->route('worship-groups.show', $worshipGroup)
            ->with('success', 'Rapport ajouté avec succès.');
    }

    public function destroy(WorshipGroup $worshipGroup, WorshipGroupRapport $rapport)
    {
        if ($rapport->fichier) {
            Storage::disk('public')->delete($rapport->fichier);
        }

        $rapport->delete();

        return redirect()
            ->route('worship-groups.show', $worshipGroup)
            ->with('success', 'Rapport supprimé.');
    }
}