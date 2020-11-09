<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class TitleTest extends LaravelTableTestCase
{
    public function testTitleAttribute(): void
    {
        $table = (new Table())->model(User::class);
        $table->column('name')->title('Name');
        self::assertEquals('Name', $table->getColumns()->first()->getTitle());
    }

    public function testTitleHtml(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('Name', $html);
        self::assertStringContainsString('Email', $html);
    }
}
