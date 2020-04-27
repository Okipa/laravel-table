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
        $table->configure();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->toHtml();
        $this->assertStringContainsString(config('laravel-table.icon.info'), $html);
        $this->assertStringContainsString(__('laravel-table::laravel-table.emptyTable'), $html);
    }

    public function testFilledListHtml()
    {
        $this->createMultipleUsers(5);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->toHtml();
        $this->assertStringNotContainsString(config('laravel-table.icon.info'), $html);
        $this->assertStringNotContainsString(__('laravel-table::laravel-table.emptyTable'), $html);
    }
}
