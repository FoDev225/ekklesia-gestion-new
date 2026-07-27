<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Believer;
use App\Models\NewComer;
use App\Models\Church;
use App\Http\Requests\PublicBelieverRegistrationRequest;
use App\Http\Requests\PublicNewcomerRegistrationRequest;

class PublicRegistrationController extends Controller
{
    // ═══════════════════════════════════════════
    // FIDÈLE — auto-enregistrement
    // ═══════════════════════════════════════════

    public function showBelieverForm()
    {
        $church = Church::instance();
        return view('public.believer-form', compact('church'));
    }

    public function storeBeliever(PublicBelieverRegistrationRequest $request)
    {
        $data = $request->validated();

        $believer = Believer::create([
            'lastname'           => $data['lastname'],
            'firstname'          => $data['firstname'],
            'gender'             => $data['gender'],
            'birth_date'         => $data['birth_date'] ?? null,
            'birth_place'        => $data['birth_place'] ?? null,
            'nationality'        => $data['nationality'] ?? 'Ivoirienne',
            'marital_status'     => $data['marital_status'] ?? null,
            'number_of_children' => $data['number_of_children'] ?? 0,
            'status'             => 'actif',
            'is_active'          => true,
        ]);

        $believer->address()->create($data['address'] ?? []);

        $believer->churchInformation()->create([
            'connaissance_eglise' => $data['church']['connaissance_eglise'] ?? null,
            'original_church'     => $data['church']['original_church'] ?? null,
            'baptised'            => $request->boolean('church.baptised'),
        ]);

        return redirect()->route('public.believer.success');
    }

    public function believerSuccess()
    {
        $church = Church::instance();
        return view('public.believer-success', compact('church'));
    }

    // ═══════════════════════════════════════════
    // NOUVELLE PERSONNE — enregistré par le service d'ordre
    // ═══════════════════════════════════════════

    public function showNewcomerForm()
    {
        $church = Church::instance();
        return view('public.newcomer-form', compact('church'));
    }

    public function storeNewcomer(PublicNewcomerRegistrationRequest $request)
    {
        $data = $request->validated();
        unset($data['website']);

        $data['first_visit_date'] = now()->toDateString();
        $data['is_recommended']   = $request->boolean('is_recommended');

        NewComer::create($data);

        return redirect()->route('public.newcomer.success');
    }

    public function newcomerSuccess()
    {
        $church = Church::instance();
        return view('public.newcomer-success', compact('church'));
    }

    public function qrCodes()
    {
        return view('admin.public-qrcodes');
    }
}