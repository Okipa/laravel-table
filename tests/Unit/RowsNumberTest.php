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
        $this->assertNotContains('rows-number-selection', $thead);
        $this->assertNotContains('type="number"', $thead);
        $this->assertNotContains('name="rows"', $thead);
        $this->assertNotContains('value="20"', $thead);
        $this->assertNotContains('placeholder="' . __('laravel-table::laravel-table.rowsNumber')
                              . '"', $thead);
        $this->assertNotContains('aria-label="' . __('laravel-table::laravel-table.rowsNumber')
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
        $this->assertNotContains('rows-number-selection', $thead);
        $this->assertNotContains('type="number"', $thead);
        $this->assertNotContains('name="rows"', $thead);
        $this->assertNotContains('value="20"', $thead);
        $this->assertNotContains('placeholder="' . __('laravel-table::laravel-table.rowsNumber')
                                 . '"', $thead);
        $this->assertNotContains('aria-label="' . __('laravel-table::laravel-table.rowsNumber')
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
        $this->assertContains('rows-number-selection', $thead);
        $this->assertContains('type="number"', $thead);
        $this->assertContains('name="rows"', $thead);
        $this->assertContains('value="20"', $thead);
        $this->assertContains('placeholder="' . __('laravel-table::laravel-table.rowsNumber')
                              . '"', $thead);
        $this->assertContains('aria-label="' . __('laravel-table::laravel-table.rowsNumber')
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
        $this->assertContains('rows-number-selection', $thead);
        $this->assertContains('type="number"', $thead);
        $this->assertContains('name="rows"', $thead);
        $this->assertContains('value="15"', $thead);
        $this->assertContains('placeholder="' . __('laravel-table::laravel-table.rowsNumber')
                              . '"', $thead);
        $this->assertContains('aria-label="' . __('laravel-table::laravel-table.rowsNumber')
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
