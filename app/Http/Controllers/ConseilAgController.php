<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ConseilAg;
use App\Http\Requests\ConseilAgRequest;
use Illuminate\Support\Facades\Storage;

class ConseilAgController extends Controller
{
    public function index()
    {
        $ag = ConseilAg::orderByDesc('ag_date')->paginate(15);

        return view('conseil.ag', compact('ag'));
    }

    public function store(ConseilAgRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('rapport_path')) {
            $data['rapport_path'] = $request->file('rapport_path')->store('conseil-ag', 'public');
        }

        ConseilAg::create($data);

        return redirect()
            ->route('conseil.ag')
            ->with('success', 'Réunion enregistrée avec succès.');
    }

    public function destroy(ConseilAg $ag)
    {
        if ($ag->rapport_path) {
            Storage::disk('public')->delete($ag->rapport_path);
        }

        $ag->delete();

        return redirect()
            ->route('conseil.ag')
            ->with('success', 'AG supprimée.');
    }
}
