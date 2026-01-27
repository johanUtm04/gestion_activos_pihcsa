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

    public function boot(): void
    {

    $this->registerPolicies();


    // El Admin tiene su propio permiso
    Gate::define('access-admin', function ($user) {
        return strtolower($user->rol) === 'admin'; 
    });

    // Sistemas puede entrar, ¡pero el Admin TAMBIÉN!
    Gate::define('access-sistemas', function ($user) {
        // Si es admin O es sistemas, devuélveme true
        return in_array(strtolower($user->rol), ['admin', 'sistemas']);
    });

    // Permiso Para Usuario INVITADO
    Gate::define('access-invitado', function ($user) {
        return in_array(strtolower($user->rol), ['admin', 'invitado']);
    });


    // Ver equipos (todos)
    Gate::define('ver-equipo', function ($user) {
        return true;
    });

    // Crear equipo (admin + sistemas)
    Gate::define('crear-equipo', function ($user) {
        return in_array( strtolower($user->rol), ['admin', '']);
    });

    // Editar equipo (admin + sistemas)
    Gate::define('editar-equipo', function ($user) {
        return in_array(strtolower($user->rol), ['admin', 'sistemas']);
    });

    // Eliminar equipo (solo admin)
    Gate::define('eliminar-equipo', function ($user) {
       return in_array(strtolower($user->rol), ['admin', '']);
    });

    // Agregar mantenimiento a un equipo (solo admin)
    Gate::define('mantenimiento-equipo', function ($user) {
        return in_array(strtolower($user->rol), ['admin', 'sistemas']);
    });
    }
}
