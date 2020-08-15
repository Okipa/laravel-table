<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class RowDisableTest extends LaravelTableTestCase
{
    public function testRowDisableAttribute()
    {
        $rowDisableClosure = function ($model) {
            return $model->id === 1;
        };
        $classes = ['test-disabled-custom-class'];
        $table = (new Table)->disableRows($rowDisableClosure, $classes);
        $this->assertEquals($rowDisableClosure, $table->getDisabledRows()->first()['closure']);
        $this->assertEquals($classes, $table->getDisabledRows()->first()['classes']);
    }

    public function testDisableLineWithDefaultClassHtml()
    {
        $this->routes(['users'], ['index', 'create', 'edit', 'destroy']);
        $users = $this->createMultipleUsers(5);
        $closure = function ($model) use ($users) {
            return $model->id === 1 || $model->id === 2;
        };
        $classes = ['test-disabled-default-class'];
        config()->set('laravel-table.classes.disabled', $classes);
        $table = (new Table)->model(User::class)
            ->routes([
                'index'   => ['name' => 'users.index'],
                'create'  => ['name' => 'users.create'],
                'edit'    => ['name' => 'users.edit'],
                'destroy' => ['name' => 'users.destroy'],
            ])
            ->disableRows($closure);
        $table->column('name');
        $table->column('email');
        $table->configure();
        foreach ($table->getPaginator()->getCollection() as $user) {
            $closure($user)
                ? $this->assertEquals($user->disabled_classes, $classes)
                : $this->assertEmpty($user->disabled_classes);
        }
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString(implode(' ', $classes), $html);
        foreach ($users as $user) {
            if ($user->id === 1 || $user->id === 2) {
                $this->assertStringNotContainsString('edit-' . $user->id, $html);
                $this->assertStringNotContainsString(
                    'action="http://localhost/users/edit?' . $user->id . '"',
                    $html
                );
            } else {
                $this->assertStringContainsString('edit-' . $user->id, $html);
                $this->assertStringContainsString(
                    'action="http://localhost/users/edit?' . $user->id . '"',
                    $html
                );
            }
        }
    }

    public function testDisableLineWithCustomClassHtml()
    {
        $this->routes(['users'], ['index', 'create', 'edit', 'destroy']);
        $users = $this->createMultipleUsers(5);
        $closure = function ($model) use ($users) {
            return $model->id === 1 || $model->id === 2;
        };
        $classes = ['test-disabled-custom-class'];
        $table = (new Table)->model(User::class)
            ->routes([
                'index'   => ['name' => 'users.index'],
                'create'  => ['name' => 'users.create'],
                'edit'    => ['name' => 'users.edit'],
                'destroy' => ['name' => 'users.destroy'],
            ])
            ->disableRows($closure, $classes);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email');
        $table->configure();
        foreach ($table->getPaginator()->getCollection() as $user) {
            $closure($user)
                ? $this->assertEquals($user->disabled_classes, $classes)
                : $this->assertEmpty($user->disabled_classes);
        }
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString(implode(' ', $classes), $html);
        foreach ($users as $user) {
            if ($user->id === 1 || $user->id === 2) {
                $this->assertStringNotContainsString('edit-' . $user->id, $html);
                $this->assertStringNotContainsString(
                    'action="http://localhost/users/edit?' . $user->id . '"',
                    $html
                );
            } else {
                $this->assertStringContainsString('edit-' . $user->id, $html);
                $this->assertStringContainsString('action="http://localhost/users/edit?' . $user->id . '"', $html);
            }
        }
    }

    public function testWithNoDisableLinesHtml()
    {
        $classes = ['test-disabled-default-class'];
        config()->set('laravel-table.behavior.disabled_line.class', $classes);
        $this->routes(['users'], ['index', 'create', 'edit', 'destroy']);
        $this->createMultipleUsers(5);
        $table = (new Table)->model(User::class)
            ->routes([
                'index'   => ['name' => 'users.index'],
                'create'  => ['name' => 'users.create'],
                'edit'    => ['name' => 'users.edit'],
                'destroy' => ['name' => 'users.destroy'],
            ]);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringNotContainsString(implode(' ', $classes), $html);
        $this->assertStringNotContainsString('disabled="disabled"', $html);
    }
}
