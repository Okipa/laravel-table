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
        $table->column('name')->prependHtml('html');
        $this->assertEquals('html', $table->getColumns()->first()->getPrependedHtml());
        $this->assertEquals(false, $table->getColumns()->first()->shouldForcePrependedHtmlDisplay());
    }

    public function testSetPrependAttributeAndSetShowWithNoValue()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->prependHtml('html', true);
        $this->assertEquals('html', $table->getColumns()->first()->getPrependedHtml());
        $this->assertEquals(true, $table->getColumns()->first()->shouldForcePrependedHtmlDisplay());
    }

    public function testSetPrependHtml()
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->prependHtml('html');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('html', $html);
    }

    public function testSetPrependWithCustomValueHtml()
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->prependHtml('html')->value(function () {
            return 'test';
        });
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('html', $html);
    }

    public function testSetPrependWithNoValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->prependHtml('html');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringNotContainsString('html', $html);
    }

    public function testSetPrependWithNoValueButShowAnywayValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->prependHtml('html', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('html', $html);
    }
}
