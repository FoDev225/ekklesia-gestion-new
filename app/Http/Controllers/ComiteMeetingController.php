<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ComiteMeeting;
use App\Http\Requests\ComiteMeetingRequest;
use Illuminate\Support\Facades\Storage;

class ComiteMeetingController extends Controller
{
    public function index()
    {
        $meetings = ComiteMeeting::orderByDesc('meeting_date')->paginate(15);

        return view('comite.meetings', compact('meetings'));
    }

    public function store(ComiteMeetingRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('rapport_path')) {
            $data['rapport_path'] = $request->file('rapport_path')->store('committee-meetings', 'public');
        }

        ComiteMeeting::create($data);

        return redirect()
            ->route('comite.meetings')
            ->with('success', 'Réunion enregistrée avec succès.');
    }

    public function destroy(ComiteMeeting $meeting)
    {
        if ($meeting->rapport_path) {
            Storage::disk('public')->delete($meeting->rapport_path);
        }

        $meeting->delete();

        return redirect()
            ->route('comite.meetings')
            ->with('success', 'Réunion supprimée.');
    }
}

