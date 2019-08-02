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
        $table = (new Table)->rowsNumberSelectionActivation();
        $this->assertTrue($table->rowsNumberSelectionActivation);
    }

    public function testSetRowsNumberAttribute()
    {
        $rows = 10;
        $table = (new Table)->rowsNumber($rows);
        $this->assertEquals($rows, $table->rows);
    }

    public function testSetUnlimitedRowsNumberAttribute()
    {
        $rows = false;
        $table = (new Table)->rowsNumber(null);
        $this->assertEquals($rows, $table->rows);
    }

    public function testDeactivateRowsNumberSelectionFromConfigHtml()
    {
        config()->set('laravel-table.value.rowsNumberSelectionActivation', false);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->searchable();
        $table->column('email');
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertStringNotContainsString('rows-number-selection', $html);
        $this->assertStringNotContainsString('type="hidden" name="search"', $html);
        $this->assertStringNotContainsString(
            'placeholder="' . __('laravel-table::laravel-table.rowsNumber') . '"',
            $html
        );
        $this->assertStringNotContainsString(
            'aria-label="' . __('laravel-table::laravel-table.rowsNumber') . '"',
            $html
        );
    }

    public function testDeactivateRowsNumberSelectionFromMethodHtml()
    {
        config()->set('laravel-table.value.rowsNumberSelectionActivation', true);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumberSelectionActivation(false);
        $table->column('name')->searchable();
        $table->column('email');
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertStringNotContainsString('rows-number-selection', $html);
        $this->assertStringNotContainsString('type="hidden" name="search"', $html);
        $this->assertStringNotContainsString(
            'placeholder="' . __('laravel-table::laravel-table.rowsNumber') . '"',
            $html
        );
        $this->assertStringNotContainsString(
            'aria-label="' . __('laravel-table::laravel-table.rowsNumber') . '"',
            $html
        );
    }

    public function testActivateRowsNumberSelectionHtml()
    {
        config()->set('laravel-table.value.rowsNumberSelectionActivation', false);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumberSelectionActivation();
        $table->column('name');
        $table->column('email');
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertStringContainsString('rows-number-selection', $html);
        $this->assertStringContainsString('type="hidden" name="search"', $html);
        $this->assertStringContainsString(
            'placeholder="' . __('laravel-table::laravel-table.rowsNumber') . '"',
            $html
        );
        $this->assertStringContainsString(
            'aria-label="' . __('laravel-table::laravel-table.rowsNumber') . '"',
            $html
        );
    }

    public function testSetCustomRowsNumberFromConfigHtml()
    {
        config()->set('laravel-table.value.rowsNumber', 15);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('email');
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
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
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertStringContainsString('value="15"', $html);
    }

    public function testSetCustomRowsNumberFromRequest()
    {
        $this->createMultipleUsers(20);
        $customRequest = (new Request)->merge([(new Table)->rowsField => 10]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest);
        $table->column('name')->sortable(true, 'asc');
        $table->column('email');
        $table->render();
        $this->assertEquals(
            App(User::class)->orderBy('name', 'asc')->paginate(10)->toArray()['data'],
            $table->list->toArray()['data']
        );
    }

    public function testSetUnlimitedRowsNumberHtml()
    {
        config()->set('laravel-table.value.rowsNumber', 5);
        $users = $this->createMultipleUsers(20);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(null);
        $table->column('name');
        $table->column('email');
        $table->render();
        $html = view('laravel-table::' . $table->tableComponentPath, compact('table'))->render();
        $this->assertStringContainsString('value=""', $html);
        foreach ($users as $user) {
            $this->assertStringContainsString($user->name, $html);
            $this->assertStringContainsString($user->email, $html);
        }
    }
}
