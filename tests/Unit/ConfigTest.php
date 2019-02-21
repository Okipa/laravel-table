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
        $this->assertTrue(array_key_exists('rows', config('laravel-table')));
        $this->assertTrue(array_key_exists('template', config('laravel-table')));
        // laravel-table.classes
        $this->assertTrue(array_key_exists('container', config('laravel-table.classes')));
        $this->assertTrue(array_key_exists('table', config('laravel-table.classes')));
        $this->assertTrue(array_key_exists('tr', config('laravel-table.classes')));
        $this->assertTrue(array_key_exists('th', config('laravel-table.classes')));
        $this->assertTrue(array_key_exists('td', config('laravel-table.classes')));
        $this->assertTrue(array_key_exists('results', config('laravel-table.classes')));
        // laravel-table.icon
        $this->assertTrue(array_key_exists('rowsNumber', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('sort', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('sortAsc', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('sortDesc', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('search', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('validate', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('cancel', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('create', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('edit', config('laravel-table.icon')));
        $this->assertTrue(array_key_exists('destroy', config('laravel-table.icon')));
        // laravel-table.rows
        $this->assertTrue(array_key_exists('number', config('laravel-table.rows')));
        $this->assertTrue(array_key_exists('disabled', config('laravel-table.rows')));
        // laravel-table.rows.number
        $this->assertTrue(array_key_exists('default', config('laravel-table.rows.number')));
        $this->assertTrue(array_key_exists('selection', config('laravel-table.rows.number')));
        // laravel-table.rows.classes
        $this->assertTrue(array_key_exists('classes', config('laravel-table.rows.disabled')));
        // laravel-table.template
        $this->assertTrue(array_key_exists('table', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('thead', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('tbody', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('tfoot', config('laravel-table.template')));
    }

    public function testCustomDefaultValueRowsNumber()
    {
        config()->set('laravel-table.rows.number.default', 9999);
        $this->createMultipleUsers(3);
        $this->routes(['users'], ['index', 'create', 'edit', 'destroy']);
        $table = (new Table)->model(User::class)->routes([
            'index'   => ['name' => 'users.index'],
            'create'  => ['name' => 'users.create'],
            'edit'    => ['name' => 'users.edit'],
            'destroy' => ['name' => 'users.destroy'],
        ]);
        $table->column('name')
            ->title('Name')
            ->sortable()
            ->searchable();;
        $table->column('email')
            ->title('Email')
            ->searchable()
            ->sortable();
        $table->render();
        $html = view('laravel-table::' . $table->tableComponentPath, compact('table'))->render();
        $this->assertEquals(9999, $table->rows);
        $this->assertContains('value="9999"', $html);
        $this->assertContains('rows=9999', $html);
    }
}
