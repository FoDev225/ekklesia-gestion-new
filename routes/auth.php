<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    // Connexion par username
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

});

Route::middleware('auth')->group(function () {

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    // Renommé pour éviter le conflit avec password.update de PasswordChangeController
    Route::put('password', [PasswordController::class, 'update'])
        ->name('password.update.profile');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

});