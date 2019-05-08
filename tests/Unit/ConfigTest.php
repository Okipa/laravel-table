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
        $this->assertTrue(array_key_exists('value', config('laravel-table')));
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
        $this->assertTrue(array_key_exists('show', config('laravel-table.icon')));
        // laravel-table.value
        $this->assertTrue(array_key_exists('rowsNumber', config('laravel-table.value')));
        $this->assertTrue(array_key_exists('rowsNumberSelectionActivation', config('laravel-table.value')));
        // laravel-table.template
        $this->assertTrue(array_key_exists('table', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('thead', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('tbody', config('laravel-table.template')));
        $this->assertTrue(array_key_exists('tfoot', config('laravel-table.template')));
    }

    public function testCustomDefaultValueRowsNumber()
    {
        config()->set('laravel-table.value.rowsNumber', 9999);
        $this->createMultipleUsers(3);
        $this->routes(['users'], ['index', 'create', 'edit', 'destroy', 'show']);
        $table = (new Table)->model(User::class)->routes([
            'index'   => ['name' => 'users.index'],
            'create'  => ['name' => 'users.create'],
            'edit'    => ['name' => 'users.edit'],
            'destroy' => ['name' => 'users.destroy'],
            'show'    => ['name' => 'users.show'],
        ]);
        $table->column('name')
            ->title('Name')
            ->sortable()
            ->searchable();
        $table->column('email')
            ->title('Email')
            ->searchable()
            ->sortable();
        $table->render();
        $html = view('laravel-table::' . $table->tableComponentPath, compact('table'))->render();
        $this->assertEquals(9999, $table->rows);
        $this->assertStringContainsString('value="9999"', $html);
        $this->assertStringContainsString('rows=9999', $html);
    }
}
