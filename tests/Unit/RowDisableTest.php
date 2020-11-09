<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class RowDisableTest extends LaravelTableTestCase
{
    public function testRowDisableAttribute(): void
    {
        $rowDisableClosure = fn(User $user) => $user->id === 1;
        $classes = ['test-disabled-custom-class'];
        $table = (new Table())->disableRows($rowDisableClosure, $classes);
        self::assertEquals($rowDisableClosure, $table->getDisabledRows()->first()['closure']);
        self::assertEquals($classes, $table->getDisabledRows()->first()['classes']);
    }

    public function testDisableLineWithDefaultClassHtml(): void
    {
        $this->routes(['users'], ['index', 'create', 'edit', 'destroy']);
        $users = $this->createMultipleUsers(5);
        $closure = fn(User $user) => $user->id === 1 || $user->id === 2;
        $classes = ['test-disabled-default-class'];
        config()->set('laravel-table.classes.disabled', $classes);
        $table = (new Table())->model(User::class)
            ->routes([
                'index' => ['name' => 'users.index'],
                'create' => ['name' => 'users.create'],
                'edit' => ['name' => 'users.edit'],
                'destroy' => ['name' => 'users.destroy'],
            ])
            ->disableRows($closure);
        $table->column('name');
        $table->column('email');
        $table->configure();
        foreach ($table->getPaginator()->getCollection() as $user) {
            $closure($user)
                ? self::assertEquals($user->disabled_classes, $classes)
                : self::assertEmpty($user->disabled_classes);
        }
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(implode(' ', $classes), $html);
        foreach ($users as $user) {
            if ($user->id === 1 || $user->id === 2) {
                self::assertStringNotContainsString('edit-' . $user->id, $html);
                self::assertStringNotContainsString(
                    'href="http://localhost/users/edit?' . $user->id . '"',
                    $html
                );
            } else {
                self::assertStringContainsString('edit-' . $user->id, $html);
                self::assertStringContainsString(
                    'href="http://localhost/users/edit?' . $user->id . '"',
                    $html
                );
            }
        }
    }

    public function testDisableLineWithCustomClassHtml(): void
    {
        $this->routes(['users'], ['index', 'create', 'edit', 'destroy']);
        $users = $this->createMultipleUsers(5);
        $closure = fn(User $user) => $user->id === 1 || $user->id === 2;
        $classes = ['test-disabled-custom-class'];
        $table = (new Table())->model(User::class)
            ->routes([
                'index' => ['name' => 'users.index'],
                'create' => ['name' => 'users.create'],
                'edit' => ['name' => 'users.edit'],
                'destroy' => ['name' => 'users.destroy'],
            ])
            ->disableRows($closure, $classes);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email');
        $table->configure();
        foreach ($table->getPaginator()->getCollection() as $user) {
            $closure($user)
                ? self::assertEquals($user->disabled_classes, $classes)
                : self::assertEmpty($user->disabled_classes);
        }
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(implode(' ', $classes), $html);
        foreach ($users as $user) {
            if ($user->id === 1 || $user->id === 2) {
                self::assertStringNotContainsString('edit-' . $user->id, $html);
                self::assertStringNotContainsString(
                    'href="http://localhost/users/edit?' . $user->id . '"',
                    $html
                );
            } else {
                self::assertStringContainsString('edit-' . $user->id, $html);
                self::assertStringContainsString('href="http://localhost/users/edit?' . $user->id . '"', $html);
            }
        }
    }

    public function testWithNoDisableLinesHtml(): void
    {
        $classes = ['test-disabled-default-class'];
        config()->set('laravel-table.behavior.disabled_line.class', $classes);
        $this->routes(['users'], ['index', 'create', 'edit', 'destroy']);
        $this->createMultipleUsers(5);
        $table = (new Table())->model(User::class)
            ->routes([
                'index' => ['name' => 'users.index'],
                'create' => ['name' => 'users.create'],
                'edit' => ['name' => 'users.edit'],
                'destroy' => ['name' => 'users.destroy'],
            ]);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString(implode(' ', $classes), $html);
        self::assertStringNotContainsString('disabled="disabled"', $html);
    }
}
