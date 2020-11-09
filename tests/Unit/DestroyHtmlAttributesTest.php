<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class DestroyHtmlAttributesTest extends LaravelTableTestCase
{
    public function testSetDestroyConfirmationClosure(): void
    {
        $this->createMultipleUsers(5);
        $this->routes(['users'], ['index']);
        $closure = fn(User $user) => [
            'data-confirm' => __('Are you sure you want to delete the user :name ?', ['name' => $user->name]),
        ];
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->destroyConfirmationHtmlAttributes($closure);
        $table->column('name');
        $table->configure();
        self::assertEquals($closure, $table->getDestroyConfirmationClosure());
        foreach ($table->getPaginator() as $model) {
            self::assertEquals([
                'data-confirm' => __('Are you sure you want to delete the user :name ?', [
                    'name' => $model->name,
                ]),
            ], $model->destroy_confirmation_attributes);
        }
    }

    public function testSetDestroyConfirmationClosureHtml(): void
    {
        $closure = fn(User $user) => [
            'data-confirm' => __('Are you sure you want to delete the user :name ?', ['name' => $user->name]),
        ];
        $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'destroy']);
        $table = (new Table())->model(User::class)
            ->routes([
                'index' => ['name' => 'users.index'],
                'destroy' => ['name' => 'users.destroy'],
            ])
            ->destroyConfirmationHtmlAttributes($closure);
        $table->column('name');
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(5, substr_count($html, 'data-confirm'));
        foreach ($table->getPaginator() as $model) {
            self::assertStringContainsString(__('Are you sure you want to delete the user :name ?', [
                'name' => $model->name,
            ]), $html);
        }
    }
}
