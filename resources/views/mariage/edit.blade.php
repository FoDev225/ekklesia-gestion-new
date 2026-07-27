@extends('layouts.dashboard')

@section('title', 'Modifier mariage')
@section('page-title', 'Registre des mariages')

@section('content')
<div class="max-w-4xl mx-auto space-y-4">

    {{-- Navigation --}}
    <div class="flex items-center gap-3">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}" class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('mariage.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Mariages</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('mariage.show', $mariage) }}" class="text-sm text-gray-500 hover:text-gray-700">
            {{ $mariage->groom_display_name }} & {{ $mariage->bride_display_name }}
        </a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Modifier</span>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        <strong>Erreurs :</strong>
        <ul class="mt-1 list-disc list-inside text-sm">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('mariage.update', $mariage) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        {{-- ══ ÉPOUX ══ --}}
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3 flex items-center gap-2">
                <span class="px-2 py-0.5 rounded text-xs font-bold text-white" style="background:#3A9BDC">ÉPOUX</span>
            </h3>

            @php $groomType = old('groom_type', $mariage->groom_id ? 'believer' : 'external'); @endphp
            <div class="flex gap-4 mb-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="radio" name="groom_type" value="believer"
                        {{ $groomType === 'believer' ? 'checked' : '' }}
                        onchange="togglePerson('groom', 'believer')" class="text-indigo-600">
                    Fidèle de l'église
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="radio" name="groom_type" value="external"
                        {{ $groomType === 'external' ? 'checked' : '' }}
                        onchange="togglePerson('groom', 'external')" class="text-indigo-600">
                    Externe à l'église
                </label>
            </div>

            <div id="groom_believer_fields" class="{{ $groomType === 'external' ? 'hidden' : '' }}">
                <label class="block text-sm font-medium text-gray-700">Sélectionner le fidèle</label>
                <select name="groom_id" id="groom_id_select"
                    class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Choisir un fidèle --</option>
                    @foreach($believers->where('gender', 'M') as $b)
                        <option value="{{ $b->id }}"
                            @selected(old('groom_id', $mariage->groom_id) == $b->id)>
                            {{ $b->lastname }} {{ $b->firstname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div id="groom_name_field" class="{{ $groomType !== 'external' ? 'hidden' : '' }}">
                    <label class="block text-sm font-medium text-gray-700">Nom & Prénom</label>
                    <input type="text" name="groom_name"
                        value="{{ old('groom_name', $mariage->groom_name) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                    <input type="date" name="groom_birthdate"
                        value="{{ old('groom_birthdate', $mariage->groom_birthdate?->format('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu de naissance</label>
                    <input type="text" name="groom_birth_place"
                        value="{{ old('groom_birth_place', $mariage->groom_birth_place) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de baptême</label>
                    <input type="date" name="groom_bapistism_date"
                        value="{{ old('groom_bapistism_date', $mariage->groom_bapistism_date?->format('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu de baptême</label>
                    <input type="text" name="groom_bapistism_place"
                        value="{{ old('groom_bapistism_place', $mariage->groom_bapistism_place) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pasteur du baptême</label>
                    <input type="text" name="baptism_officer_groom"
                        value="{{ old('baptism_officer_groom', $mariage->baptism_officer_groom) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Profession</label>
                    <input type="text" name="groom_profession"
                        value="{{ old('groom_profession', $mariage->groom_profession) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Photo</label>
                    @if($mariage->groom_photo)
                    <div class="mb-2">
                        <img src="{{ Storage::url($mariage->groom_photo) }}" class="h-16 w-16 object-cover rounded border">
                        <p class="text-xs text-gray-400 mt-1">Photo actuelle</p>
                    </div>
                    @endif
                    <input type="file" name="groom_photo" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700">
                </div>
            </div>
        </div>

        {{-- ══ ÉPOUSE ══ --}}
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3 flex items-center gap-2">
                <span class="px-2 py-0.5 rounded text-xs font-bold text-white" style="background:#C9A635">ÉPOUSE</span>
            </h3>

            @php $brideType = old('bride_type', $mariage->bride_id ? 'believer' : 'external'); @endphp
            <div class="flex gap-4 mb-2">
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="radio" name="bride_type" value="believer"
                        {{ $brideType === 'believer' ? 'checked' : '' }}
                        onchange="togglePerson('bride', 'believer')" class="text-indigo-600">
                    Fidèle de l'église
                </label>
                <label class="flex items-center gap-2 cursor-pointer text-sm">
                    <input type="radio" name="bride_type" value="external"
                        {{ $brideType === 'external' ? 'checked' : '' }}
                        onchange="togglePerson('bride', 'external')" class="text-indigo-600">
                    Externe à l'église
                </label>
            </div>

            <div id="bride_believer_fields" class="{{ $brideType === 'external' ? 'hidden' : '' }}">
                <label class="block text-sm font-medium text-gray-700">Sélectionner la fidèle</label>
                <select name="bride_id" id="bride_id_select"
                    class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">-- Choisir une fidèle --</option>
                    @foreach($believers->where('gender', 'F') as $b)
                        <option value="{{ $b->id }}"
                            @selected(old('bride_id', $mariage->bride_id) == $b->id)>
                            {{ $b->lastname }} {{ $b->firstname }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div id="bride_name_field" class="{{ $brideType !== 'external' ? 'hidden' : '' }}">
                    <label class="block text-sm font-medium text-gray-700">Nom & Prénom</label>
                    <input type="text" name="bride_name"
                        value="{{ old('bride_name', $mariage->bride_name) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                    <input type="date" name="bride_birthdate"
                        value="{{ old('bride_birthdate', $mariage->bride_birthdate?->format('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu de naissance</label>
                    <input type="text" name="bride_birth_place"
                        value="{{ old('bride_birth_place', $mariage->bride_birth_place) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de baptême</label>
                    <input type="date" name="bride_bapistism_date"
                        value="{{ old('bride_bapistism_date', $mariage->bride_bapistism_date?->format('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu de baptême</label>
                    <input type="text" name="bride_bapistism_place"
                        value="{{ old('bride_bapistism_place', $mariage->bride_bapistism_place) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pasteur du baptême</label>
                    <input type="text" name="baptism_officer_bride"
                        value="{{ old('baptism_officer_bride', $mariage->baptism_officer_bride) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Profession</label>
                    <input type="text" name="bride_profession"
                        value="{{ old('bride_profession', $mariage->bride_profession) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Photo</label>
                    @if($mariage->bride_photo)
                    <div class="mb-2">
                        <img src="{{ Storage::url($mariage->bride_photo) }}" class="h-16 w-16 object-cover rounded border">
                        <p class="text-xs text-gray-400 mt-1">Photo actuelle</p>
                    </div>
                    @endif
                    <input type="file" name="bride_photo" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-4 file:rounded file:border-0 file:text-sm file:font-medium file:bg-yellow-50 file:text-yellow-700">
                </div>
            </div>
        </div>

        {{-- ══ MARIAGE CIVIL ══ --}}
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Cérémonie civile</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="civil_marriage_date"
                        value="{{ old('civil_marriage_date', $mariage->civil_marriage_date?->format('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu <span class="text-red-500">*</span></label>
                    <input type="text" name="civil_marriage_place"
                        value="{{ old('civil_marriage_place', $mariage->civil_marriage_place) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </div>

        {{-- ══ MARIAGE RELIGIEUX ══ --}}
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Cérémonie religieuse</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                    <input type="date" name="religious_marriage_date"
                        value="{{ old('religious_marriage_date', $mariage->religious_marriage_date?->format('Y-m-d')) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu <span class="text-red-500">*</span></label>
                    <input type="text" name="religious_marriage_place"
                        value="{{ old('religious_marriage_place', $mariage->religious_marriage_place) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Maître de cérémonie</label>
                    <input type="text" name="wedding_mc"
                        value="{{ old('wedding_mc', $mariage->wedding_mc) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prédicateur <span class="text-red-500">*</span></label>
                    <input type="text" name="wedding_preacher"
                        value="{{ old('wedding_preacher', $mariage->wedding_preacher) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">La Bible remise par</label>
                    <input type="text" name="hand_bible"
                        value="{{ old('hand_bible', $mariage->hand_bible) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pasteur officiant <span class="text-red-500">*</span></label>
                    <input type="text" name="officiant"
                        value="{{ old('officiant', $mariage->officiant) }}"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
        </div>

        {{-- ══ TÉMOINS ══ --}}
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <h3 class="font-semibold text-gray-700 border-b pb-3">Témoins</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-3">
                    <p class="text-xs font-semibold text-blue-700 uppercase">Témoin de l'époux</p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                        <input type="text" name="groom_witness"
                            value="{{ old('groom_witness', $mariage->groom_witness) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Profession</label>
                        <input type="text" name="groom_witness_profession"
                            value="{{ old('groom_witness_profession', $mariage->groom_witness_profession) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="space-y-3">
                    <p class="text-xs font-semibold text-yellow-700 uppercase">Témoin de l'épouse</p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                        <input type="text" name="bride_witness"
                            value="{{ old('bride_witness', $mariage->bride_witness) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Profession</label>
                        <input type="text" name="bride_witness_profession"
                            value="{{ old('bride_witness_profession', $mariage->bride_witness_profession) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('mariage.show', $mariage) }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md text-sm hover:bg-gray-300">Annuler</a>
            <button type="submit"
                class="px-6 py-2 text-white rounded-md text-sm font-medium" style="background:#3FA46A">
                ✓ Enregistrer les modifications
            </button>
        </div>
    </form>

</div>

<script>
function togglePerson(person, type) {
    const believerFields = document.getElementById(person + '_believer_fields');
    const nameField = document.getElementById(person + '_name_field');
    if (type === 'believer') {
        believerFields.classList.remove('hidden');
        nameField.classList.add('hidden');
    } else {
        believerFields.classList.add('hidden');
        nameField.classList.remove('hidden');
        const select = document.getElementById(person + '_id_select');
        if (select) select.value = '';
    }
}
</script>
@endsection