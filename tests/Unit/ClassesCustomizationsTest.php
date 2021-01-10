<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ClassesCustomizationsTest extends LaravelTableTestCase
{
    public function testContainerClassesAttribute(): void
    {
        $classes = ['test-custom-class'];
        $table = (new Table())->model(User::class)->containerClasses($classes);
        $table->column();
        self::assertEquals($classes, $table->getContainerClasses());
    }

    public function testTableClassesAttribute(): void
    {
        $classes = ['test-custom-class'];
        $table = (new Table())->model(User::class)->tableClasses($classes);
        $table->column();
        self::assertEquals($classes, $table->getTableClasses());
    }

    public function testTrClassesAttribute(): void
    {
        $classes = ['test-custom-class'];
        $table = (new Table())->model(User::class)->trClasses($classes);
        $table->column();
        self::assertEquals($classes, $table->getTrClasses());
    }

    public function testThClassesAttribute(): void
    {
        $classes = ['test-custom-class'];
        $table = (new Table())->model(User::class)->thClasses($classes);
        $table->column();
        self::assertEquals($classes, $table->getThClasses());
    }

    public function testTdClassesAttribute(): void
    {
        $classes = ['test-custom-class'];
        $table = (new Table())->model(User::class)->tdClasses($classes);
        $table->column();
        self::assertEquals($classes, $table->getTdClasses());
    }

    public function testColumnClassesAttribute(): void
    {
        $classes = ['test-custom-class'];
        $table = (new Table())->model(User::class);
        $table->column()->classes($classes);
        self::assertEquals($classes, $table->getColumns()->first()->getClasses());
    }

    public function testResultClassesAttribute(): void
    {
        $classes = ['test-custom-class'];
        $table = (new Table())->model(User::class);
        $table->result()->classes($classes);
        self::assertEquals($classes, $table->getResults()->first()->getClasses());
    }

    public function testRowConditionalClassesAttribute(): void
    {
        $table = (new Table())->rowsConditionalClasses(
            fn(User $user) => $user->id === 1,
            fn(User $user) => ['custom', 'class', $user->id]
        )->rowsConditionalClasses(fn(User $user) => $user->id === 2, ['custom', 'class', '2']);
        self::assertEquals(
            fn(User $user) => ['custom', 'class', $user->id],
            $table->getRowsConditionalClasses()->first()['conditions']
        );
        self::assertEquals(['custom', 'class', '2'], $table->getRowsConditionalClasses()->last()['classes']);
    }

    public function testContainerClassesHtml(): void
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->containerClasses($classes);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<div class="table-container ' . implode(' ', $classes) . '">', $html);
    }

    public function testTableClassesHtml(): void
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tableClasses($classes);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();

        self::assertStringContainsString('<table class="table ' . implode(' ', $classes) . '">', $html);
    }

    public function testTrClassesHtml(): void
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->trClasses($classes);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(substr_count($html, '<tr '), substr_count($html, implode(' ', $classes)));
    }

    public function testThClassesHtml(): void
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->thClasses($classes);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(substr_count($html, '<th '), substr_count($html, implode(' ', $classes)));
    }

    public function testTdClassesHtml(): void
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tdClasses($classes);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(substr_count($html, '<td '), substr_count($html, implode(' ', $classes)));
    }

    public function testColumnClassesHtml(): void
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->classes($classes);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(2, substr_count($html, implode(' ', $classes)));
    }

    public function testResultClassesHtml(): void
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('email');
        $table->result()->classes($classes);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(1, substr_count($html, implode(' ', $classes)));
    }

    public function testRowConditionalClassesHtml(): void
    {
        $this->routes(['users'], ['index', 'create', 'edit', 'destroy']);
        $this->createMultipleUsers(5);
        $table = (new Table())->model(User::class)
            ->routes([
                'index' => ['name' => 'users.index'],
                'create' => ['name' => 'users.create'],
                'edit' => ['name' => 'users.edit'],
                'destroy' => ['name' => 'users.destroy'],
            ])
            ->rowsConditionalClasses(
                fn($model) => $model->id === 1,
                fn(User $model) => ['custom', 'first-class', $model->id]
            )
            ->rowsConditionalClasses(fn($model) => $model->id === 1, ['custom', 'second-class', '1'])
            ->rowsConditionalClasses(fn($model) => $model->id === 2, ['custom', 'class', '2']);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email');
        $table->configure();
        foreach ($table->getPaginator()->getCollection() as $user) {
            if (! in_array($user->id, [1, 2], true)) {
                self::assertEmpty($user->conditionnal_classes);
                continue;
            }
            if ($user->id === 1) {
                self::assertEquals(
                    ['custom', 'first-class', 1, 'custom', 'second-class', '1'],
                    $user->conditionnal_classes
                );
                continue;
            }
            self::assertEquals(['custom', 'class', '2'], $user->conditionnal_classes);
        }
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('custom first-class 1 custom second-class 1', $html);
        self::assertStringContainsString('custom class 2', $html);
    }
}
