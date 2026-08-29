<?php

use Illuminate\Support\Facades\Route;

// -------------------------------------------------------
// Page d'accueil → redirection vers login
// -------------------------------------------------------
Route::get('/', fn() => redirect()->route('login'));

// -------------------------------------------------------
// Formulaires publics (sans authentification, accès QR code)
// -------------------------------------------------------
Route::prefix('inscription')->name('public.')->middleware('throttle:15,1')->group(function () {
    Route::get('fidele', [\App\Http\Controllers\Public\PublicRegistrationController::class, 'showBelieverForm'])->name('believer.form');
    Route::post('fidele', [\App\Http\Controllers\Public\PublicRegistrationController::class, 'storeBeliever'])->name('believer.store');
    Route::get('fidele/merci', [\App\Http\Controllers\Public\PublicRegistrationController::class, 'believerSuccess'])->name('believer.success');

    Route::get('nouvelle-personne', [\App\Http\Controllers\Public\PublicRegistrationController::class, 'showNewcomerForm'])->name('newcomer.form');
    Route::post('nouvelle-personne', [\App\Http\Controllers\Public\PublicRegistrationController::class, 'storeNewcomer'])->name('newcomer.store');
    Route::get('nouvelle-personne/merci', [\App\Http\Controllers\Public\PublicRegistrationController::class, 'newcomerSuccess'])->name('newcomer.success');
});

// -------------------------------------------------------
// Auth (Breeze)
// -------------------------------------------------------
require __DIR__.'/auth.php';

// Changement de mot de passe forcé
Route::middleware('auth')->group(function () {
    Route::get('password/change', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'show'])
        ->name('password.change');
    Route::post('password/change', [\App\Http\Controllers\Auth\PasswordChangeController::class, 'update'])
        ->name('password.update');
});

// Gestion des utilisateurs (admin uniquement)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/create', [\App\Http\Controllers\Admin\AdminUserController::class, 'create'])->name('users.create');
    Route::post('users', [\App\Http\Controllers\Admin\AdminUserController::class, 'store'])->name('users.store');
    Route::get('users/{user}/edit', [\App\Http\Controllers\Admin\AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('users/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'update'])->name('admin.users.update');
    Route::post('users/{user}/toggle', [\App\Http\Controllers\Admin\AdminUserController::class, 'toggleActive'])->name('admin.users.toggle');
    Route::post('users/{user}/reset-password', [\App\Http\Controllers\Admin\AdminUserController::class, 'resetPassword'])->name('admin.users.reset-password');
    Route::delete('users/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('admin.users.destroy');
});

// -------------------------------------------------------
// Espace protégé par rôle (Spatie)
// -------------------------------------------------------
Route::middleware(['auth', 'force.password.change'])->group(function () {

    // -----------------------------------------------------------
    // Redirection intelligente après connexion — accessible à tout rôle authentifié
    // -----------------------------------------------------------
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'redirect'])->name('dashboard');

    // ADMIN
    Route::middleware('role:admin')
        ->prefix('admin')
        ->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'admin'])->name('dashboard.admin');
            Route::resource('users', \App\Http\Controllers\Admin\AdminUserController::class);

            Route::get('activity-logs', [\App\Http\Controllers\Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    // PASTEUR
    Route::middleware('role:pasteur|admin')
        ->prefix('pasteur')
        ->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'pasteur'])->name('dashboard.pasteur');
    });

    // SECRÉTARIAT
    Route::middleware('role:secretariat|admin')
        ->prefix('secretariat')
        ->group(function () {
            Route::get('/dashboard', fn() => view('dashboards.secretariat'))->name('dashboard.secretariat');

            Route::get('believers/export/excel', [\App\Http\Controllers\BelieverExportController::class, 'exportExcel'])->name('believers.export.excel');
            Route::get('believers/export/pdf', [\App\Http\Controllers\BelieverExportController::class, 'exportPdf'])->name('believers.export.pdf');
            Route::get('believers/import', [\App\Http\Controllers\BelieverExportController::class, 'importForm'])->name('believers.import.form');
            Route::post('believers/import', [\App\Http\Controllers\BelieverExportController::class, 'import'])->name('believers.import');
            Route::get('believers/template', function () {
                return \Maatwebsite\Excel\Facades\Excel::download(
                    new \App\Exports\BelieversTemplateExport(),
                    'template-import-fideles.xlsx'
                );
            })->name('believers.template');

            Route::get('believers/export/matricules', [\App\Http\Controllers\BelieverExportController::class, 'exportMatricules'])
                    ->name('believers.export.matricules');
            Route::get('believers/photo-import', [\App\Http\Controllers\BelieverPhotoImportController::class, 'form'])->name('believers.photo-import.form');
            Route::post('believers/photo-import', [\App\Http\Controllers\BelieverPhotoImportController::class, 'import'])->name('believers.photo-import');

            Route::resource('believers', \App\Http\Controllers\BelieverController::class);
            Route::resource('newcomers', \App\Http\Controllers\NewcomerController::class);
    });

    // RESPONSABLE CULTE — atterrit désormais directement sur cultes.periodes via /dashboard,
    // cette route reste comme fallback/lien direct
    Route::middleware('role:direction_culte|admin|pasteur')
        ->prefix('culte')
        ->group(function () {
            Route::get('/dashboard', fn() => view('dashboards.culte'))->name('dashboard.culte');
        });

    // RESPONSABLE JEUNES (J-AEBECI) — atterrit désormais sur teams.show via /dashboard
    Route::middleware('role:jaebeci|admin|pasteur')
        ->prefix('jeunes')
        ->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'jeunes'])->name('dashboard.jeunes');
    });

    // RESPONSABLE FEMMES (AFEBECI) — atterrit désormais sur teams.show via /dashboard
    Route::middleware('role:afebeci|admin|pasteur')
        ->prefix('femmes')
        ->group(function () {
            Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'femmes'])->name('dashboard.femmes');
    });

    // RESPONSABLE ECODIM — module en attente (gestion notes/classement)
    Route::middleware('role:direction_ecodim|admin|pasteur')
        ->prefix('ecodim')
        ->group(function () {
            Route::get('/dashboard', fn() => view('dashboards.ecodim'))->name('dashboard.ecodim');
        });

    // -----------------------------------------------------------
    // Actions sur les fidèles (admin, pasteur, secrétariat)
    // -----------------------------------------------------------
    Route::middleware('role:admin|pasteur|secretariat')->group(function () {

        Route::get('sanctions', [\App\Http\Controllers\SanctionController::class, 'index'])->name('sanctions.index');
        Route::patch('believers/{believer}/sanction', [\App\Http\Controllers\BelieverController::class, 'sanction'])->name('believers.sanction');
        Route::patch('believers/{believer}/lift-sanction', [\App\Http\Controllers\BelieverController::class, 'liftSanction'])->name('believers.lift-sanction');
        Route::patch('believers/{believer}/depart', [\App\Http\Controllers\BelieverController::class, 'depart'])->name('believers.depart');
        Route::patch('believers/{believer}/reinstate', [\App\Http\Controllers\BelieverController::class, 'reinstate'])->name('believers.reinstate');
        Route::post('newcomers/{newcomer}/convert', [\App\Http\Controllers\NewcomerController::class, 'convert'])->name('newcomers.convert');
        Route::get('departures', [\App\Http\Controllers\DepartureController::class, 'index'])->name('departures.index');
        Route::get('believers/{believer}/fiche', [\App\Http\Controllers\BelieverController::class, 'downloadFiche'])->name('believers.fiche');
        Route::get('believers/{believer}/card', [\App\Http\Controllers\BelieverController::class, 'membershipCard'])
                ->name('believers.card');

        // Funérailles
        Route::resource('funeral', \App\Http\Controllers\FuneralRegisterController::class);
        Route::get('funeral/{funeral}/fiche', [\App\Http\Controllers\FuneralRegisterController::class, 'downloadFiche'])->name('funeral.fiche');

        // Mariage
        Route::resource('mariage', \App\Http\Controllers\MariageRegisterController::class);
        Route::get('mariage/{mariage}/fiche', [\App\Http\Controllers\MariageRegisterController::class, 'downloadFiche'])->name('mariage.fiche');

        // Présentation d'enfant
        Route::resource('dedication', \App\Http\Controllers\ChildDedicationController::class);
        Route::get('dedication/{dedication}/fiche', [\App\Http\Controllers\ChildDedicationController::class, 'downloadFiche'])->name('dedication.fiche');

        Route::get('inscriptions/qrcodes', [\App\Http\Controllers\Public\PublicRegistrationController::class, 'qrCodes'])->name('admin.qrcodes');

        // COMITÉ (admin, pasteur, secrétariat)
        Route::get('/comite/members', [\App\Http\Controllers\ComiteController::class, 'index'])->name('comite.index');
        Route::post('/comite/members', [\App\Http\Controllers\ComiteController::class, 'store'])->name('comite.store');
        Route::patch('/comite/members/{member}/deactivate', [\App\Http\Controllers\ComiteController::class, 'deactivate'])->name('comite.deactivate');
        Route::patch('/comite/members/{member}/reactivate', [\App\Http\Controllers\ComiteController::class, 'reactivate'])->name('comite.reactivate');
        Route::delete('/comite/members/{member}', [\App\Http\Controllers\ComiteController::class, 'destroy'])->name('comite.destroy');

        Route::get('/comite/meetings', [\App\Http\Controllers\ComiteMeetingController::class, 'index'])->name('comite.meetings');
        Route::post('/comite/meetings', [\App\Http\Controllers\ComiteMeetingController::class, 'store'])->name('comite.meetings.store');
        Route::delete('/comite/meetings/{meeting}', [\App\Http\Controllers\ComiteMeetingController::class, 'destroy'])->name('comite.meetings.destroy');

        // CONSEIL (admin, pasteur, secrétariat)
        Route::get('/conseil/members', [\App\Http\Controllers\ConseilController::class, 'index'])->name('conseil.index');
        Route::post('/conseil/members', [\App\Http\Controllers\ConseilController::class, 'store'])->name('conseil.store');
        Route::patch('/conseil/members/{member}/deactivate', [\App\Http\Controllers\ConseilController::class, 'deactivate'])->name('conseil.deactivate');
        Route::patch('/conseil/members/{member}/reactivate', [\App\Http\Controllers\ConseilController::class, 'reactivate'])->name('conseil.reactivate');
        Route::delete('/conseil/members/{member}', [\App\Http\Controllers\ConseilController::class, 'destroy'])->name('conseil.destroy');


        Route::get('/conseil/ag', [\App\Http\Controllers\ConseilAgController::class, 'index'])->name('conseil.ag');
        Route::post('/conseil/ag', [\App\Http\Controllers\ConseilAgController::class, 'store'])->name('conseil.ag.store');
        Route::delete('/conseil/ag/{ag}', [\App\Http\Controllers\ConseilAgController::class, 'destroy'])->name('conseil.ag.destroy');

        // EQUIPE CONSTRUCTION (admin, pasteur, secrétariat, direction_culte)
        Route::get('/construction/members', [\App\Http\Controllers\ConstructionController::class, 'index'])->name('construction.index');
        Route::post('/construction/members', [\App\Http\Controllers\ConstructionController::class, 'store'])->name('construction.store');
        Route::patch('/construction/members/{member}/deactivate', [\App\Http\Controllers\ConstructionController::class, 'deactivate'])->name('construction.deactivate');
        Route::patch('/construction/members/{member}/reactivate', [\App\Http\Controllers\ConstructionController::class, 'reactivate'])->name('construction.reactivate');
        Route::delete('/construction/members/{member}', [\App\Http\Controllers\ConstructionController::class, 'destroy'])->name('construction.destroy');

        Route::get('/construction/projects', [\App\Http\Controllers\ConstructionProjectController::class, 'index'])->name('construction.projects');
        Route::post('/construction/projects', [\App\Http\Controllers\ConstructionProjectController::class, 'store'])->name('construction.projects.store');
        Route::put('/construction/projects/{project}', [\App\Http\Controllers\ConstructionProjectController::class, 'update'])->name('construction.projects.update');
        Route::delete('/construction/projects/{project}', [\App\Http\Controllers\ConstructionProjectController::class, 'destroy'])->name('construction.projects.destroy');

        // EQUIPE FONCIERE (admin, pasteur, secrétariat, direction_culte)
        Route::get('/fonciere', [\App\Http\Controllers\EquipeFoncieteController::class, 'index'])->name('fonciere.index');
        Route::post('/fonciere', [\App\Http\Controllers\EquipeFoncieteController::class, 'store'])->name('fonciere.store');
        Route::patch('/fonciere/{member}/deactivate', [\App\Http\Controllers\EquipeFoncieteController::class, 'deactivate'])->name('fonciere.deactivate');
        Route::patch('/fonciere/{member}/reactivate', [\App\Http\Controllers\EquipeFoncieteController::class, 'reactivate'])->name('fonciere.reactivate');
        Route::delete('/fonciere/{member}', [\App\Http\Controllers\EquipeFoncieteController::class, 'destroy'])->name('fonciere.destroy');

        Route::get('/dossiers', [\App\Http\Controllers\DossierFoncierController::class, 'index'])->name('dossiers');
        Route::post('/dossiers', [\App\Http\Controllers\DossierFoncierController::class, 'store'])->name('dossiers.store');
        Route::put('/dossiers/{dossier}', [\App\Http\Controllers\DossierFoncierController::class, 'update'])->name('dossiers.update');
        Route::delete('/dossiers/{dossier}', [\App\Http\Controllers\DossierFoncierController::class, 'destroy'])->name('dossiers.destroy');

    });

    // -----------------------------------------------------------
    // Gestion des cultes (admin, pasteur, secrétariat, direction_culte)
    // -----------------------------------------------------------
    Route::middleware('role:admin|pasteur|secretariat|direction_culte')->group(function () {
        Route::get('cultes/periodes', [\App\Http\Controllers\ServiceController::class, 'periodes'])->name('cultes.periodes');
        Route::get('cultes/periodes/create', [\App\Http\Controllers\ServiceController::class, 'createPeriode'])->name('cultes.periodes.create');
        Route::post('cultes/periodes', [\App\Http\Controllers\ServiceController::class, 'storePeriode'])->name('cultes.periodes.store');
        Route::get('cultes/periodes/{periode}/edit', [\App\Http\Controllers\ServiceController::class, 'editPeriode'])->name('cultes.periodes.edit');
        Route::put('cultes/periodes/{periode}', [\App\Http\Controllers\ServiceController::class, 'updatePeriode'])->name('cultes.periodes.update');
        Route::post('cultes/periodes/{periode}/activate', [\App\Http\Controllers\ServiceController::class, 'activatePeriode'])->name('cultes.periode.activate');
        Route::post('cultes/periodes/{periode}/archive', [\App\Http\Controllers\ServiceController::class, 'archivePeriode'])->name('cultes.periode.archive');

        // Route::get('cultes/periodes/{periode}/services', [\App\Http\Controllers\ServiceController::class, 'services'])->name('cultes.services');
        Route::get('cultes/periodes/{periode}/services/create', [\App\Http\Controllers\ServiceController::class, 'createService'])->name('cultes.services.create');
        Route::post('cultes/periodes/{periode}/services', [\App\Http\Controllers\ServiceController::class, 'storeService'])->name('cultes.services.store');
        Route::delete('cultes/services/{service}', [\App\Http\Controllers\ServiceController::class, 'destroyService'])->name('cultes.services.destroy');

        Route::get('cultes/services/{service}/assignations', [\App\Http\Controllers\ServiceController::class, 'assignations'])->name('cultes.assignations');
        Route::post('cultes/services/{service}/assignations', [\App\Http\Controllers\ServiceController::class, 'storeAssignation'])->name('cultes.assignations.store');
        Route::delete('cultes/assignations/{assignment}', [\App\Http\Controllers\ServiceController::class, 'destroyAssignation'])->name('cultes.assignations.destroy');
        Route::patch('cultes/assignations/{assignment}/promote', [\App\Http\Controllers\ServiceController::class, 'promoteAssignation'])
                    ->name('cultes.assignations.promote');

        Route::get('cultes/acteurs', [\App\Http\Controllers\ServiceController::class, 'acteurs'])->name('cultes.acteurs');
        Route::post('cultes/acteurs', [\App\Http\Controllers\ServiceController::class, 'storeActeur'])->name('cultes.acteurs.store');
        Route::delete('cultes/acteurs/{acteur}', [\App\Http\Controllers\ServiceController::class, 'destroyActeur'])->name('cultes.acteurs.destroy');

        // Route::get('cultes/periodes/{periode}/programme', [\App\Http\Controllers\ServiceController::class, 'programmePdf'])->name('cultes.programme.pdf');

        // Groupes
        Route::resource('groups', \App\Http\Controllers\GroupController::class);
        Route::post('groups/{group}/believers', [\App\Http\Controllers\GroupController::class, 'assignBeliever'])->name('groups.believers.store');
        Route::delete('groups/{group}/believers/{believer}', [\App\Http\Controllers\GroupController::class, 'removeBeliever'])->name('groups.believers.destroy');
        Route::get('groups/{group}/members/pdf', [\App\Http\Controllers\GroupController::class, 'membersPdf'])->name('groups.members-pdf');

        Route::post('groups/{group}/rapports', [\App\Http\Controllers\RapportController::class, 'store'])
            ->name('groups.rapports.store');
        Route::delete('groups/{group}/rapports/{rapport}', [\App\Http\Controllers\RapportController::class, 'destroy'])
            ->name('groups.rapports.destroy');

        // Groupes de louange
        Route::resource('worship-groups', \App\Http\Controllers\WorshipGroupController::class)
            ->parameters(['worship-groups' => 'worship_group']);
        Route::post('worship-groups/{worship_group}/believers', [\App\Http\Controllers\WorshipGroupController::class, 'assignBeliever'])->name('worship-groups.believers.store');
        Route::delete('worship-groups/{worship_group}/believers/{believer}', [\App\Http\Controllers\WorshipGroupController::class, 'removeBeliever'])->name('worship-groups.believers.destroy');
        Route::get('worship-groups/{worship_group}/members/pdf', [\App\Http\Controllers\WorshipGroupController::class, 'membersPdf'])->name('worship-groups.members-pdf');

        Route::post('worship-groups/{worship_group}/rapports', [\App\Http\Controllers\WorshipGroupRapportController::class, 'store'])
            ->name('worship-groups.rapports.store');
        Route::delete('worship-groups/{worship_group}/rapports/{rapport}', [\App\Http\Controllers\WorshipGroupRapportController::class, 'destroy'])
            ->name('worship-groups.rapports.destroy');
    });

    // Consultation du programme — lecture seule, accessible aussi aux acteurs de culte
    Route::middleware('role:admin|pasteur|secretariat|direction_culte|acteur_culte')->group(function () {
        Route::get('cultes/periodes/{periode}/services', [\App\Http\Controllers\ServiceController::class, 'services'])->name('cultes.services');
        Route::get('cultes/periodes/{periode}/programme', [\App\Http\Controllers\ServiceController::class, 'programmePdf'])->name('cultes.programme.pdf');
    });

    // Fallback si aucune période n'existe
    Route::middleware('role:acteur_culte|admin|pasteur')->group(function () {
        Route::get('acteur-culte/aucune-periode', fn() => view('dashboards.acteur-culte-empty'))
            ->name('dashboard.acteur-culte-empty');
    });

    // -----------------------------------------------------------
    // Gestion des équipes (admin, pasteur, secrétariat, direction_culte
    // + jaebeci/afebeci pour "toutes opérations" sur leur équipe)
    // -----------------------------------------------------------
    Route::middleware('role:admin|pasteur|secretariat|direction_culte|jaebeci|afebeci')->group(function () {
        Route::resource('teams', \App\Http\Controllers\TeamController::class);

        Route::post('teams/{team}/believers', [\App\Http\Controllers\TeamController::class, 'assignBeliever'])->name('teams.believers.store');
        Route::delete('teams/{team}/believers/{believer}', [\App\Http\Controllers\TeamController::class, 'removeBeliever'])->name('teams.believers.destroy');
        Route::get('teams/{team}/members/pdf', [\App\Http\Controllers\TeamController::class, 'membersPdf'])->name('teams.members-pdf');

        Route::post('teams/{team}/activities', [\App\Http\Controllers\TeamActivityController::class, 'store'])->name('teams.activities.store');
        Route::post('teams/{team}/activities/{activity}/finish', [\App\Http\Controllers\TeamActivityController::class, 'finish'])->name('teams.activities.finish');
        Route::post('teams/{team}/activities/{activity}/postpone', [\App\Http\Controllers\TeamActivityController::class, 'postpone'])->name('teams.activities.postpone');
        Route::get('teams/{team}/activities/program/pdf', [\App\Http\Controllers\TeamActivityController::class, 'programPdf'])->name('teams.activities.program-pdf');
        Route::get('teams/{team}/activities/report/pdf', [\App\Http\Controllers\TeamActivityController::class, 'reportPdf'])->name('teams.activities.report-pdf');
    });

});