# Seeders para `databaseWithVehicles.sql`

Estos seeders están pensados para Laravel y respetan el orden de llaves foráneas del esquema:

1. Tablas base: `users`, `ubicaciones`, `marcas`, `tipo_activos`, `cat_tipo_vehiculos`
2. Tablas dependientes: `equipos`, `vehiculos`
3. Detalles: `procesadores`, `rams`, `discos_duros`, `monitores`, `perifericos`, `vehiculo_documentacion`, `historiales_log`

## Uso

Copia los archivos `.php` dentro de:

```bash
database/seeders
```

Luego ejecuta:

```bash
php artisan db:seed
```

O, si quieres reconstruir la base completa:

```bash
php artisan migrate:fresh --seed
```

## Acceso de prueba

Usuario admin:

```text
email: admin@pihcsa.local
password: password
```

## Nota

No incluí seeders para `migrations` ni `personal_access_tokens`, porque Laravel/Sanctum normalmente las administran automáticamente.
