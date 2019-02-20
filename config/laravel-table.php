<?php

return [

    'classes' => [
        'container' => ['table-responsive', 'pt-3'],
        'table'     => ['table-striped', 'table-hover', 'mt-3'],
        'tr'        => [],
        'th'        => ['align-middle'],
        'td'        => ['align-middle'],
        'results'   => ['table-secondary'],
    ],

    'icon' => [
        'rowsNumber' => '<i class="fas fa-list"></i>',
        'sort'       => '<i class="fas fa-sort fa-fw"></i>',
        'sortAsc'    => '<i class="fas fa-sort-up fa-fw"></i>',
        'sortDesc'   => '<i class="fas fa-sort-down fa-fw"></i>',
        'search'     => '<i class="fas fa-search"></i>',
        'validate'   => '<i class="fas fa-check"></i>',
        'cancel'     => '<i class="fas fa-times"></i>',
        'create'     => '<i class="fas fa-plus-circle fa-fw "></i>',
        'edit'       => '<i class="fas fa-edit fa-fw"></i>',
        'destroy'    => '<i class="fas fa-trash fa-fw"></i>',
    ],

    'rows' => [
        'number'   => [
            'default'   => 20,
            'selection' => true,
        ],
        'disabled' => [
            'classes' => ['disabled', 'bg-secondary', 'text-white'],
        ],
    ],

    'template' => [
        'table'   => 'bootstrap.table',
        'thead'   => 'bootstrap.thead',
        'tbody'   => 'bootstrap.tbody',
        'results' => 'bootstrap.results',
        'tfoot'   => 'bootstrap.tfoot',
    ],

];
