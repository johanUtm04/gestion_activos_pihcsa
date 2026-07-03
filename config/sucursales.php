<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sucursal por defecto
    |--------------------------------------------------------------------------
    */
    'default' => env('SUCURSAL_DEFAULT', 'morelia'),

    /*
    |--------------------------------------------------------------------------
    | Sucursales disponibles
    |--------------------------------------------------------------------------
    | La llave debe coincidir con el nombre de la conexión en config/database.php.
    */
    'disponibles' => [
        'morelia' => 'Morelia',
    ],
    #When I add a sucursal, I gonna had too in database.php

];