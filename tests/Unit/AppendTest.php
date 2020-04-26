<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class AppendTest extends LaravelTableTestCase
{
    public function testSetAppendAttribute()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->append('icon');
        $this->assertEquals('icon', $table->columns->first()->append);
        $this->assertEquals(false, $table->columns->first()->displayAppendEvenIfNoValue);
    }

    public function testSetAppendAttributeAndSetShowWithNoValue()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->append('icon', true);
        $this->assertEquals('icon', $table->columns->first()->append);
        $this->assertEquals(true, $table->columns->first()->displayAppendEvenIfNoValue);
    }

    public function testSetAppendHtml()
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->append('icon');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->render();
        $this->assertStringContainsString('icon', $html);
    }

    public function testSetAppendWithCustomValueHtml()
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->append('icon')->value(function () {
            return 'test';
        });
        $table->render();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->render();
        $this->assertStringContainsString('icon', $html);
    }

    public function testSetAppendWithNoValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->append('icon');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->render();
        $this->assertStringNotContainsString('icon', $html);
    }

    public function testSetAppendWithNoValueButShowAnywayValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->append('icon', true);
        $table->render();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->render();
        $this->assertStringContainsString('icon', $html);
    }
}
