<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ConfigTest extends LaravelTableTestCase
{
    public function it_can_check_config_structure(): void
    {
        // laravel-table
        self::assertArrayHasKey('classes', config('laravel-table'));
        self::assertArrayHasKey('icon', config('laravel-table'));
        self::assertArrayHasKey('behavior', config('laravel-table'));
        self::assertArrayHasKey('template', config('laravel-table'));
        // laravel-table.classes
        self::assertArrayHasKey('container', config('laravel-table.classes'));
        self::assertArrayHasKey('table', config('laravel-table.classes'));
        self::assertArrayHasKey('tr', config('laravel-table.classes'));
        self::assertArrayHasKey('th', config('laravel-table.classes'));
        self::assertArrayHasKey('td', config('laravel-table.classes'));
        self::assertArrayHasKey('results', config('laravel-table.classes'));
        self::assertArrayHasKey('disabled', config('laravel-table.classes'));
        // laravel-table.icon
        self::assertArrayHasKey('rows_number', config('laravel-table.icon'));
        self::assertArrayHasKey('sort', config('laravel-table.icon'));
        self::assertArrayHasKey('sort_asc', config('laravel-table.icon'));
        self::assertArrayHasKey('sort_desc', config('laravel-table.icon'));
        self::assertArrayHasKey('search', config('laravel-table.icon'));
        self::assertArrayHasKey('validate', config('laravel-table.icon'));
        self::assertArrayHasKey('reset', config('laravel-table.icon'));
        self::assertArrayHasKey('create', config('laravel-table.icon'));
        self::assertArrayHasKey('edit', config('laravel-table.icon'));
        self::assertArrayHasKey('destroy', config('laravel-table.icon'));
        self::assertArrayHasKey('show', config('laravel-table.icon'));
        // laravel-table.behavior
        self::assertArrayHasKey('rows_number', config('laravel-table.behavior'));
        self::assertArrayHasKey('activate_rows_number_definition', config('laravel-table.behavior'));
        // laravel-table.template
        self::assertArrayHasKey('table', config('laravel-table.template'));
        self::assertArrayHasKey('thead', config('laravel-table.template'));
        self::assertArrayHasKey('rows_searching', config('laravel-table.template'));
        self::assertArrayHasKey('rows_number_definition', config('laravel-table.template'));
        self::assertArrayHasKey('create_action', config('laravel-table.template'));
        self::assertArrayHasKey('column_titles', config('laravel-table.template'));
        self::assertArrayHasKey('tbody', config('laravel-table.template'));
        self::assertArrayHasKey('show_action', config('laravel-table.template'));
        self::assertArrayHasKey('edit_action', config('laravel-table.template'));
        self::assertArrayHasKey('destroy_action', config('laravel-table.template'));
        self::assertArrayHasKey('results', config('laravel-table.template'));
        self::assertArrayHasKey('tfoot', config('laravel-table.template'));
        self::assertArrayHasKey('navigation_status', config('laravel-table.template'));
        self::assertArrayHasKey('pagination', config('laravel-table.template'));
    }
}
