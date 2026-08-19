@extends('layouts.dashboard')

@section('title', 'Modifier fidèle')
@section('page-title', 'Gestion des fidèles')

@section('content')
<div class="max-w-5xl mx-auto space-y-4">

    {{-- Barre de navigation --}}
    <div class="flex items-center gap-3">
        <a href="{{ route(auth()->user()->dashboardRoute()) }}"
           class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('believers.index') }}"
           class="text-sm text-gray-500 hover:text-gray-700">Fidèles</a>
        <span class="text-gray-300">/</span>
        <a href="{{ route('believers.show', $believer) }}"
           class="text-sm text-gray-500 hover:text-gray-700">{{ $believer->full_name }}</a>
        <span class="text-gray-300">/</span>
        <span class="text-sm text-gray-700 font-medium">Modifier</span>
    </div>

            @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>Veuillez corriger les erreurs suivantes :</strong>
                <ul class="mt-1 list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('believers.update', $believer) }}" enctype="multipart/form-data">
                @csrf 
                @method('PUT')

                {{-- Onglets navigation --}}
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-6 overflow-x-auto" id="tabs-nav">
                        @foreach([
                            ['id' => 'general',        'label' => '① Infos générales'],
                            ['id' => 'adresse',        'label' => '② Adresse & Contact'],
                            ['id' => 'eglise',         'label' => '③ Vie spirituelle'],
                            ['id' => 'education',      'label' => '④ Éducation'],
                            ['id' => 'profession',     'label' => '⑤ Profession'],
                            ['id' => 'responsabilite', 'label' => '⑥ Responsabilités'],
                            ['id' => 'appartenance',   'label' => '⑦ Équipes & Groupes'],
                        ] as $tab)
                        <button type="button"
                            onclick="switchTab('{{ $tab['id'] }}')"
                            id="tab-{{ $tab['id'] }}"
                            class="tab-btn whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700">
                            {{ $tab['label'] }}
                        </button>
                        @endforeach
                    </nav>
                </div>

                {{-- ① INFOS GÉNÉRALES --}}
                <div id="panel-general" class="tab-panel bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-medium text-gray-700 mb-4">Informations générales</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Matricule</label>
                            <input type="text" value="{{ $believer->register_number }}" disabled
                                class="mt-1 block w-full border-gray-200 rounded-md bg-gray-50 text-gray-700 text-sm font-mono font-semibold">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom <span class="text-red-500">*</span></label>
                            <input type="text" name="lastname" value="{{ old('lastname', $believer->lastname) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('lastname') border-red-300 @enderror">
                            @error('lastname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prénom <span class="text-red-500">*</span></label>
                            <input type="text" name="firstname" value="{{ old('firstname', $believer->firstname) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('firstname') border-red-300 @enderror">
                            @error('firstname') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Genre <span class="text-red-500">*</span></label>
                            <select name="gender" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="M" @selected(old('gender', $believer->gender) === 'M')>Homme</option>
                                <option value="F" @selected(old('gender', $believer->gender) === 'F')>Femme</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Situation maritale</label>
                            <select name="marital_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Choisir --</option>
                                <option value="celibataire" @selected(old('marital_status', $believer->marital_status) === 'celibataire')>Célibataire</option>
                                <option value="marie"       @selected(old('marital_status', $believer->marital_status) === 'marie')>Marié(e)</option>
                                <option value="veuf"        @selected(old('marital_status', $believer->marital_status) === 'veuf')>Veuf/Veuve</option>
                                <option value="divorce"     @selected(old('marital_status', $believer->marital_status) === 'divorce')>Divorcé(e)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                            <input type="date" name="birth_date"
                                value="{{ old('birth_date', $believer->birth_date?->format('Y-m-d')) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lieu de naissance</label>
                            <input type="text" name="birth_place" value="{{ old('birth_place', $believer->birth_place) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nationalité</label>
                            <input type="text" name="nationality" value="{{ old('nationality', $believer->nationality) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Numéro CNI</label>
                            <input type="text" name="cni_number" value="{{ old('cni_number', $believer->cni_number) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('cni_number') border-red-300 @enderror">
                            @error('cni_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre d'enfants</label>
                            <input type="number" name="number_of_children" min="0"
                                value="{{ old('number_of_children', $believer->number_of_children) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Photo de profil</label>

                            @if($believer->profile_picture)
                            <div class="flex items-center gap-3 mt-2 mb-2">
                                <img src="{{ $believer->profile_picture_url }}" alt="Photo actuelle"
                                    class="w-16 h-16 rounded-full object-cover border">
                                <label class="flex items-center gap-1 text-xs text-red-600">
                                    <input type="checkbox" name="remove_profile_picture" value="1">
                                    Retirer la photo actuelle
                                </label>
                            </div>
                            @endif

                            <input type="file" name="profile_picture" accept="image/*"
                                class="mt-1 block w-full text-sm">
                            <p class="text-xs text-gray-400 mt-1">Laissez vide pour conserver la photo actuelle.</p>
                            @error('profile_picture')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- ② ADRESSE --}}
                <div id="panel-adresse" class="tab-panel hidden bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-medium text-gray-700 mb-4">Adresse & Contact</h3>
                    @php $addr = $believer->address; @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Commune</label>
                            <input type="text" name="address[commune]" value="{{ old('address.commune', $addr?->commune) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quartier</label>
                            <input type="text" name="address[quartier]" value="{{ old('address.quartier', $addr?->quartier) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sous-quartier</label>
                            <input type="text" name="address[sous_quartier]" value="{{ old('address.sous_quartier', $addr?->sous_quartier) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input type="text" name="address[phone]" value="{{ old('address.phone', $addr?->phone) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">WhatsApp</label>
                            <input type="text" name="address[whatsapp]" value="{{ old('address.whatsapp', $addr?->whatsapp) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="address[email]" value="{{ old('address.email', $addr?->email) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                {{-- ③ VIE SPIRITUELLE --}}
                <div id="panel-eglise" class="tab-panel hidden bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-medium text-gray-700 mb-4">Vie spirituelle & Église</h3>
                    @php $ci = $believer->churchInformation; @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Connaissance à l'église</label>
                            <input type="text" name="church[connaissance_eglise]" value="{{ old('church.connaissance_eglise', $ci?->connaissance_eglise) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Église d'origine</label>
                            <input type="text" name="church[original_church]" value="{{ old('church.original_church', $ci?->original_church) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Année d'arrivée</label>
                            <input type="number" name="church[arrival_year]" value="{{ old('church.arrival_year', $ci?->arrival_year) }}"
                                min="1900" max="{{ date('Y') }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de conversion</label>
                            <input type="date" name="church[conversion_date]" value="{{ old('church.conversion_date', $ci?->conversion_date?->format('Y-m-d')) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lieu de conversion</label>
                            <input type="text" name="church[conversion_place]" value="{{ old('church.conversion_place', $ci?->conversion_place) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div class="md:col-span-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700 cursor-pointer">
                                <input type="checkbox" name="church[baptised]" value="1"
                                    @checked(old('church.baptised', $ci?->baptised))
                                    class="rounded border-gray-300 text-indigo-600"
                                    onchange="toggleBaptism(this)">
                                Baptisé(e)
                            </label>
                        </div>
                        <div id="baptism-fields" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 {{ old('church.baptised', $ci?->baptised) ? '' : 'hidden' }}">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Date de baptême</label>
                                <input type="date" name="church[baptism_date]" value="{{ old('church.baptism_date', $ci?->baptism_date?->format('Y-m-d')) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Lieu de baptême</label>
                                <input type="text" name="church[baptism_place]" value="{{ old('church.baptism_place', $ci?->baptism_place) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pasteur officiant</label>
                                <input type="text" name="church[baptism_pastor]" value="{{ old('church.baptism_pastor', $ci?->baptism_pastor) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">N° carte de baptême</label>
                                <input type="text" name="church[baptism_card_number]" value="{{ old('church.baptism_card_number', $ci?->baptism_card_number) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ④ ÉDUCATION --}}
                <div id="panel-education" class="tab-panel hidden bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-medium text-gray-700 mb-4">Éducation</h3>
                    @php $edu = $believer->education; @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Niveau d'étude</label>
                            <select name="education[niveau_etude]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">-- Choisir --</option>
                                @foreach(['Primaire','Secondaire','Baccalauréat','Licence','Master','Doctorat','Aucun'] as $n)
                                    <option value="{{ $n }}" @selected(old('education.niveau_etude', $edu?->niveau_etude) === $n)>{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Diplôme obtenu</label>
                            <input type="text" name="education[diploma]" value="{{ old('education.diploma', $edu?->diploma) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Qualification</label>
                            <input type="text" name="education[qualification]" value="{{ old('education.qualification', $edu?->qualification) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                {{-- ⑤ PROFESSION --}}
                <div id="panel-profession" class="tab-panel hidden bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-medium text-gray-700 mb-4">Profession</h3>
                    @php $pro = $believer->profession; @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Profession</label>
                            <input type="text" name="profession[profession]" value="{{ old('profession.profession', $pro?->profession) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fonction</label>
                            <input type="text" name="profession[function]" value="{{ old('profession.function', $pro?->function) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Entreprise</label>
                            <input type="text" name="profession[company]" value="{{ old('profession.company', $pro?->company) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Contact professionnel</label>
                            <input type="text" name="profession[professional_contact]" value="{{ old('profession.professional_contact', $pro?->professional_contact) }}"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                </div>

                {{-- ⑥ RESPONSABILITÉS --}}
                <div id="panel-responsabilite" class="tab-panel hidden bg-white shadow-sm rounded-lg p-6">
                    <h3 class="font-medium text-gray-700 mb-4">Responsabilités dans l'église</h3>
                    @php $resp = $believer->responsibility; @endphp
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Anciennes responsabilités</label>
                            <textarea name="responsibility[old]" rows="2"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('responsibility.old', $resp?->old) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Responsabilités actuelles</label>
                            <textarea name="responsibility[current]" rows="2"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('responsibility.current', $resp?->current) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Souhaits de service</label>
                            <textarea name="responsibility[desire]" rows="2"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('responsibility.desire', $resp?->desire) }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- ⑦ APPARTENANCE --}}
                <div id="panel-appartenance" class="tab-panel hidden bg-white shadow-sm rounded-lg p-6 space-y-6">
                    <h3 class="font-medium text-gray-700 mb-4">Équipes & Groupes</h3>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Groupes</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach($teams as $team)
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="teams[]" value="{{ $team->id }}"
                                    @checked(in_array($team->id, old('teams', $believer->teams->pluck('id')->toArray())))
                                    class="rounded border-gray-300 text-indigo-600">
                                {{ $team->name }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Équipes</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach($groups as $group)
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="groups[]" value="{{ $group->id }}"
                                    @checked(in_array($group->id, old('groups', $believer->groups->pluck('id')->toArray())))
                                    class="rounded border-gray-300 text-indigo-600">
                                {{ $group->name }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Groupes de louange</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                            @foreach($worshipGroups as $group)
                            <label class="flex items-center gap-2 text-sm cursor-pointer">
                                <input type="checkbox" name="worship_groups[]" value="{{ $group->id }}"
                                    @checked(in_array($group->id, old('worship_groups', $believer->worshipGroups->pluck('id')->toArray())))
                                    class="rounded border-gray-300 text-indigo-600">
                                {{ $group->name }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cellule de quartier</label>
                        <select name="cell_id" class="mt-1 block w-full md:w-1/2 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Aucune --</option>
                            @foreach($cells as $cell)
                                <option value="{{ $cell->id }}"
                                    @selected(old('cell_id', $believer->cells->first()?->id) == $cell->id)>
                                    {{ $cell->name }} ({{ $cell->quartier }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Langues</label>
                        <div class="border border-gray-200 rounded-md overflow-hidden">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase text-xs">Langue</th>
                                        <th class="px-3 py-2 text-center font-medium text-gray-500 uppercase text-xs">Lu</th>
                                        <th class="px-3 py-2 text-center font-medium text-gray-500 uppercase text-xs">Parlé</th>
                                        <th class="px-3 py-2 text-center font-medium text-gray-500 uppercase text-xs">Écrit</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($languages as $language)
                                    @php
                                        $existingPivot = isset($believer) ? $believer->languages->firstWhere('id', $language->id)?->pivot : null;
                                    @endphp
                                    <tr>
                                        <td class="px-3 py-2 text-gray-700">{{ $language->name }}</td>
                                        <td class="px-3 py-2 text-center">
                                            <input type="checkbox" name="languages[{{ $language->id }}][lu]" value="1"
                                                @checked(old("languages.{$language->id}.lu", $existingPivot?->lu))
                                                class="rounded border-gray-300 text-indigo-600">
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <input type="checkbox" name="languages[{{ $language->id }}][parle]" value="1"
                                                @checked(old("languages.{$language->id}.parle", $existingPivot?->parle))
                                                class="rounded border-gray-300 text-indigo-600">
                                        </td>
                                        <td class="px-3 py-2 text-center">
                                            <input type="checkbox" name="languages[{{ $language->id }}][ecrit]" value="1"
                                                @checked(old("languages.{$language->id}.ecrit", $existingPivot?->ecrit))
                                                class="rounded border-gray-300 text-indigo-600">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="text-xs text-gray-400 mt-1">Cochez au moins une compétence pour enregistrer une langue.</p>
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="mt-6 flex items-center justify-between">
                    <a href="{{ route('believers.index') }}"
                       class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm">
                        Annuler
                    </a>
                    <div class="flex gap-3">
                        <button type="button" onclick="prevTab()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 text-sm">
                            ← Précédent
                        </button>
                        <button type="button" onclick="nextTab()" id="btn-next"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
                            Suivant →
                        </button>
                        <button type="submit" id="btn-submit"
                            class="hidden px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">
                            ✓ Enregistrer les modifications
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        const tabs = ['general','adresse','eglise','education','profession','responsabilite','appartenance'];
        let current = 0;

        function switchTab(id) { current = tabs.indexOf(id); renderTab(); }

        function renderTab() {
            tabs.forEach((t, i) => {
                document.getElementById('panel-' + t).classList.toggle('hidden', i !== current);
                const btn = document.getElementById('tab-' + t);
                btn.classList.toggle('border-indigo-500', i === current);
                btn.classList.toggle('text-indigo-600', i === current);
                btn.classList.toggle('border-transparent', i !== current);
                btn.classList.toggle('text-gray-500', i !== current);
            });
            const isLast = current === tabs.length - 1;
            document.getElementById('btn-next').classList.toggle('hidden', isLast);
            document.getElementById('btn-submit').classList.toggle('hidden', !isLast);
        }

        function nextTab() { if (current < tabs.length - 1) { current++; renderTab(); } }
        function prevTab() { if (current > 0) { current--; renderTab(); } }
        function toggleBaptism(el) {
            document.getElementById('baptism-fields').classList.toggle('hidden', !el.checked);
        }

        renderTab();
    </script>
</div>
@endsection