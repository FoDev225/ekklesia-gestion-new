<?php

namespace App\Http\Controllers;

use App\Models\Believer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BelieverPhotoImportController extends Controller
{
    public function form()
    {
        return view('believers.photo-import-form');
    }

    public function import(Request $request)
    {
        $request->validate([
            'photos'   => 'required|array',
            'photos.*' => 'image|mimes:jpg,jpeg,png|max:5120', // 5 Mo max par photo
        ]);

        $imported = 0;
        $notFound = [];

        foreach ($request->file('photos') as $file) {
            // Le nom du fichier (sans extension) doit correspondre exactement au matricule
            $matricule = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $believer = Believer::where('register_number', $matricule)->first();

            if (!$believer) {
                $notFound[] = $file->getClientOriginalName();
                continue;
            }

            // Supprime l'ancienne photo si elle existe
            if ($believer->profile_picture) {
                Storage::disk('public')->delete($believer->profile_picture);
            }

            $path = $file->store('believers/profile-pictures', 'public');
            $believer->update(['profile_picture' => $path]);
            $imported++;
        }

        return redirect()
            ->route('believers.photo-import.form')
            ->with('success', "{$imported} photo(s) importée(s) avec succès.")
            ->with('not_found', $notFound);
    }
}