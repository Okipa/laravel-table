<?php

return [

    /** Set the default classes for each part of the table. */
    'classes' => [
        'container' => ['table-responsive'],
        'table' => ['table-borderless'],
        'tr' => ['border-bottom'],
        'th' => ['align-middle'],
        'td' => ['align-middle'],
        'results' => ['table-secondary'],
        'disabled' => ['table-danger', 'disabled'],
    ],

    /** Set all the action icons that are used on the table templates. */
    'icon' => [
        'rows_number' => '<i class="fas fa-list-ol"></i>',
        'sort' => '<i class="fas fa-sort fa-fw"></i>',
        'sort_asc' => '<i class="fas fa-sort-up fa-fw"></i>',
        'sort_desc' => '<i class="fas fa-sort-down fa-fw"></i>',
        'search' => '<i class="fas fa-search"></i>',
        'validate' => '<i class="fas fa-check"></i>',
        'info' => '<i class="fas fa-info-circle fa-fw"></i>',
        'reset' => '<i class="fas fa-undo fa-fw"></i>',
        'create' => '<i class="fas fa-plus-circle fa-fw "></i>',
        'show' => '<i class="fas fa-eye fa-fw"></i>',
        'edit' => '<i class="fas fa-edit fa-fw"></i>',
        'destroy' => '<i class="fas fa-trash fa-fw"></i>',
    ],

    /** Set the table default behavior. */
    'behavior' => [
        'rows_number' => 20,
        'activate_rows_number_definition' => true,
    ],

    /** Set the default view path for each component of the table. */
    'template' => [
        'table' => 'bootstrap.table',
        'thead' => 'bootstrap.thead',
        'rows_searching' => 'bootstrap.rows-searching',
        'rows_number_definition' => 'bootstrap.rows-number-definition',
        'create_action' => 'bootstrap.create-action',
        'column_titles' => 'bootstrap.column-titles',
        'tbody' => 'bootstrap.tbody',
        'show_action' => 'bootstrap.show-action',
        'edit_action' => 'bootstrap.edit-action',
        'destroy_action' => 'bootstrap.destroy-action',
        'results' => 'bootstrap.results',
        'tfoot' => 'bootstrap.tfoot',
        'navigation_status' => 'bootstrap.navigation-status',
        'pagination' => 'bootstrap.pagination',
    ],

];
