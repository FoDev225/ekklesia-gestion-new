@extends('layouts.dashboard')

@section('title', 'Fiche fidèle')
@section('page-title', 'Gestion des fidèles')

@section('content')
<div class="max-w-5xl mx-auto space-y-4">

    {{-- Barre de navigation --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route(auth()->user()->dashboardRoute()) }}"
               class="text-sm text-gray-500 hover:text-gray-700">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <a href="{{ route('believers.index') }}"
               class="text-sm text-gray-500 hover:text-gray-700">Fidèles</a>
            <span class="text-gray-300">/</span>
            <span class="text-sm text-gray-700 font-medium">{{ $believer->full_name }}</span>
        </div>
        <div class="flex gap-2 flex-wrap">
            {{-- Télécharger la fiche --}}
            <a href="{{ route('believers.fiche', $believer) }}"
            target="_blank"
            class="px-3 py-1.5 text-white text-sm rounded-md flex items-center gap-1"
            style="background:#1a2e4a">
                📄 Fiche fidèle PDF
            </a>
            {{-- <a href="{{ route('believers.card', $believer) }}"
                class="inline-flex items-center px-4 py-2 text-white text-sm font-medium rounded-md"
                style="background:#C9A635">
                    🪪 Carte de membre
            </a> --}}
            @can('believers.edit')
            <a href="{{ route('believers.edit', $believer) }}"
            class="px-4 py-2 text-white text-sm rounded-md" style="background:#C9A635">
                Modifier
            </a>

            {{-- Sanction --}}
            @if($believer->sanctions()->where('is_active', true)->exists())
                <button type="button"
                    onclick="openLiftSanctionModal({{ $believer->id }}, '{{ addslashes($believer->full_name) }}')"
                    class="px-4 py-2 bg-green-500 text-white text-sm rounded-md hover:bg-green-400">
                    Lever la sanction
                </button>
            @else
                <button type="button"
                    onclick="openSanctionModal({{ $believer->id }}, '{{ addslashes($believer->full_name) }}')"
                    class="px-4 py-2 bg-red-400 text-white text-sm rounded-md hover:bg-red-500">
                    Sanctionner
                </button>
            @endif

            {{-- Départ / Décès / Réintégration --}}
            @if(!in_array($believer->status, ['parti', 'decede']))
            <button type="button"
                onclick="openDepartModal({{ $believer->id }}, '{{ addslashes($believer->full_name) }}')"
                class="px-4 py-2 bg-gray-200 text-gray-700 text-sm rounded-md hover:bg-gray-300">
                Départ
            </button>
            @endif
            @if($believer->status === 'parti')
            <form method="POST" action="{{ route('believers.reinstate', $believer) }}" class="inline">
                @csrf @method('PATCH')
                <button type="submit"
                    onclick="return confirm('Réintégrer {{ addslashes($believer->full_name) }} ?')"
                    class="px-4 py-2 bg-blue-100 text-blue-700 text-sm rounded-md hover:bg-blue-200">
                    Réintégrer
                </button>
            </form>
            @endif
            @if($believer->status === 'decede')
            <span class="inline-flex items-center px-2.5 py-2 bg-gray-100 text-gray-400 text-xs rounded">
                🕊 Décédé
            </span>
            @endif
            @endcan

            @can('believers.delete')
            <form method="POST" action="{{ route('believers.destroy', $believer) }}"
                onsubmit="return confirm('Archiver ce fidèle ?')">
                @csrf @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 bg-red-500 text-white text-sm rounded-md hover:bg-red-600">
                    Archiver
                </button>
            </form>
            @endcan
        </div>
    </div>

    {{-- En-tête identité --}}
    <div class="bg-white shadow-sm rounded-lg p-6">
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-4">
                {{-- Photo de profil --}}
                @if($believer->profile_picture)
                <img src="{{ $believer->profile_picture_url }}" alt="{{ $believer->full_name }}"
                    class="w-20 h-20 rounded-full object-cover border-2 border-gray-100 flex-shrink-0">
                @else
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-2xl font-bold flex-shrink-0">
                    {{ strtoupper(substr($believer->firstname, 0, 1) . substr($believer->lastname, 0, 1)) }}
                </div>
                @endif

                <div>
                    <h3 class="text-2xl font-bold text-gray-900">{{ $believer->full_name }}</h3>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $believer->age_group_color }}">
                            {{ $believer->age_group }}
                        </span>
                        <span class="text-sm text-gray-500">{{ $believer->gender_label }}</span>
                        <span class="text-sm text-gray-500">{{ $believer->marital_status }}</span>
                        @if($believer->age)
                            <span class="text-sm text-gray-500">{{ $believer->age }} ans</span>
                        @endif
                        @if($believer->sanctions()->where('is_active', true)->exists())
                            <span class="text-sm bg-red-500 text-white px-2 py-1 rounded-full">Sous discipline</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-right text-xs text-gray-400">
                <div>Fidèle #{{ $believer->id }}</div>
                <div class="font-mono font-semibold text-gray-600">{{ $believer->register_number }}</div>
                <div>Enregistré le {{ $believer->created_at->format('d/m/Y') }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Infos générales --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Informations générales</h4>
            @include('believers._info-row', ['label' => 'Nom', 'value' => $believer->lastname])
            @include('believers._info-row', ['label' => 'Prénom', 'value' => $believer->firstname])
            @include('believers._info-row', ['label' => 'Numéro CNI', 'value' => $believer->cni_number])
            @include('believers._info-row', ['label' => 'Date de naissance', 'value' => $believer->birth_date?->format('d/m/Y')])
            @include('believers._info-row', ['label' => 'Lieu de naissance', 'value' => $believer->birth_place])
            @include('believers._info-row', ['label' => 'Nationalité', 'value' => $believer->nationality])
            @include('believers._info-row', ['label' => 'Nombre d\'enfants', 'value' => $believer->number_of_children])
            @include('believers._info-row', ['label' => 'Famille', 'value' => $believer->family?->name])
        </div>

        {{-- Adresse & Contact --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Adresse & Contact</h4>
            @if($believer->address)
                @include('believers._info-row', ['label' => 'Commune', 'value' => $believer->address->commune])
                @include('believers._info-row', ['label' => 'Quartier', 'value' => $believer->address->quartier])
                @include('believers._info-row', ['label' => 'Sous-quartier', 'value' => $believer->address->sous_quartier])
                @include('believers._info-row', ['label' => 'Téléphone', 'value' => $believer->address->phone])
                @include('believers._info-row', ['label' => 'WhatsApp', 'value' => $believer->address->whatsapp])
                @include('believers._info-row', ['label' => 'Email', 'value' => $believer->address->email])
            @else
                <p class="text-gray-400 text-sm">Aucune adresse enregistrée.</p>
            @endif
        </div>

        {{-- Vie spirituelle --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Vie spirituelle</h4>
            @if($believer->churchInformation)
                @php $ci = $believer->churchInformation; @endphp
                @include('believers._info-row', ['label' => 'Connaissance de l\'église', 'value' => $ci->connaissance_eglise])
                @include('believers._info-row', ['label' => 'Église d\'origine', 'value' => $ci->original_church])
                @include('believers._info-row', ['label' => 'Année d\'arrivée', 'value' => $ci->arrival_year])
                @include('believers._info-row', ['label' => 'Date de conversion', 'value' => $ci->conversion_date?->format('d/m/Y')])
                @include('believers._info-row', ['label' => 'Lieu de conversion', 'value' => $ci->conversion_place])
                <div class="flex justify-between py-1 border-b border-gray-50">
                    <span class="text-sm text-gray-500">Baptisé(e)</span>
                    <span class="text-sm font-medium">
                        @if($ci->baptised)
                            <span class="text-green-600">✓ Oui</span>
                        @else
                            <span class="text-gray-400">Non</span>
                        @endif
                    </span>
                </div>
                @if($ci->baptised)
                    @include('believers._info-row', ['label' => 'Date de baptême', 'value' => $ci->baptism_date?->format('d/m/Y')])
                    @include('believers._info-row', ['label' => 'Lieu de baptême', 'value' => $ci->baptism_place])
                    @include('believers._info-row', ['label' => 'Pasteur officiant', 'value' => $ci->baptism_pastor])
                    @include('believers._info-row', ['label' => 'N° carte', 'value' => $ci->baptism_card_number])
                @endif
            @else
                <p class="text-gray-400 text-sm">Aucune information spirituelle enregistrée.</p>
            @endif
        </div>

        {{-- Éducation & Profession --}}
        <div class="bg-white shadow-sm rounded-lg p-6">
            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Éducation</h4>
            @if($believer->education)
                @include('believers._info-row', ['label' => 'Niveau d\'étude', 'value' => $believer->education->niveau_etude])
                @include('believers._info-row', ['label' => 'Diplôme', 'value' => $believer->education->diploma])
                @include('believers._info-row', ['label' => 'Qualification', 'value' => $believer->education->qualification])
            @else
                <p class="text-gray-400 text-sm">Aucune information renseignée.</p>
            @endif

            <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4 mt-6">Profession</h4>
            @if($believer->profession)
                @include('believers._info-row', ['label' => 'Profession', 'value' => $believer->profession->profession])
                @include('believers._info-row', ['label' => 'Fonction', 'value' => $believer->profession->function])
                @include('believers._info-row', ['label' => 'Entreprise', 'value' => $believer->profession->company])
                @include('believers._info-row', ['label' => 'Contact pro.', 'value' => $believer->profession->professional_contact])
            @else
                <p class="text-gray-400 text-sm">Aucune information renseignée.</p>
            @endif
        </div>

    </div>

    {{-- Responsabilités --}}
    @if($believer->responsibility)
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Responsabilités</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-500 font-medium mb-1">Anciennes</p>
                <p class="text-gray-700">{{ $believer->responsibility->old ?: '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500 font-medium mb-1">Actuelles</p>
                <p class="text-gray-700">{{ $believer->responsibility->current ?: '—' }}</p>
            </div>
            <div>
                <p class="text-gray-500 font-medium mb-1">Souhaits de service</p>
                <p class="text-gray-700">{{ $believer->responsibility->desire ?: '—' }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Appartenance --}}
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h4 class="font-semibold text-gray-700 border-b pb-2 mb-4">Appartenance</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium mb-2">Groupes</p>
                @forelse($believer->teams as $team)
                    <span class="inline-block px-2 py-1 bg-indigo-100 text-indigo-700 text-xs rounded mb-1">
                        {{ $team->name }}
                    </span>
                @empty
                    <p class="text-gray-400 text-sm">Aucun groupe</p>
                @endforelse
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium mb-2">Équipes</p>
                @forelse($believer->groups as $group)
                    <span class="inline-block px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded mb-1">
                        {{ $group->name }}
                    </span>
                @empty
                    <p class="text-gray-400 text-sm">Aucun Équipes</p>
                @endforelse
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium mb-2">Groupes de louange</p>
                @forelse($believer->worshipGroups as $group)
                    <span class="inline-block px-2 py-1 bg-purple-100 text-purple-700 text-xs rounded mb-1">
                        {{ $group->name }}
                    </span>
                @empty
                    <p class="text-gray-400 text-sm">Aucun groupe</p>
                @endforelse
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium mb-2">Cellule de quartier</p>
                @forelse($believer->cells as $cell)
                    <span class="inline-block px-2 py-1 bg-green-100 text-green-700 text-xs rounded mb-1">
                        {{ $cell->name }}
                    </span>
                @empty
                    <p class="text-gray-400 text-sm">Aucune cellule</p>
                @endforelse
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium mb-2">Langues</p>
                @forelse($believer->languages as $language)
                    <div class="flex items-center gap-2 mb-1 text-sm">
                        <span class="font-medium text-gray-700">{{ $language->name }}</span>
                        <span class="flex gap-1">
                            @if($language->pivot->lu)
                                <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 text-xs rounded">Lu</span>
                            @endif
                            @if($language->pivot->parle)
                                <span class="px-1.5 py-0.5 bg-green-100 text-green-700 text-xs rounded">Parlé</span>
                            @endif
                            @if($language->pivot->ecrit)
                                <span class="px-1.5 py-0.5 bg-purple-100 text-purple-700 text-xs rounded">Écrit</span>
                            @endif
                        </span>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">Aucune langue renseignée</p>
                @endforelse
            </div>
        </div>

        {{-- ===================== MODALS ===================== --}}
        @can('believers.edit')
            @include('believers.partials.departure')
            @include('believers.partials.sanction-modal')
            @include('believers.partials.lift-sanction-modal')
        @endcan
    </div>

    <script>
        // ── Départ / Décès ──
        function openDepartModal(believerId, believerName) {
            document.getElementById('modal-depart-name').textContent = believerName;
            document.getElementById('form-depart').action = '/believers/' + believerId + '/depart';
            document.getElementById('modal-depart').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeDepartModal() {
            document.getElementById('modal-depart').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.getElementById('form-depart').reset();
            document.getElementById('destination-field').classList.remove('hidden');
        }

        function toggleDestination(type) {
            document.getElementById('destination-field').classList.toggle('hidden', type === 'deces');
        }

        // ── Sanction ──
        function openSanctionModal(believerId, believerName) {
            document.getElementById('modal-believer-name').textContent = believerName;
            document.getElementById('form-sanction').action = '/believers/' + believerId + '/sanction';
            document.getElementById('modal-sanction').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeSanctionModal() {
            document.getElementById('modal-sanction').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.getElementById('form-sanction').reset();
        }

        // ── Lever la sanction ──
        function openLiftSanctionModal(believerId, believerName) {
            document.getElementById('lift-modal-believer-name').textContent = believerName;
            document.getElementById('form-lift-sanction').action = '/believers/' + believerId + '/lift-sanction';
            document.getElementById('lift-modal-sanction').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeLiftSanctionModal() {
            document.getElementById('lift-modal-sanction').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.getElementById('form-lift-sanction').reset();
        }

        // Fermeture avec Échap
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeSanctionModal();
                closeLiftSanctionModal();
                closeDepartModal();
            }
        });
    </script>

</div>
@endsection

