<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Support\Str;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ButtonTest extends LaravelTableTestCase
{
    /** @test */
    public function it_can_set_button_with_model_value(): void
    {
        $user = $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary']);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(['btn', 'btn-primary'], $table->getColumns()->first()->getButtonClasses());
        self::assertStringContainsString('<button class="btn btn-primary ' . Str::slug(strip_tags($user->name)), $html);
        self::assertStringContainsString('</button>', $html);
    }

    /** @test */
    public function it_can_set_button_with_collection_value(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromCollection(collect([['name' => 'Name test']]))->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary']);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(['btn', 'btn-primary'], $table->getColumns()->first()->getButtonClasses());
        self::assertStringContainsString('<button class="btn btn-primary name-test', $html);
        self::assertStringContainsString('</button>', $html);
    }

    /** @test */
    public function it_cant_set_button_with_no_model_value(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary']);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString('<button class="btn btn-primary', $html);
        self::assertStringNotContainsString('</button>', $html);
    }

    /** @test */
    public function it_cant_set_button_with_no_collection_value(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromCollection(collect([['name' => null]]))->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary']);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString('<button class="btn btn-primary', $html);
        self::assertStringNotContainsString('</button>', $html);
    }

    /** @test */
    public function it_can_set_button_with_no_model_value_but_prepended_html(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary'])->prependHtml('html', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<button class="btn btn-primary', $html);
        self::assertStringContainsString('</button>', $html);
    }

    /** @test */
    public function it_can_set_button_with_no_collection_value_but_prepended_html(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromCollection(collect([['name' => null]]))->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary'])->prependHtml('html', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<button class="btn btn-primary', $html);
        self::assertStringContainsString('</button>', $html);
    }

    /** @test */
    public function it_can_set_button_with_no_model_value_but_appended_html(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary'])->appendHtml('icon', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<button class="btn btn-primary', $html);
        self::assertStringContainsString('</button>', $html);
    }

    /** @test */
    public function it_can_set_button_with_no_collection_value_but_appended_html(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromCollection(collect([['name' => null]]))->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary'])->appendHtml('icon', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<button class="btn btn-primary', $html);
        self::assertStringContainsString('</button>', $html);
    }

    /** @test */
    public function it_can_set_button_with_custom_value(): void
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column()->button(['button-class'])->value(fn(array $row) => 'user name = ' . $row['name']);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(
            '<button class="button-class user-name-' . Str::slug(strip_tags($user->name)) . '">',
            $html
        );
        self::assertStringContainsString('</button>', $html);
    }
}
