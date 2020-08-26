<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ConfigTest extends LaravelTableTestCase
{
    public function testConfigStructure()
    {
        // laravel-table
        $this->assertTrue(array_key_exists('classes', config('laravel-table')));
        $this->assertTrue(array_key_exists('icon', config('laravel-table')));
        $this->assertTrue(array_key_exists('behavior', config('laravel-table')));
        $this->assertTrue(array_key_exists('template', config('laravel-table')));
        // laravel-table.classes
        $this->assertTrue(array_key_exists('container', config('laravel-table.classes')));
        $this->assertTrue(array_key_exists('table', config('laravel-table.classes')));
        $this->assertTrue(array_key_exists('tr', config('laravel-table.classes')));
        $this->assertTrue(array_key_exists('th', config('laravel-table.classes')));
        $this->assertTrue(array_key_exists('td', config('laravel-table.classes')));
        $this->assertTrue(array_key_exists('results', config('laravel-table.classes')));
        $this->assertTrue(array_key_exists('disabled', config('laravel-table.classes')));
        // laravel-table.icon
        $this->assertTrue(array_key_exists('rows_number', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('sort', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('sort_asc', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('sort_desc', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('search', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('validate', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('reset', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('create', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('edit', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('destroy', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('show', config('laravel-table.icon')));
        // laravel-table.behavior
        $this->assertTrue(array_key_exists('rows_number', config('laravel-table.behavior')));
        $this->assertTrue(array_key_exists('activate_rows_number_definition', config('laravel-table.behavior')));
        // laravel-table.template
        $this->assertTrue(array_key_exists('table', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('thead', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('rows_searching', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('rows_number_definition', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('create_action', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('column_titles', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('tbody', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('show_action', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('edit_action', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('destroy_action', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('results', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('tfoot', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('navigation_status', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('pagination', config('laravel-table.template')));
    }

    public function testCustomDefaultValueRowsNumber()
    {
        config()->set('laravel-table.behavior.rows_number', 9999);
        $this->createMultipleUsers(3);
        $this->routes(['users'], ['index', 'create', 'edit', 'destroy', 'show']);
        $table = (new Table)->model(User::class)->routes([
            'index' => ['name' => 'users.index'],
            'create' => ['name' => 'users.create'],
            'edit' => ['name' => 'users.edit'],
            'destroy' => ['name' => 'users.destroy'],
            'show' => ['name' => 'users.show'],
        ]);
        $table->column('name')
            ->title('Name')
            ->sortable()
            ->searchable();
        $table->column('email')
            ->title('Email')
            ->searchable()
            ->sortable();
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        $this->assertEquals(9999, $table->getRowsNumberValue());
        $this->assertStringContainsString('value="9999"', $html);
        $this->assertStringContainsString('rows=9999', $html);
    }
}
