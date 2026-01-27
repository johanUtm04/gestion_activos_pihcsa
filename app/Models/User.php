<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users'; // Explicitamente definido

    /**
     * CRÍTICO: Incluir todos los campos nuevos para asignación masiva.
     */
    protected $fillable = [
        'name', 
        'email', 
        'password',
        'rol',
        'departamento',
        'contacto',
        'estatus',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        // 'email_verified_at' => 'datetime', // Este campo fue eliminado en la migración
        'password' => 'hashed',
    ];

    // === RELACIONES CORREGIDAS ===
    
    /**
     * Relación 1:N (Un usuario registra muchos logs)
     */
    public function historialLogs()
    {
        return $this->hasMany(Historial_Log::class, 'usuario_accion_id');
    }

    /**
     * CRÍTICO: Relación 1:N (Un usuario es responsable de muchos equipos)
     */
    public function equiposResponsables() // Renombrado y apuntando a Equipo::class
    {
        // Un usuario tiene muchos equipos donde la FK 'usuario_id' en la tabla 'equipos' lo referencia.
        return $this->hasMany(Equipo::class, 'usuario_id');
    }

}