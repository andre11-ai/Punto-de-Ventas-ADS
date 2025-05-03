<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

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
    public function boot()
    {
        // Regla que acepta solo letras (unicode) y espacios
        Validator::extend('alpha_spaces', function ($attribute, $value) {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        // Mensaje personalizado (opcional, lo veremos en el siguiente paso)
        Validator::replacer('alpha_spaces', function ($message, $attribute) {
            return str_replace(':attribute', $attribute, 'El campo :attribute solo puede contener letras y espacios.');
        });
    }
}
