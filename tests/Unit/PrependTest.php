<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class PrependTest extends LaravelTableTestCase
{
    public function testSetPrependAttribute()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->prepend('icon');
        $this->assertEquals('icon', $table->columns->first()->prepend);
        $this->assertEquals(false, $table->columns->first()->displayPrependEvenIfNoValue);
    }

    public function testSetPrependAttributeAndSetShowWithNoValue()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->prepend('icon', true);
        $this->assertEquals('icon', $table->columns->first()->prepend);
        $this->assertEquals(true, $table->columns->first()->displayPrependEvenIfNoValue);
    }

    public function testSetPrependHtml()
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->prepend('icon');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->render();
        $this->assertStringContainsString('icon', $html);
    }

    public function testSetPrependWithCustomValueHtml()
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->prepend('icon')->value(function () {
            return 'test';
        });
        $table->render();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->render();
        $this->assertStringContainsString('icon', $html);
    }

    public function testSetPrependWithNoValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->prepend('icon');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->render();
        $this->assertStringNotContainsString('icon', $html);
    }

    public function testSetPrependWithNoValueButShowAnywayValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->prepend('icon', true);
        $table->render();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->render();
        $this->assertStringContainsString('icon', $html);
    }
}
