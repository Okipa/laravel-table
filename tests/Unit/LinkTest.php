<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class LinkTest extends LaravelTableTestCase
{
    public function testSetIsLinkAttributeEmpty(): void
    {
        $table = (new Table())->fromModel(User::class);
        $table->column('name')->link();
        self::assertEquals('__VALUE__', $table->getColumns()->first()->getUrl());
    }

    public function testSetIsLinkAttributeString(): void
    {
        $table = (new Table())->fromModel(User::class);
        $table->column('name')->link('link');
        self::assertEquals('link', $table->getColumns()->first()->getUrl());
    }

    public function testSetIsLinkAttributeClosure(): void
    {
        $table = (new Table())->fromModel(User::class);
        $closure = fn() => null;
        $table->column('name')->link($closure);
        self::assertEquals($closure, $table->getColumns()->first()->getUrlClosure());
    }

    public function testIsLinkEmptyHtml(): void
    {
        $user = $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link();
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<a href="' . $user->name . '" title="' . $user->name . '">', $html);
    }

    public function testIsLinkStringHtml(): void
    {
        $user = $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link('test');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<a href="test" title="' . $user->name . '">', $html);
    }

    public function testIsLinkClosureHtml(): void
    {
        $user = $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link(fn() => 'url');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<a href="url" title="' . $user->name . '">', $html);
    }

    public function testIsLinkWithDefaultValueHtml(): void
    {
        $user = $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link();
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<a href="' . $user->name . '" title="' . $user->name . '">', $html);
    }

    public function testIsLinkWithNoValueHtml(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link();
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString('<a href="', $html);
    }

    public function testIsLinkWithNoValueWithPrependedHtml(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link()->prependHtml('html', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString('<a href="', $html);
    }

    public function testIsLinkWithNoValueWithAppendedHtml(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link()->appendHtml('icon', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString('<a href="', $html);
    }

    public function testIsLinkWithCustomValueHtml(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link('url')->value(fn() => 'test');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<a href="url" title="test">', $html);
    }

    public function testIsLinkWithNoValueCustomValueHtml(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link()->value(fn() => 'test');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<a href="test" title="test">', $html);
    }
}
