<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class TitleTest extends LaravelTableTestCase
{
    public function testTitleAttribute()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->title('Name');
        $this->assertEquals('Name', $table->columns->first()->title);
    }

    public function testTitleHtml()
    {
        $this->routes(['users'], ['index']);
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email');
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertStringContainsString('Name', $html);
        $this->assertStringContainsString('Email', $html);
    }
}
