@php
    use App\Models\Sucursal;

    $claveActiva = session('sucursal_activa');

    $sucursalActiva = null;

    if ($claveActiva) {
        $sucursalActiva = Sucursal::on('mysql')
            ->where('clave', $claveActiva)
            ->first();
    }

    $nombreBase = $sucursalActiva->nombre ?? 'Sin base seleccionada';
    $dbName = $sucursalActiva->database_name ?? 'N/A';
@endphp

<style>
    .db-active-badge {
        position: fixed;
        right: 18px;
        bottom: 188px;
        z-index: 9999;
        background: rgba(33, 37, 41, 0.92);
        color: #ffffff;
        border-left: 4px solid #17a2b8;
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 11px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.18);
        max-width: 280px;
        backdrop-filter: blur(4px);
    }

    .db-active-badge .db-title {
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #17d4ee;
        display: block;
        line-height: 1.1;
    }

    .db-active-badge .db-name {
        font-weight: 700;
        display: block;
        margin-top: 2px;
        line-height: 1.2;
    }

    .db-active-badge .db-detail {
        display: block;
        color: #adb5bd;
        margin-top: 2px;
        font-size: 10px;
        line-height: 1.1;
    }
</style>

<div class="db-active-badge">
    <span class="db-title">
        <i class="fas fa-database mr-10"></i> Base activa
    </span>

    <span class="db-name">
        {{ $nombreBase }}
    </span>

    <span class="db-detail">
        {{ $dbName }}
    </span>
</div>