<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class EmptyStatusTest extends LaravelTableTestCase
{
    public function testEmptyListHtml()
    {
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertStringContainsString(__('laravel-table::laravel-table.emptyTable'), $html);
    }

    public function testFilledListHtml()
    {
        $this->createMultipleUsers(5);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('email');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertStringNotContainsString(__('laravel-table::laravel-table.emptyTable'), $html);
    }
}
