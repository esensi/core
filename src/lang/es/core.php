<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Error messages
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the Esensi\Core components package.
    |
    */

    'errors' => [
        'store' => 'Objeto no podía ser almacenado.',
        'show' => 'El objeto no se pudo encontrar.',
        'update' => 'Objeto no pudo actualizarse.',
        'restore' => 'El objeto no podía ser restaurada.',
        'destroy' => 'Objeto no pudo ser eliminado.',
        'trashing' => 'El objeto no se puede enviar a la basura.',
        'rate_limit_exceeded' => 'No tan rápido Handsome One! Su límite de velocidad se ha superado por lo que ha sido puesto en un tiempo de espera :timeout minutos.',
    ],


    /*
    |--------------------------------------------------------------------------
    | Status message lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain status message lines used by the
    | Esensi\Core components package models.
    |
    */

    'messages' => [
        'never_expires' => 'Nunca Caduca',
        'never_updated' => 'Nunca Actualizado',
        'never_created' => 'Nunca Creado',
        'never_deleted' => 'Nunca Suprimido',
        'rate_limit_exceeded' => 'Excede el Límite de Velocidad'
    ],
];