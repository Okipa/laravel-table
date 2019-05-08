<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ClassesDefinitionTest extends LaravelTableTestCase
{
    public function testContainerClassesAttribute()
    {
        $classes = ['test-custom-class'];
        $table = (new Table)->model(User::class)->containerClasses($classes);
        $table->column();
        $this->assertEquals($classes, $table->containerClasses);
    }

    public function testTableClassesAttribute()
    {
        $classes = ['test-custom-class'];
        $table = (new Table)->model(User::class)->tableClasses($classes);
        $table->column();
        $this->assertEquals($classes, $table->tableClasses);
    }

    public function testTrClassesAttribute()
    {
        $classes = ['test-custom-class'];
        $table = (new Table)->model(User::class)->trClasses($classes);
        $table->column();
        $this->assertEquals($classes, $table->trClasses);
    }

    public function testThClassesAttribute()
    {
        $classes = ['test-custom-class'];
        $table = (new Table)->model(User::class)->thClasses($classes);
        $table->column();
        $this->assertEquals($classes, $table->thClasses);
    }
    
    public function testTdClassesAttribute()
    {
        $classes = ['test-custom-class'];
        $table = (new Table)->model(User::class)->tdClasses($classes);
        $table->column();
        $this->assertEquals($classes, $table->tdClasses);
    }

    public function testColumnClassesAttribute()
    {
        $classes = ['test-custom-class'];
        $table = (new Table)->model(User::class);
        $table->column()->classes($classes);
        $this->assertEquals($classes, $table->columns->first()->classes);
    }

    public function testResultClassesAttribute()
    {
        $classes = ['test-custom-class'];
        $table = (new Table)->model(User::class);
        $table->result()->classes($classes);
        $this->assertEquals($classes, $table->results->first()->classes);
    }

    public function testRowConditionalClassesAttribute()
    {
        $closure = function ($model) {
            return $model->id === 1;
        };
        $classes = ['test-custom-class'];
        $table = (new Table)->rowsConditionalClasses($closure, $classes);
        $this->assertEquals($closure, $table->rowsConditionalClasses->first()['closure']);
        $this->assertEquals($classes, $table->rowsConditionalClasses->first()['classes']);
    }

    public function testContainerClassesHtml()
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->containerClasses($classes);
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->tableComponentPath, compact('table'))->render();
        $this->assertStringContainsString('<div class="table-container ' . implode(' ', $classes) . '">', $html);
    }

    public function testTableClassesHtml()
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tableClasses($classes);
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->tableComponentPath, compact('table'))->render();
        $this->assertStringContainsString('<table class="table ' . implode(' ', $classes) . '">', $html);
    }

    public function testTrClassesHtml()
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->trClasses($classes);
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->tableComponentPath, compact('table'))->render();
        $this->assertEquals(substr_count($html, '<tr '), substr_count($html, implode(' ', $classes)));
    }

    public function testThClassesHtml()
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->thClasses($classes);
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->tableComponentPath, compact('table'))->render();
        $this->assertEquals(substr_count($html, '<th '), substr_count($html, implode(' ', $classes)));
    }

    public function testTdClassesHtml()
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tdClasses($classes);
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->tableComponentPath, compact('table'))->render();
        $this->assertEquals(substr_count($html, '<td '), substr_count($html, implode(' ', $classes)));
    }

    public function testColumnClassesHtml()
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->classes($classes);
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertEquals(2, substr_count($html, implode(' ', $classes)));
    }

    public function testResultClassesHtml()
    {
        $this->createMultipleUsers(2);
        $classes = ['test-custom-class'];
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('email');
        $table->result()->classes($classes);
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertEquals(1, substr_count($html, implode(' ', $classes)));
    }

    public function testRowConditionalClassesHtml()
    {
        $this->routes(['users'], ['index', 'create', 'edit', 'destroy']);
        $users = $this->createMultipleUsers(5);
        $closure = function ($model) use ($users) {
            return $model->id === 1 || $model->id === 2;
        };
        $classes = ['test-row-custom-class-1', 'test-row-custom-class-2'];
        $table = (new Table)->model(User::class)->routes([
            'index'   => ['name' => 'users.index'],
            'create'  => ['name' => 'users.create'],
            'edit'    => ['name' => 'users.edit'],
            'destroy' => ['name' => 'users.destroy'],
        ])->rowsConditionalClasses($closure, $classes);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email');
        $table->render();
        foreach ($table->list->getCollection() as $user) {
            $closure($user)
                ? $this->assertEquals($user->conditionnalClasses, $classes)
                : $this->assertEmpty($user->conditionnalClasses);
        }
        $html = view('laravel-table::' . $table->tableComponentPath, compact('table'))->render();
        $this->assertStringContainsString(implode(' ', $classes), $html);
        $this->assertEquals(2, substr_count($html, implode(' ', $classes)));
    }
}
