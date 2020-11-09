<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class AppendTest extends LaravelTableTestCase
{
    public function testSetAppendAttribute(): void
    {
        $table = (new Table())->model(User::class);
        $table->column('name')->appendsHtml('html');
        self::assertEquals('html', $table->getColumns()->first()->getAppendedHtml());
        self::assertEquals(false, $table->getColumns()->first()->shouldForceAppendedHtmlDisplay());
    }

    public function testSetAppendAttributeAndSetShowWithNoValue(): void
    {
        $table = (new Table())->model(User::class);
        $table->column('name')->appendsHtml('html', true);
        self::assertEquals('html', $table->getColumns()->first()->getAppendedHtml());
        self::assertEquals(true, $table->getColumns()->first()->shouldForceAppendedHtmlDisplay());
    }

    public function testSetAppendHtml(): void
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->appendsHtml('html');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('html', $html);
    }

    public function testSetAppendWithCustomValueHtml(): void
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->appendsHtml('html')->value(fn() =>'test');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('html', $html);
    }

    public function testSetAppendWithNoValueHtml(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->appendsHtml('html');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString('html', $html);
    }

    public function testSetAppendWithNoValueButShowAnywayValueHtml(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->appendsHtml('html', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('html', $html);
    }
}
