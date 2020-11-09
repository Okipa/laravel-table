<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Support\Str;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ButtonTest extends LaravelTableTestCase
{
    public function testSetButtonAttribute(): void
    {
        $table = (new Table())->model(User::class);
        $table->column('name')->button(['buttonClass']);
        self::assertEquals(['buttonClass'], $table->getColumns()->first()->getButtonClasses());
    }

    public function testIsButtonHtml(): void
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary']);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<button class="btn btn-primary', $html);
        self::assertStringContainsString('</button>', $html);
    }

    public function testIsButtonWithNoValueHtml(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary']);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString('<button class="btn btn-primary', $html);
        self::assertStringNotContainsString('</button>', $html);
    }

    public function testIsButtonWithNoValueWithPrependedHtml(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary'])->prependHtml('html', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<button class="btn btn-primary', $html);
        self::assertStringContainsString('</button>', $html);
    }

    public function testIsButtonWithNoValueWithAppendedHtml(): void
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary'])->appendsHtml('icon', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<button class="btn btn-primary', $html);
        self::assertStringContainsString('</button>', $html);
    }

    public function testIsButtonWithCustomValueHtml(): void
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column()->button(['buttonClass'])->value(fn(User $user) => 'user name = ' . $user->name);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(
            '<button class="buttonClass user-name-' . Str::slug(strip_tags($user->name)) . '">',
            $html
        );
        self::assertStringContainsString('</button>', $html);
    }
}
