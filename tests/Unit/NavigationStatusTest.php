<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class NavigationStatusTest extends LaravelTableTestCase
{
    public function testNavigationStatus(): void
    {
        $this->createMultipleUsers(10);
        $this->routes(['users'], ['index']);
        $table = (new Table())->routes(['index' => ['name' => 'users.index']])->fromModel(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        self::assertEquals(
            $table->getNavigationStatus(),
            __('Showing results <b>:start</b> to <b>:stop</b> on <b>:total</b>', [
                'start' => 1,
                'stop' => 10,
                'total' => 10,
            ])
        );
    }

    public function testNavigationStatusHtml(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->routes(['index' => ['name' => 'users.index']])->fromModel(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTfootTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString($table->getNavigationStatus(), $html);
    }
}
