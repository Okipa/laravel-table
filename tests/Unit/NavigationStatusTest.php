<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class NavigationStatusTest extends LaravelTableTestCase
{
    public function testNavigationStatus()
    {
        $this->createMultipleUsers(10);
        $this->routes(['users'], ['index']);
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->render();
        $this->assertEquals($table->navigationStatus(), __('laravel-table::laravel-table.navigation', [
            'start' => 1,
            'stop'  => 10,
            'total' => 10,
        ]));
    }

    public function testNavigationStatusHtml()
    {
        $this->routes(['users'], ['index']);
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->render();
        $html = view('laravel-table::' . $table->tfootComponentPath, compact('table'))->render();
        $this->assertStringContainsString($table->navigationStatus(), $html);
    }
}
