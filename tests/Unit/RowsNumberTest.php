<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Http\Request;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class RowsNumberTest extends LaravelTableTestCase
{
    public function testSetRowsNumberSelectionActivationAttribute()
    {
        $table = (new Table)->activateRowsNumberDefinition();
        $this->assertTrue($table->getRowsNumberSelectionActivation());
    }

    public function testSetRowsNumberAttribute()
    {
        $rows = 10;
        $table = (new Table)->rowsNumber($rows);
        $this->assertEquals($rows, $table->getRowsNumberValue());
    }

    public function testSetUnlimitedRowsNumberAttribute()
    {
        $rows = false;
        $table = (new Table)->rowsNumber(false);
        $this->assertEquals($rows, $table->getRowsNumberValue());
    }

    public function testDeactivateRowsNumberDefinitionFromConfigHtml()
    {
        config()->set('laravel-table.behavior.activate_rows_number_definition', false);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->searchable();
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        $this->assertStringNotContainsString('rows-number-selection', $html);
        $this->assertStringNotContainsString('type="hidden" name="search"', $html);
        $this->assertStringNotContainsString(
            'placeholder="Number of rows"',
            $html
        );
        $this->assertStringNotContainsString(
            'aria-label="Number of rows"',
            $html
        );
    }

    public function testDeactivateRowsNumberDefinitionFromMethodHtml()
    {
        config()->set('laravel-table.behavior.activate_rows_number_definition', true);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->activateRowsNumberDefinition(false);
        $table->column('name')->searchable();
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        $this->assertStringNotContainsString('rows-number-selection', $html);
        $this->assertStringNotContainsString('type="hidden" name="search"', $html);
        $this->assertStringNotContainsString(
            'placeholder="Number of rows"',
            $html
        );
        $this->assertStringNotContainsString(
            'aria-label="Number of rows"',
            $html
        );
    }

    public function testActivateRowsNumberSelectionHtml()
    {
        config()->set('laravel-table.behavior.activate_rows_number_definition', false);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->activateRowsNumberDefinition();
        $table->column('name');
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('rows-number-selection', $html);
        $this->assertStringContainsString('type="hidden" name="search"', $html);
        $this->assertStringContainsString(
            'placeholder="Number of rows"',
            $html
        );
        $this->assertStringContainsString(
            'aria-label="Number of rows"',
            $html
        );
    }

    public function testSetCustomRowsNumberFromConfigHtml()
    {
        config()->set('laravel-table.behavior.rows_number', 15);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('value="15"', $html);
    }

    public function testSetCustomRowsNumberFromMethodHtml()
    {
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(15);
        $table->column('name');
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('value="15"', $html);
    }

    public function testSetCustomRowsNumberFromRequest()
    {
        $this->createMultipleUsers(20);
        $customRequest = (new Request)->merge([(new Table)->getRowsNumberField() => 10]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest);
        $table->column('name')->sortable(true, 'asc');
        $table->column('email');
        $table->configure();
        $this->assertEquals(
            App(User::class)->orderBy('name', 'asc')->paginate(10)->toArray()['data'],
            $table->getPaginator()->toArray()['data']
        );
    }

    public function testSetUnlimitedRowsNumberHtml()
    {
        config()->set('laravel-table.behavior.rows_number', 5);
        $users = $this->createMultipleUsers(20);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(null);
        $table->column('name');
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('value=""', $html);
        foreach ($users as $user) {
            $this->assertStringContainsString($user->name, $html);
            $this->assertStringContainsString($user->email, $html);
        }
    }
}
