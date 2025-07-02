<?php

namespace App\Providers;
use Illuminate\Support\Facades\Gate;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
  public function boot()
{
    $this->registerPolicies();

    Gate::define('menu-compania', function ($user) {
        return in_array($user->rol, ['Admin', 'Super-Admin']);
    });

    Gate::define('menu-usuarios', function ($user) {
        return in_array($user->rol, ['Admin', 'Super-Admin']);
    });

    Gate::define('menu-proveedores', function ($user) {
        return in_array($user->rol, ['Admin', 'Super-Admin']);
    });
    Gate::define('menu-categorias', function ($user) {
        return in_array($user->rol, ['Admin', 'Super-Admin']);
    });
    Gate::define('menu-productos', function ($user) {
        return in_array($user->rol, ['Admin', 'Super-Admin', 'User']);
    });

    Gate::define('menu-ventas', function ($user) {
        return in_array($user->rol, ['Admin', 'Super-Admin', 'User']);
    });

    Gate::define('menu-facturacion', function ($user) {
        return $user->rol === 'Super-Admin';
    });

    Gate::define('menu-admin', function ($user) {
        return in_array($user->rol, ['Admin', 'Super-Admin']);
    });

    Gate::define('menu-clientes', function ($user) {
        return in_array($user->rol, ['Admin', 'Super-Admin', 'User']);
    });
}
}
