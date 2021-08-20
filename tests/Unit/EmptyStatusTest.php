<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class EmptyStatusTest extends LaravelTableTestCase
{
    public function testEmptyListHtml(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(config('laravel-table.icon.info'), $html);
        self::assertStringContainsString('No results were found.', $html);
    }

    public function testFilledListHtml(): void
    {
        $this->createMultipleUsers(5);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString(config('laravel-table.icon.info'), $html);
        self::assertStringNotContainsString('No results were found.', $html);
    }
}
