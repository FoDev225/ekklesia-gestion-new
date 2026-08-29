<?php

namespace Database\Seeders;

use App\Models\Believer;
use App\Models\Team;
use App\Models\Group;
use App\Models\Cell;
use App\Models\Language;
use App\Models\WorshipGroup;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class BelieverSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        // -------------------------------------------------------
        // Données de référence (créées si absentes)
        // -------------------------------------------------------
        $teams = Team::all();
        if ($teams->isEmpty()) {
            $teams = collect([
                ['name' => 'J-AEBECI', 'slug' => 'j-aebeci', 'description' => 'Jeunesse de l\'église'],
                ['name' => 'AFEBECI',     'slug' => 'afebeci', 'description' => 'Femmes de l\'église'],
                ['name' => 'COMITE', 'slug' => 'comite', 'description' => 'Comité de l\'église'],
                ['name' => 'GROUPES DES HOMMES',       'slug' => 'groupes-des-hommes', 'description' => 'Groupe des hommes de l\'église'],
            ])->map(fn($t) => Team::create($t));
        }

        $groups = Group::all();
        if ($groups->isEmpty()) {
            $groups = collect([
                ['name' => 'EQUIPE DE PRIERE', 'slug' => 'equipe-priere', 'description' => 'Equipe de prière de l\'église'],
                ['name' => 'PROTOCOLE', 'slug' => 'protocole',  'description' => 'Protocoles de l\'église'],
                ['name' => 'EQUIPE D\'EVANGELISATION', 'slug' => 'equipe-evangelisation' ,'description' => 'Equipe d\'évangélisation de l\'église'],
                ['name' => 'ECODIM', 'slug' => 'ecodim', 'description' => 'École du dimanche'],
                ['name' => 'ENTRETIEN',       'slug' => 'entretien', 'description' => 'Équipe d\'Entretien'],
                ['name' => 'CONSTRUCTION',               'slug' => 'construction', 'description' => 'Équipe de construction'],
                ['name' => 'PATRIMOINE',             'slug' => 'patrimoine', 'description' => 'Gestion du patrimoine de l\'église'],
                ['name' => 'CELLULE MATRIMONIALE',             'slug' => 'matrimoniale', 'description' => 'Cellule matrimoniale'],
                ['name' => 'CERCOM',             'slug' => 'cercom', 'description' => 'Cellule de communication'],
                ['name' => 'ACCUEIL ET INSTALLATION',             'slug' => 'accueil-installation', 'description' => 'Cellule d\'accueil et d\'installation'],
            ])->map(fn($g) => Group::create($g));
        }

        $worship = WorshipGroup::all();
        if ($worship->isEmpty()) {
            $worship = collect([
                ['name' => 'Groupe Musical',  'description' => 'Groupe de louange principal'],
                ['name' => 'DEXY',   'description' => 'Groupe de louange sénoufo balafon'],
                ['name' => 'Chorale Eden',  'description' => 'Chorale sénoufo balafon'],
                ['name' => 'Chorale Horeb',  'description' => 'Chorale sénoufo'],
                ['name' => 'Chorale Sainte Cohorte',  'description' => 'Chorale française'],
            ])->map(fn($g) => WorshipGroup::create($g));
        }

        $cells = Cell::all();
        if ($cells->isEmpty()) {
            $quartiers = ['Yopougon', 'Cocody', 'Abobo', 'Marcory', 'Treichville', 'Koumassi'];
            $cells = collect($quartiers)->map(fn($q) => Cell::create([
                'name'     => "Cellule {$q}",
                'quartier' => $q,
            ]));
        }

        $languages = Language::all();
        if ($languages->isEmpty()) {
            $languages = collect([
                ['code' => 'fr', 'name' => 'Français'],
                ['code' => 'di', 'name' => 'Dioula'],
                ['code' => 'se', 'name' => 'Sénoufo'],
                ['code' => 'ba', 'name' => 'Baoulé'],
                ['code' => 'an', 'name' => 'Anglais'],
            ])->map(fn($l) => Language::create($l));
        }

        $communes  = ['Yopougon', 'Cocody', 'Abobo', 'Marcory', 'Treichville', 'Koumassi', 'Adjamé', 'Plateau'];
        $eglises   = ['Église méthodiste', 'Église catholique', 'Église baptiste', 'Aucune', 'Église pentecôtiste'];
        $niveaux   = ['Primaire', 'Secondaire', 'Baccalauréat', 'Licence', 'Master', 'Doctorat', 'Aucun'];
        $professionsList = ['Enseignant', 'Commerçant', 'Infirmier', 'Chauffeur', 'Étudiant', 'Couturier', 'Mécanicien', 'Fonctionnaire', 'Électricien', 'Coiffeuse', 'Sans emploi'];

        // -------------------------------------------------------
        // Génération des 150 fidèles
        // -------------------------------------------------------
        for ($i = 0; $i < 150; $i++) {

            $gender = $faker->randomElement(['M', 'F']);
            $firstname = $gender === 'M' ? $faker->firstNameMale() : $faker->firstNameFemale();

            // Répartition réaliste des âges (pyramide d'église)
            $ageGroup = $faker->randomElement([
                'nourrisson', 'nourrisson',
                'pre_scolaire',
                'ecodim', 'ecodim', 'ecodim',
                'jeunes', 'jeunes', 'jeunes', 'jeunes', 'jeunes',
                'adultes', 'adultes', 'adultes', 'adultes',
            ]);

            $birthDate = match($ageGroup) {
                'nourrisson'   => $faker->dateTimeBetween('-2 years', 'now'),
                'pre_scolaire' => $faker->dateTimeBetween('-4 years', '-3 years'),
                'ecodim'       => $faker->dateTimeBetween('-18 years', '-5 years'),
                'jeunes'       => $faker->dateTimeBetween('-40 years', '-19 years'),
                'adultes'      => $faker->dateTimeBetween('-75 years', '-41 years'),
            };

            $age = Carbon::parse($birthDate)->age;

            // Situation matrimoniale cohérente avec l'âge
            $maritalStatus = match(true) {
                $age < 18 => null,
                $age < 25 => $faker->randomElement(['Célibataire', 'Célibataire', 'Marié(e)']),
                $age < 50 => $faker->randomElement(['Célibataire', 'Marié(e)', 'Marié(e)', 'Marié(e)', 'Divorcé']),
                default   => $faker->randomElement(['Marié(e)', 'Marié(e)', 'Veuf(ve)', 'Divorcé']),
            };

            $baptised = $age >= 12 ? $faker->boolean(70) : false;
            $statusOptions = ['actif', 'actif', 'actif', 'actif', 'inactif'];
            $status = $faker->randomElement($statusOptions);

            // -------------------------------------------------------
            // Création du fidèle
            // -------------------------------------------------------
            $believer = Believer::create([
                'lastname'           => $faker->lastName(),
                'firstname'          => $firstname,
                'cni_number'         => $age >= 18 ? strtoupper($faker->bothify('CI##########')) : null,
                'birth_date'         => $birthDate,
                'birth_place'        => $faker->city(),
                'nationality'        => $faker->randomElement(['Ivoirienne', 'Ivoirienne', 'Ivoirienne', 'Burkinabè', 'Malienne']),
                'gender'             => $gender,
                'marital_status'     => $maritalStatus,
                'number_of_children' => $age >= 25 ? $faker->numberBetween(0, 5) : 0,
                'is_active'          => $status === 'actif',
                'status'             => $status,
                'created_at'         => $faker->dateTimeBetween('-3 years', 'now'),
            ]);

            // -------------------------------------------------------
            // Adresse
            // -------------------------------------------------------
            $commune = $faker->randomElement($communes);
            $believer->address()->create([
                'commune'       => $commune,
                'quartier'      => $faker->randomElement($communes),
                'sous_quartier' => $faker->streetName(),
                'phone'         => '07' . $faker->numerify('########'),
                'whatsapp'      => $faker->boolean(80) ? '07' . $faker->numerify('########') : null,
                'email'         => $faker->boolean(40) ? $faker->safeEmail() : null,
            ]);

            // -------------------------------------------------------
            // Infos église
            // -------------------------------------------------------
            $believer->churchInformation()->create([
                'connaissance_eglise' => $faker->randomElement(['Par un ami', 'Par la famille', 'Évangélisation', 'Né dans l\'église', 'Réseaux sociaux']),
                'original_church'     => $faker->randomElement($eglises),
                'arrival_year'        => $faker->numberBetween(max(1990, date('Y') - $age), (int) date('Y')),
                'conversion_date'     => $faker->boolean(85) ? $faker->dateTimeBetween('-20 years', 'now') : null,
                'conversion_place'    => $faker->city(),
                'baptised'            => $baptised,
                'baptism_date'        => $baptised ? $faker->dateTimeBetween($birthDate, 'now') : null,
                'baptism_place'       => $baptised ? 'Église locale' : null,
                'baptism_pastor'      => $baptised ? $faker->name('male') : null,
                'baptism_card_number' => $baptised ? strtoupper($faker->bothify('BAP-####')) : null,
            ]);

            // -------------------------------------------------------
            // Éducation (à partir de 5 ans)
            // -------------------------------------------------------
            if ($age >= 5) {
                $believer->education()->create([
                    'niveau_etude'  => $faker->randomElement($niveaux),
                    'diploma'       => $faker->boolean(50) ? $faker->randomElement(['BEPC', 'BAC', 'Licence', 'Master']) : null,
                    'qualification' => $faker->boolean(30) ? $faker->jobTitle() : null,
                ]);
            }

            // -------------------------------------------------------
            // Profession (à partir de 18 ans)
            // -------------------------------------------------------
            if ($age >= 18) {
                $believer->profession()->create([
                    'profession'           => $faker->randomElement($professionsList),
                    'function'             => $faker->boolean(40) ? $faker->jobTitle() : null,
                    'company'              => $faker->boolean(50) ? $faker->company() : null,
                    'professional_contact' => $faker->boolean(30) ? '07' . $faker->numerify('########') : null,
                ]);
            }

            // -------------------------------------------------------
            // Responsabilités (pour certains adultes/jeunes)
            // -------------------------------------------------------
            if ($age >= 19 && $faker->boolean(25)) {
                $believer->responsibility()->create([
                    'old'     => $faker->boolean(40) ? $faker->randomElement(['Trésorier', 'Animateur', 'Chorale']) : null,
                    'current' => $faker->randomElement(['Responsable louange', 'Animateur jeunesse', 'Secrétaire', 'Diacre', null]),
                    'desire'  => $faker->boolean(30) ? $faker->randomElement(['Évangélisation', 'Prédication', 'Musique']) : null,
                ]);
            } else {
                $believer->responsibility()->create([]);
            }

            // -------------------------------------------------------
            // Langues (1 à 3)
            // -------------------------------------------------------
            $believer->languages()->attach(
                $languages->random($faker->numberBetween(1, 3))->pluck('id')
            );

            // -------------------------------------------------------
            // Équipe (selon tranche d'âge, cohérent)
            // -------------------------------------------------------
            $matchingTeam = match($ageGroup) {
                'ecodim'  => $teams->firstWhere('slug', 'ecodim'),
                'jeunes'  => $teams->firstWhere('slug', 'j-aebeci'),
                'adultes' => $maritalStatus === 'marie' && $gender === 'F'
                                ? $teams->firstWhere('slug', 'afebeci')
                                : $teams->random(),
                default   => null,
            };

            if ($matchingTeam && $faker->boolean(70)) {
                $believer->teams()->attach($matchingTeam->id, ['joined_at' => $faker->dateTimeBetween('-2 years', 'now')]);
            }
            // Une 2e équipe parfois
            if ($age >= 19 && $faker->boolean(20)) {
                $believer->teams()->syncWithoutDetaching([$teams->random()->id => ['joined_at' => now()]]);
            }

            // -------------------------------------------------------
            // Groupe de louange (50% de chance si jeune/adulte)
            // -------------------------------------------------------
            if ($age >= 12 && $faker->boolean(40)) {
                $believer->groups()->attach($groups->random()->id, ['joined_at' => $faker->dateTimeBetween('-2 years', 'now')]);
            }

            // -------------------------------------------------------
            // Cellule de quartier (80% de chance)
            // -------------------------------------------------------
            if ($faker->boolean(80)) {
                $matchingCell = $cells->firstWhere('quartier', $commune) ?? $cells->random();
                $believer->cells()->attach($matchingCell->id, ['joined_at' => $faker->dateTimeBetween('-2 years', 'now')]);
            }
        }

        $this->command->info('✅ 150 fidèles générés avec succès (adresses, infos église, éducation, profession, équipes, groupes, cellules).');
    }
}