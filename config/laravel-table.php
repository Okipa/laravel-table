<?php

return [

    /**
     * The UI framework that should be used to generate the components.
     * Can be set to:
     * - bootstrap-5
     * - bootstrap-4
     * - tailwind-3 (upcoming feature)
     */
    'ui' => 'bootstrap-5',

    /** Set all the displayed action icons. */
    'icon' => [
        'filter' => '<i class="fa-solid fa-filter fa-2x"></i>',
        'rows_number' => '<i class="fa-solid fa-list-ol"></i>',
        'sort' => '<i class="fa-solid fa-sort fa-fw"></i>',
        'sort_asc' => '<i class="fa-solid fa-sort-up fa-fw"></i>',
        'sort_desc' => '<i class="fa-solid fa-sort-down fa-fw"></i>',
        'search' => '<i class="fa-solid fa-magnifying-glass"></i>',
        'validate' => '<i class="fa-solid fa-check"></i>',
        'info' => '<i class="fa-solid fa-circle-info"></i>',
        'reset' => '<i class="fa-solid fa-rotate-left"></i>',
        'drag_drop' => '<i class="fa-solid fa-grip-vertical"></i>',
        'add' => '<i class="fa-solid fa-circle-plus fa-fw"></i>',
        'create' => '<i class="fa-solid fa-circle-plus fa-fw"></i>',
        'show' => '<i class="fa-solid fa-eye fa-fw"></i>',
        'edit' => '<i class="fa-solid fa-pencil fa-fw"></i>',
        'destroy' => '<i class="fa-solid fa-trash-can fa-fw"></i>',
        'active' => '<i class="fa-solid fa-check text-success fa-fw"></i>',
        'inactive' => '<i class="fa-solid fa-xmark text-danger fa-fw"></i>',
        'email_verified' => '<i class="fa-solid fa-envelope-circle-check fa-fw"></i>',
        'email_unverified' => '<i class="fa-solid fa-envelope fa-fw"></i>',
        'toggle_on' => '<i class="fa-solid fa-toggle-on fa-fw"></i>',
        'toggle_off' => '<i class="fa-solid fa-toggle-off fa-fw"></i>',
    ],

    /** The default table select HTML components attributes. */
    'html_select_components_attributes' => [],

    /** Whether the select allowing to choose the number of rows per page should be displayed by default. */
    'enable_number_of_rows_per_page_choice' => true,

    /** The default number-of-rows-per-page-select options. */
    'number_of_rows_per_page_default_options' => [10, 25, 50, 75, 100],

];
