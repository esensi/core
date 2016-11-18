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
        'create'              => 'El objeto no pudo ser almacenado.',
        'read'                => 'No se pudo encontrar el objeto.',
        'update'              => 'No se pudo actualizar el objeto.',
        'delete'              => 'No se pudo eliminar el objeto.',
        'find_by'             => 'El objeto no se pudo encontrar por :attribute.',
        'find_in'             => 'Los objetos no se pudieron encontrar por :attribute.',
        'retrieve'            => 'El objeto no se pudo encontrar en la papelera.',
        'trash'               => 'El objeto no pudo ser trasladado a la papelera.',
        'restore'             => 'El objeto no pudo ser restaurado.',
        'purge'               => 'No se pudieron eliminar los obejtos de la papelera.',
        'recover'             => 'No se pudieron recuperar los objetos de la papelera.',
        'truncate'            => 'Los objetos no pueden ser eliminados.',
        'not_related'         => 'El objeto no tiene una :relationship relación.',
        'rate_limit_exceeded' => '¡No tan rápido Guapo! Has excedido tu límite de velocidad por lo que has sido puesto en un tiempo de espera :timeout minutos.',
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
        'created'             => 'El objeto ha sido creado.',
        'updated'             => 'El objeto ha sido actualizado.',
        'deleted'             => 'El objeto ha sido eliminado.',
        'trashed'             => 'Se ha trasladado el objeto a la papelera.',
        'restored'            => 'Se ha restaurado el objeto.',
        'purged'              => 'Se han eliminado los objetos de la papelera.',
        'recovered'           => 'Se han recuperado los objetos de la papelera.',
        'truncated'           => 'Se han eliminado los objetos.',
        'no_results'          => 'La búsqueda no arrojó resultados para los objetos.',
        'rate_limit_exceeded' => 'Se ha excedido el límite de velocidad',

        'bulk' => [
            'deleted'   => 'Se han eliminado los objetos seleccionados.',
            'restored'  => 'Los objetos seleccionados han sido restaurados.',
            'trashed'   => 'Los objetos seleccionados se han enviado a la papelera.',
        ],
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
        'never_deleted'          => 'Nunca Eliminado',
        'never_authenticated'    => 'Nunca ha Iniciado Sesión',
        'never_activated'        => 'Nunca Activado',
        'never_password_updated' => 'Nunca Establecido',
        'order_by'               => 'Ordenar Por',
        'sort_results'           => 'Ordenar Resultados',
        'max_results'            => 'Max Resultados',
        'cancel'                 => 'Cancelar',
        'dashboard'              => 'Dashboard',
        'showing'                => 'Mostrando',
        'to'                     => 'a',
        'of'                     => 'de',
        'updated'                => 'Actualizado',
        'trash_can'              => 'Papelera',
    ],

    'buttons' => [
        'cancel'          => 'Cancelar',
        'delete'          => 'Eliminar',
        'delete_all'      => 'Eliminar Todos',
        'filter'          => 'Filtrar',
        'new'             => 'Nuevo',
        'edit'            => 'Editar',
        'recover'         => 'Recuperar',
        'restore'         => 'Restaurar',
        'save'            => 'Guardar',
        'trash'           => 'Papelera',
        'view'            => 'Ver',
        'toggle_dropdown' => 'Mostrar/Ocultar Dropdown',
        'toggle_menu'     => 'Mostrar/Ocultar Menú',
        'empty_trash'     => 'Vaciar Papelera',
        'search'          => 'Buscar',
        'close'           => 'Cerrar',
        'log_out'         => 'Cerrar Sesión',
        'public_site'     => 'Sitio Público',
    ],

    'drawer' => [
        'keyword_search'    => 'Búsqueda por Palabra Clave',
    ],

    'table-headings' => [
        'actions'       => 'Acciones',
        'id'            => 'ID',
        'name'          => 'Nombre',
        'updated'       => 'Actualizado',
        'email'         => 'Email',
        'status'        => 'Estado',
        'users'         => 'Usuarios',
        'groups'        => 'Grupos',
        'permissions'   => 'Permisos',
    ],

    'cards' => [
        'maintenance' => [
            'title'     => 'Volveremos en seguida',
            'message'   => 'Estamos realizando un poco de mantenimiento en este momento. Este aplicación web volverá apenas terminemos!',
            'button'    => 'Contactar Soporte'
        ],
        'missing' => [
            'title'     => 'Página no Econtrada',
            'message'   => '',
        ],
        'whoops' => [
            'title'     => '',
            'message'   => '',
        ],
    ],

];
