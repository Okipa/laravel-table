<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class DestroyHtmlAttributesTest extends LaravelTableTestCase
{
    public function testSetDestroyConfirmationClosure()
    {
        $this->createMultipleUsers(5);
        $this->routes(['users'], ['index']);
        $closure = function ($model) {
            return ['data-confirm' => __('Are you sure you want to delete the user :name ?', ['name' => $model->name])];
        };
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->destroyConfirmationHtmlAttributes($closure);
        $table->column('name');
        $table->render();
        $this->assertEquals($closure, $table->destroyConfirmationClosure);
        foreach ($table->list as $model) {
            $this->assertEquals([
                'data-confirm' => __('Are you sure you want to delete the user :name ?', [
                    'name' => $model->name,
                ]),
            ], $model->destroyConfirmationAttributes);
        }
    }

    public function testSetDestroyConfirmationClosureHtml()
    {
        $closure = function ($model) {
            return ['data-confirm' => __('Are you sure you want to delete the user :name ?', ['name' => $model->name])];
        };
        $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'destroy']);
        $table = (new Table)->model(User::class)
            ->routes([
                'index'   => ['name' => 'users.index'],
                'destroy' => ['name' => 'users.destroy'],
            ])
            ->destroyConfirmationHtmlAttributes($closure);
        $table->column('name');
        $table->column('email');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertEquals(5, substr_count($html, 'data-confirm'));
        foreach ($table->list as $model) {
            $this->assertStringContainsString(__('Are you sure you want to delete the user :name ?', [
                'name' => $model->name,
            ]), $html);
        }
    }
}
