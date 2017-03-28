<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Error messages
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the Core components package.
    |
    */

    'errors' => [
        'create'              => 'Object could not be stored.',
        'read'                => 'Object could not be found.',
        'update'              => 'Object could not be updated.',
        'delete'              => 'Object could not be deleted.',
        'find_by'             => 'Object could not be found by :attribute.',
        'find_in'             => 'Objects could not be found by :attribute.',
        'retrieve'            => 'Object could not be found in trash.',
        'trash'               => 'Object could not be trashed.',
        'restore'             => 'Object could not be restored.',
        'purge'               => 'Objects could not be purged from trash.',
        'recover'             => 'Objects could not be recovered from trash.',
        'truncate'            => 'Objects could not be deleted.',
        'not_related'         => 'Object does not have a :relationship relationship.',
        'rate_limit_exceeded' => 'Not so fast El Guapo! Your rate limit has been exceeded so you\'ve been put into a :timeout minute timeout.',
    ],


    /*
    |--------------------------------------------------------------------------
    | Message lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain message lines used by the
    | Core components package models.
    |
    */

    'messages' => [
        'created'             => 'Object has been created.',
        'updated'             => 'Object has been updated.',
        'deleted'             => 'Object has been deleted.',
        'trashed'             => 'Object has been trashed.',
        'restored'            => 'Object has been restored.',
        'purged'              => 'Objects have been purged from trash.',
        'recovered'           => 'Objects have been recovered from trash.',
        'truncated'           => 'Objects have been deleted.',
        'no_results'          => 'Search returned no results for objects.',
        'rate_limit_exceeded' => 'Rate Limit Exceeded',

        'bulk' => [
            'deleted'   => 'Selected objects have been deleted.',
            'restored'  => 'Selected objects have been restored.',
            'trashed'   => 'Selected objects have been trashed.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Button lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain message lines used by the
    | Core components package buttons.
    |
    */

    'buttons' => [
        'cancel'      => 'Cancel',
        'close'       => 'Close',
        'delete'      => 'Delete',
        'edit'        => 'Edit',
        'filter'      => 'Filter',
        'new'         => 'New',
        'public_site' => 'Public Site',
        'restore'     => 'Restore',
        'search'      => 'Search',
        'toggle_menu' => 'Toggle Menu',
        'trash'       => 'Trash',
        'truncate'    => 'Delete All',
        'view'        => 'View',
    ],

    /*
    |--------------------------------------------------------------------------
    | Label lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain label lines used by the
    | Core components package view.
    |
    */

    'labels' => [
        'actions'                => 'Actions',
        'administration'         => 'Administration',
        'created'                => 'Created',
        'dashboard'              => 'Dashboard',
        'deleted'                => 'Deleted',
        'display_name'           => 'Display Name',
        'email'                  => 'Email Address',
        'keywords'               => 'Keywords',
        'max_results'            => 'Max Results',
        'name'                   => 'Name',
        'never_activated'        => 'Never Activated',
        'never_authenticated'    => 'Never Logged In',
        'never_created'          => 'Never Created',
        'never_deleted'          => 'Never Deleted',
        'never_expires'          => 'Never Expires',
        'never_password_updated' => 'Never Set',
        'never_updated'          => 'Never Updated',
        'of'                     => 'of',
        'order_by'               => 'Order By',
        'showing'                => 'Showing',
        'sort_results'           => 'Sort Results',
        'to'                     => 'to',
        'updated'                => 'Last Modified',
    ],

];
