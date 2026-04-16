<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        if ($this->app->isProduction()) {
            \URL::forceScheme('https');
        }

        $this->configureDefaults();

        Gate::define('access-staff-area', fn (User $user) => $user->role === 'osa'
            ? Response::allow()
            : Response::denyAsNotFound());

        Gate::define('access-guard-area', fn (User $user) => $user->role === 'guard'
            ? Response::allow()
            : Response::denyAsNotFound());

        Gate::define('access-student-area', fn (User $user) => $user->role === 'student'
            ? Response::allow()
            : Response::denyAsNotFound());

        Gate::define('is-osa', fn (User $user) => $user->role === 'osa');
    }

    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
