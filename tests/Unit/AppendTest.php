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
        $table->column('name')->appendsHtml('html');
        $this->assertEquals('html', $table->getColumns()->first()->getAppendedHtml());
        $this->assertEquals(false, $table->getColumns()->first()->shouldForceAppendedHtmlDisplay());
    }

    public function testSetAppendAttributeAndSetShowWithNoValue()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->appendsHtml('html', true);
        $this->assertEquals('html', $table->getColumns()->first()->getAppendedHtml());
        $this->assertEquals(true, $table->getColumns()->first()->shouldForceAppendedHtmlDisplay());
    }

    public function testSetAppendHtml()
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->appendsHtml('html');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('html', $html);
    }

    public function testSetAppendWithCustomValueHtml()
    {
        $this->createMultipleUsers(1);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->appendsHtml('html')->value(function () {
            return 'test';
        });
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('html', $html);
    }

    public function testSetAppendWithNoValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->appendsHtml('html');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringNotContainsString('html', $html);
    }

    public function testSetAppendWithNoValueButShowAnywayValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->appendsHtml('html', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('html', $html);
    }
}
