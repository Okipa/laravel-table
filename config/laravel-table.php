<?php

return [

    /**
     * The UI framework that should be used to generate the components.
     * Can be set to:
     * - bootstrap-5
     * - bootstrap-4
     * - tailwind-2 (upcoming feature)
     */
    'ui' => 'bootstrap-5',

    /** Set all the displayed action icons. */
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

    /** The default number of displayed rows per page. */
    'number_of_rows_per_page' => 10,

];
