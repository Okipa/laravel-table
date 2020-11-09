<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class PrependTest extends LaravelTableTestCase
{
    public function testSetPrependAttribute(): void
    {
        $table = (new Table())->model(User::class);
        $table->column('name')->prependHtml('html');
        self::assertEquals('html', $table->getColumns()->first()->getPrependedHtml());
        self::assertEquals(false, $table->getColumns()->first()->shouldForcePrependedHtmlDisplay());
    }

    public function testSetPrependAttributeAndSetShowWithNoValue(): void
    {
        $table = (new Table())->model(User::class);
        $table->column('name')->prependHtml('html', true);
        self::assertEquals('html', $table->getColumns()->first()->getPrependedHtml());
        self::assertEquals(true, $table->getColumns()->first()->shouldForcePrependedHtmlDisplay());
    }

    public function testSetPrependHtml(): void
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->prependHtml('html');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('html', $html);
    }

    public function testSetPrependWithCustomValueHtml(): void
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->prependHtml('html')->value(fn() => 'test');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('html', $html);
    }

    public function testSetPrependWithNoValueHtml(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->prependHtml('html');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString('html', $html);
    }

    public function testSetPrependWithNoValueButShowAnywayValueHtml(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->prependHtml('html', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('html', $html);
    }
}
