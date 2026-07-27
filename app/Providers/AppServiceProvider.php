<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use app\Models\Team;
use App\Policies\TeamPolicy;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Team::class, TeamPolicy::class);
        Carbon::setLocale('fr');
    }
}
