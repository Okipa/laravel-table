<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class IconTest extends LaravelTableTestCase
{
    public function testSetIconAttribute()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->icon('icon');
        $this->assertEquals('icon', $table->columns->first()->icon);
        $this->assertEquals(false, $table->columns->first()->displayIconWhenNoValue);
    }

    public function testSetIconAttributeAndSetShowWithNoValue()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->icon('icon', true);
        $this->assertEquals('icon', $table->columns->first()->icon);
        $this->assertEquals(true, $table->columns->first()->displayIconWhenNoValue);
    }

    public function testSetIconHtml()
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->icon('icon');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertStringContainsString('icon', $html);
    }

    public function testSetIconWithCustomValueHtml()
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->icon('icon')->value(function () {
            return 'test';
        });
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertStringContainsString('icon', $html);
    }

    public function testSetIconWithNoValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->icon('icon');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertStringNotContainsString('icon', $html);
    }

    public function testSetIconWithNoValueButShowAnywayValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->icon('icon', true);
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertStringContainsString('icon', $html);
    }
}
