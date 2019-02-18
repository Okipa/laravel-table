<?php

namespace Okipa\LaravelTable\Tests\Unit;

use ErrorException;
use InvalidArgumentException;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class RoutesTest extends LaravelTableTestCase
{
    public function testNoDeclaredRoutes()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The required « index » route key is missing. Use the « routes() » method to '
                                      . 'declare it.');
        $table = (new Table)->model(User::class);
        $table->render();
    }

    public function testSetRoutesSuccess()
    {
        $routes = [
            'index'   => ['name' => 'users.index'],
            'create'  => ['name' => 'users.create'],
            'edit'    => ['name' => 'users.edit'],
            'destroy' => ['name' => 'users.destroy'],
        ];
        $table = (new Table)->routes($routes);
        $this->assertEquals($routes, $table->routes);
    }

    public function testSetRoutesWithMissingIndex()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The required « index » route key is missing. Use the « routes() » '
                                      . 'method to declare it.');
        (new Table)->routes([
            'create'  => ['name' => 'users.create'],
            'edit'    => ['name' => 'users.edit'],
            'destroy' => ['name' => 'users.destroy'],
        ]);
    }

    public function testSetRoutesWithWrongStructure()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The « name » key is missing from the « create » route definition. Each route '
                                      . 'definition should follow this structure : '
                                      . '["index" => ["name" => "news.index"]. Fix your routes declaration in the '
                                      . '« routes() » method.');
        (new Table)->routes([
            'index'  => ['name' => 'users.index'],
            'create' => ['test' => 'test'],
        ]);
    }

    public function testSetRoutesWithNotAllowedKey()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The « activate » key is not an authorized route key '
                                      . '(index, create, edit, destroy). Fix your routes declaration in the '
                                      . '« routes() » method.');
        (new Table)->routes([
            'index'    => ['name' => 'users.index'],
            'activate' => ['name' => 'users.activate'],
        ]);
    }

    public function testGetRouteSuccess()
    {
        $this->routes(['users'], ['index']);
        $table = (new Table)->routes([
            'index'   => ['name' => 'users.index'],
            'create'  => ['name' => 'users.create'],
            'edit'    => ['name' => 'users.edit'],
            'destroy' => ['name' => 'users.destroy'],
        ]);
        $this->assertEquals('http://localhost/users/index', $table->route('index'));
    }

    public function testGetRouteOnNotExistingRoute()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid « $routeKey » argument for the « route() » method. The route key '
                                      . '« create » has not been found in the routes stack.');
        $routes = ['index' => ['name' => 'users.index']];
        $table = (new Table)->routes($routes);
        $table->route('create');
    }

    public function testGetRouteWithNoDeclaredRouteStack()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid « $routeKey » argument for the « route() » method. The route key '
                                      . '« update » has not been found in the routes stack.');
        (new Table)->route('update');
    }

    public function testIsRouteDefined()
    {
        $table = (new Table)->routes(['index' => ['name' => 'users.index']]);
        $this->assertTrue($table->isRouteDefined('index'));
        $this->assertFalse($table->isRouteDefined('update'));
    }

    public function testSetCreateRouteHtml()
    {
        $this->routes(['users'], ['index', 'create']);
        $table = (new Table)->routes([
            'index'  => ['name' => 'users.index'],
            'create' => ['name' => 'users.create'],
        ])->model(User::class);
        $table->column('name')->title('Name');
        $table->render();
        $html = view('laravel-table::' . $table->tfootComponentPath, compact('table'))->render();
        $this->assertContains('creation-container', $html);
        $this->assertContains('href="http://localhost/users/create"', $html);
        $this->assertContains('title="Create"', $html);
    }

    public function testSetNoCreateRouteHtml()
    {
        $this->routes(['users'], ['index', 'create']);
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->render();
        $html = view('laravel-table::' . $table->tfootComponentPath, compact('table'))->render();
        $this->assertNotContains('<div class="creation-container', $html);
        $this->assertNotContains('href="http://localhost/users/create"', $html);
        $this->assertNotContains('title="Add"', $html);
    }

    public function testSetEditRouteHtml()
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'edit']);
        $table = (new Table)->routes([
            'index' => ['name' => 'users.index'],
            'edit'  => ['name' => 'users.edit'],
        ])->model(User::class);
        $table->column('name')->title('Name');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        foreach ($users as $user) {
            $this->assertContains('edit-' . $user->id, $html);
            $this->assertContains('action="http://localhost/users/edit?id=' . $user->id . '"', $html);
        }
    }

    public function testSetNoEditRouteHtml()
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'edit']);
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        foreach ($users as $user) {
            $this->assertNotContains('<form class="edit-' . $user->id, $html);
            $this->assertNotContains('action="http://localhost/users/edit?id=' . $user->id . '"', $html);
        }
    }

    public function testSetDestroyRouteHtml()
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'destroy']);
        $table = (new Table)->routes([
            'index'   => ['name' => 'users.index'],
            'destroy' => ['name' => 'users.destroy'],
        ])->model(User::class);
        $table->column('name')->title('Name');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        foreach ($users as $user) {
            $this->assertContains('destroy-' . $user->id, $html);
            $this->assertContains('action="http://localhost/users/destroy?id=' . $user->id . '"', $html);
        }
    }

    public function testSetNoDestroyRouteHtml()
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'destroy']);
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        foreach ($users as $user) {
            $this->assertNotContains('<form class="destroy-' . $user->id, $html);
            $this->assertNotContains('action="http://localhost/users/destroy?id=' . $user->id . '"', $html);
        }
    }
}
