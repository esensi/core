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
        'create'              => 'Objeto no podía ser almacenado.',
        'read'                => 'El objeto no se pudo encontrar.',
        'update'              => 'Objeto no pudo actualizarse.',
        'delete'              => 'Objeto no pudo ser eliminado.',
        'find_by'             => 'El objeto no se pudo encontrar por :attribute.',
        'find_in'             => 'Los objetos no se podían encontrar por :attribute.',
        'retrieve'            => 'El objeto no se pudo encontrar en la basura.',
        'trash'               => 'Objeto no podía ser colocado en la papelera.',
        'restore'             => 'El objeto no podía ser restaurada.',
        'purge'               => 'Los objetos no pueden ser purgados de la basura.',
        'recover'             => 'Los objetos no pueden ser recuperados de la basura.',
        'truncate'            => 'Los objetos no pueden ser truncar.',
        'not_related'         => 'El objeto no tiene una :relationship relacionado.',
        'rate_limit_exceeded' => 'No tan rápido Handsome One! Su límite de velocidad se ha superado por lo que ha sido puesto en un tiempo de espera :timeout minutos.',
    ],


    /*
    |--------------------------------------------------------------------------
    | Message lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain message lines used by the
    | Esensi\Core components package models.
    |
    */

    'messages' => [
        'created'             => 'Objeto ha sido creado.',
        'updated'             => 'Objetos ha sido actualizado.',
        'deleted'             => 'Se ha eliminado objeto.',
        'trashed'             => 'Objetos ha sido colocado en la papelera.',
        'restored'            => 'Objetos ha sido restaurado.',
        'purged'              => 'Objetos han sido purgados de basura.',
        'recovered'           => 'Objetos han sido recuperados de la basura.',
        'truncated'           => 'Objetos has sido truncar.',
        'no_results'          => 'No hay ningún resultado para los objetos.',
        'rate_limit_exceeded' => 'Excede el Límite de Velocidad'
    ],


    /*
    |--------------------------------------------------------------------------
    | Label lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain label lines used by the
    | Esensi\Core components package view.
    |
    */

    'labels' => [
        'never_expires'          => 'Nunca Caduca',
        'never_updated'          => 'Nunca Actualizado',
        'never_created'          => 'Nunca Creado',
        'never_deleted'          => 'Nunca Suprimido',
        'never_authenticated'    => 'Nunca Conectado',
        'never_activated'        => 'Nunca Activado',
        'never_password_updated' => 'Nunca Ajuste',
    ],

];
