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

    public function testDeactivateRowsNumberSelectionFromConfigHtml()
    {
        config()->set('laravel-table.value.rowsNumberSelectionActivation', false);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email');
        $table->render();
        $thead = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertStringNotContainsString('rows-number-selection', $thead);
        $this->assertStringNotContainsString('type="number"', $thead);
        $this->assertStringNotContainsString('name="rows"', $thead);
        $this->assertStringNotContainsString('value="20"', $thead);
        $this->assertStringNotContainsString('placeholder="' . __('laravel-table::laravel-table.rowsNumber')
                              . '"', $thead);
        $this->assertStringNotContainsString('aria-label="' . __('laravel-table::laravel-table.rowsNumber')
                              . '"', $thead);
    }

    public function testDeactivateRowsNumberSelectionFromMethodHtml()
    {
        config()->set('laravel-table.value.rowsNumberSelectionActivation', true);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumberSelectionActivation(false);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email');
        $table->render();
        $thead = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertStringNotContainsString('rows-number-selection', $thead);
        $this->assertStringNotContainsString('type="number"', $thead);
        $this->assertStringNotContainsString('name="rows"', $thead);
        $this->assertStringNotContainsString('value="20"', $thead);
        $this->assertStringNotContainsString('placeholder="' . __('laravel-table::laravel-table.rowsNumber')
                                 . '"', $thead);
        $this->assertStringNotContainsString('aria-label="' . __('laravel-table::laravel-table.rowsNumber')
                                 . '"', $thead);
    }
    
    public function testActivateRowsNumberSelectionHtml()
    {
        config()->set('laravel-table.value.rowsNumberSelectionActivation', false);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumberSelectionActivation();
        $table->column('name')->title('Name');
        $table->column('email')->title('Email');
        $table->render();
        $thead = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertStringContainsString('rows-number-selection', $thead);
        $this->assertStringContainsString('type="number"', $thead);
        $this->assertStringContainsString('name="rows"', $thead);
        $this->assertStringContainsString('value="20"', $thead);
        $this->assertStringContainsString('placeholder="' . __('laravel-table::laravel-table.rowsNumber')
                              . '"', $thead);
        $this->assertStringContainsString('aria-label="' . __('laravel-table::laravel-table.rowsNumber')
                              . '"', $thead);
    }

    public function testSetCustomRowsNumberHtml()
    {
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(15);
        $table->column('name');
        $table->column('email');
        $table->render();
        $thead = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertStringContainsString('rows-number-selection', $thead);
        $this->assertStringContainsString('type="number"', $thead);
        $this->assertStringContainsString('name="rows"', $thead);
        $this->assertStringContainsString('value="15"', $thead);
        $this->assertStringContainsString('placeholder="' . __('laravel-table::laravel-table.rowsNumber')
                              . '"', $thead);
        $this->assertStringContainsString('aria-label="' . __('laravel-table::laravel-table.rowsNumber')
                              . '"', $thead);
    }

    public function testSetCustomRowsNumberFromRequest()
    {
        $this->createMultipleUsers(20);
        $customRequest = (new Request)->merge(['rows' => 10]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest);
        $table->column('name')->title('Name')->sortable(true, 'asc');
        $table->column('email')->title('Email');
        $table->render();
        $this->assertEquals(
            App(User::class)->orderBy('name', 'asc')->paginate(10)->toArray()['data'],
            $table->list->toArray()['data']
        );
    }
}
