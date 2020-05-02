<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class LinkTest extends LaravelTableTestCase
{
    public function testSetIsLinkAttributeEmpty()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->link();
        $this->assertEquals('__VALUE__', $table->getColumns()->first()->getUrl());
    }

    public function testSetIsLinkAttributeString()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->link('link');
        $this->assertEquals('link', $table->getColumns()->first()->getUrl());
    }

    public function testSetIsLinkAttributeClosure()
    {
        $table = (new Table)->model(User::class);
        $closure = function ($model, $column) {
        };
        $table->column('name')->link($closure);
        $this->assertEquals($closure, $table->getColumns()->first()->getUrlClosure());
    }

    public function testIsLinkEmptyHtml()
    {
        $user = $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link();
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<a href="' . $user->name . '" title="' . $user->name . '">', $html);
    }

    public function testIsLinkStringHtml()
    {
        $user = $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link('test');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<a href="test" title="' . $user->name . '">', $html);
    }

    public function testIsLinkClosureHtml()
    {
        $user = $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link(function () {
            return 'url';
        });
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<a href="url" title="' . $user->name . '">', $html);
    }

    public function testIsLinkWithDefaultValueHtml()
    {
        $user = $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link();
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<a href="' . $user->name . '" title="' . $user->name . '">', $html);
    }

    public function testIsLinkWithNoValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link();
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringNotContainsString('<a href="', $html);
    }

    public function testIsLinkWithNoValueWithPrependedHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link()->prependHtml('html', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringNotContainsString('<a href="', $html);
    }

    public function testIsLinkWithNoValueWithAppendedHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link()->appendsHtml('icon', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringNotContainsString('<a href="', $html);
    }

    public function testIsLinkWithCustomValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link('url')->value(function () {
            return 'test';
        });
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<a href="url" title="test">', $html);
    }

    public function testIsLinkWithNoValueCustomValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->link()->value(function () {
            return 'test';
        });
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<a href="test" title="test">', $html);
    }
}
