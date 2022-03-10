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
        'rows_number' => '<i class="fa-solid fa-list-ol"></i>',
        'sort' => '<i class="fa-solid fa-sort"></i>',
        'sort_asc' => '<i class="fa-solid fa-sort-up"></i>',
        'sort_desc' => '<i class="fa-solid fa-sort-down"></i>',
        'search' => '<i class="fa-solid fa-magnifying-glass"></i>',
        'validate' => '<i class="fa-solid fa-check"></i>',
        'info' => '<i class="fa-solid fa-circle-info"></i>',
        'reset' => '<i class="fa-solid fa-rotate-left"></i>',
        'create' => '<i class="fa-solid fa-circle-plus"></i>',
        'show' => '<i class="fa-solid fa-eye"></i>',
        'edit' => '<i class="fa-solid fa-pencil"></i>',
        'destroy' => '<i class="fa-solid fa-trash-can"></i>',
    ],

    /** Whether the select allowing to choose the number of rows per page should be displayed by default. */
    'enable_number_of_rows_per_page_choice' => true,

    /** The default number-of-rows-per-page-select options. */
    'number_of_rows_per_page_options' => [10, 25, 50, 75, 100],

];
