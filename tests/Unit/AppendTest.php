<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class AppendTest extends LaravelTableTestCase
{
    /** @test */
    public function it_can_append_html_with_model_value(): void
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->appendHtml('html');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertEquals('html', $table->getColumns()->first()->getAppendedHtml());
        self::assertFalse($table->getColumns()->first()->shouldForceAppendedHtmlDisplay());
        self::assertStringContainsString('html', $html);
    }

    /** @test */
    public function it_can_append_html_with_collection_value(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromCollection(collect([['name' => 'Name test']]))->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->appendHtml('html');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertEquals('html', $table->getColumns()->first()->getAppendedHtml());
        self::assertFalse($table->getColumns()->first()->shouldForceAppendedHtmlDisplay());
        self::assertStringContainsString('html', $html);
    }

    /** @test */
    public function it_can_append_html_with_custom_value(): void
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->appendHtml('html')->value(fn() =>'test');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('html', $html);
    }

    /** @test */
    public function it_cant_append_html_when_model_has_no_value(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->appendHtml('html');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertEquals('html', $table->getColumns()->first()->getAppendedHtml());
        self::assertFalse($table->getColumns()->first()->shouldForceAppendedHtmlDisplay());
        self::assertStringNotContainsString('html', $html);
    }

    /** @test */
    public function it_can_append_html_when_model_has_no_value_with_forced_displaying(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->appendHtml('html', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertEquals('html', $table->getColumns()->first()->getAppendedHtml());
        self::assertTrue($table->getColumns()->first()->shouldForceAppendedHtmlDisplay());
        self::assertStringContainsString('html', $html);
    }
}
