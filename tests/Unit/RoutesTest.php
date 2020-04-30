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
        $table->configure();
    }

    public function testSetRoutesSuccess()
    {
        $routes = [
            'index' => ['name' => 'users.index'],
            'create' => ['name' => 'users.create'],
            'edit' => ['name' => 'users.edit'],
            'destroy' => ['name' => 'users.destroy'],
            'show' => ['name' => 'users.show'],
        ];
        $table = (new Table)->routes($routes);
        $this->assertEquals($routes, $table->getRoutes());
    }

    public function testSetRoutesWithMissingIndex()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The required « index » route key is missing. Use the « routes() » '
            . 'method to declare it.');
        (new Table)->routes([
            'create' => ['name' => 'users.create'],
            'edit' => ['name' => 'users.edit'],
            'destroy' => ['name' => 'users.destroy'],
        ]);
    }

    public function testSetRoutesWithWrongStructure()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The « name » key is missing from the « create » route definition. Each route '
            . 'definition should follow this structure: '
            . '["index" => ["name" => "news.index"]. Fix your routes declaration in the '
            . '« routes() » method.');
        (new Table)->routes([
            'index' => ['name' => 'users.index'],
            'create' => ['test' => 'test'],
        ]);
    }

    public function testSetRoutesWithNotAllowedKey()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The « activate » key is not an authorized route key '
            . '(index, create, edit, destroy, show). Fix your routes declaration in the '
            . '« routes() » method.');
        (new Table)->routes([
            'index' => ['name' => 'users.index'],
            'activate' => ['name' => 'users.activate'],
        ]);
    }

    public function testGetRouteSuccess()
    {
        $this->routes(['users'], ['index']);
        $table = (new Table)->routes([
            'index' => ['name' => 'users.index'],
            'create' => ['name' => 'users.create'],
            'edit' => ['name' => 'users.edit'],
            'destroy' => ['name' => 'users.destroy'],
            'show' => ['name' => 'show.destroy'],
        ]);
        $this->assertEquals('http://localhost/users/index', $table->getRoute('index'));
    }

    public function testGetRouteOnNotExistingRoute()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid « $routeKey » argument for the « route() » method. The route key '
            . '« create » has not been found in the routes stack.');
        $routes = ['index' => ['name' => 'users.index']];
        $table = (new Table)->routes($routes);
        $table->getRoute('create');
    }

    public function testGetRouteWithNoDeclaredRouteStack()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid « $routeKey » argument for the « route() » method. The route key '
            . '« update » has not been found in the routes stack.');
        (new Table)->getRoute('update');
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
            'index' => ['name' => 'users.index'],
            'create' => ['name' => 'users.create'],
        ])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTfootTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('creation-container', $html);
        $this->assertStringContainsString('href="http://localhost/users/create"', $html);
        $this->assertStringContainsString('title="Create"', $html);
    }

    public function testSetNoCreateRouteHtml()
    {
        $this->routes(['users'], ['index', 'create']);
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTfootTemplatePath(), compact('table'))->toHtml();
        $this->assertStringNotContainsString('<div class="creation-container', $html);
        $this->assertStringNotContainsString('href="http://localhost/users/create"', $html);
        $this->assertStringNotContainsString('title="Add"', $html);
    }

    public function testSetEditRouteHtml()
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'edit']);
        $table = (new Table)->routes([
            'index' => ['name' => 'users.index'],
            'edit' => ['name' => 'users.edit'],
        ])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        foreach ($users as $user) {
            $this->assertStringContainsString('edit-' . $user->id, $html);
            $this->assertStringContainsString('action="http://localhost/users/edit?' . $user->id . '"', $html);
        }
    }

    public function testSetNoEditRouteHtml()
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'edit']);
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        foreach ($users as $user) {
            $this->assertStringNotContainsString('<form class="edit-' . $user->id, $html);
            $this->assertStringNotContainsString('action="http://localhost/users/edit?' . $user->id . '"', $html);
        }
    }

    public function testSetDestroyRouteHtml()
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'destroy']);
        $table = (new Table)->routes([
            'index' => ['name' => 'users.index'],
            'destroy' => ['name' => 'users.destroy'],
        ])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        foreach ($users as $user) {
            $this->assertStringContainsString('destroy-' . $user->id, $html);
            $this->assertStringContainsString('action="http://localhost/users/destroy?' . $user->id . '"', $html);
        }
    }

    public function testSetNoDestroyRouteHtml()
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'destroy']);
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        foreach ($users as $user) {
            $this->assertStringNotContainsString('<form class="destroy-' . $user->id, $html);
            $this->assertStringNotContainsString('action="http://localhost/users/destroy?' . $user->id . '"', $html);
        }
    }

    public function testSetShowRouteHtml()
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'show']);
        $table = (new Table)->routes([
            'index' => ['name' => 'users.index'],
            'show' => ['name' => 'users.show'],
        ])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        foreach ($users as $user) {
            $this->assertStringContainsString('show-' . $user->id, $html);
            $this->assertStringContainsString('action="http://localhost/users/show?' . $user->id . '"', $html);
        }
    }

    public function testSetNoShowRouteHtml()
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'show']);
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        foreach ($users as $user) {
            $this->assertStringNotContainsString('<form class="show-' . $user->id, $html);
            $this->assertStringNotContainsString('action="http://localhost/users/show?' . $user->id . '"', $html);
        }
    }

    public function testSetRouteDefinitionWithProvidedId()
    {
        $user = $this->createUniqueUser();
        app('router')->get('/users', [
            'as' => 'users.index', function () {
                return null;
            },
        ]);
        app('router')->get('/user/edit/{id}', [
            'as' => 'user.edit', function () {
                return null;
            },
        ]);
        app('router')->post('/user/destroy/{id}', [
            'as' => 'user.destroy', function () {
                return null;
            },
        ]);
        app('router')->get('/user/show/{id}', [
            'as' => 'user.show', function () {
                return null;
            },
        ]);
        $table = (new Table)->routes([
            'index' => ['name' => 'users.index'],
            'edit' => ['name' => 'user.edit'],
            'destroy' => ['name' => 'user.destroy'],
            'show' => ['name' => 'user.show'],
        ])->model(User::class);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('action="http://localhost/user/edit/' . $user->id . '"', $html);
        $this->assertStringContainsString('action="http://localhost/user/destroy/' . $user->id . '"', $html);
        $this->assertStringContainsString('action="http://localhost/user/show/' . $user->id . '"', $html);
    }

    public function testSetImplicitBindingRoutes()
    {
        $user = $this->createUniqueUser();
        app('router')->get('/users', [
            'as' => 'users.index', function () {
                return null;
            },
        ]);
        app('router')->get('/user/edit/{user}', [
            'as' => 'user.edit', function () {
                return null;
            },
        ]);
        app('router')->post('/user/destroy/{user}', [
            'as' => 'user.destroy', function () {
                return null;
            },
        ]);
        app('router')->get('/user/show/{user}', [
            'as' => 'user.show', function () {
                return null;
            },
        ]);
        $table = (new Table)->routes([
            'index' => ['name' => 'users.index'],
            'edit' => ['name' => 'user.edit'],
            'destroy' => ['name' => 'user.destroy'],
            'show' => ['name' => 'user.show'],
        ])->model(User::class);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('action="http://localhost/user/edit/' . $user->id . '"', $html);
        $this->assertStringContainsString('action="http://localhost/user/destroy/' . $user->id . '"', $html);
        $this->assertStringContainsString('action="http://localhost/user/show/' . $user->id . '"', $html);
    }

    public function testSetRouteDefinitionWithProvidedIdAndOtherRouteParams()
    {
        $user = $this->createUniqueUser();
        app('router')->get('/users', [
            'as' => 'users.index', function () {
                return null;
            },
        ]);
        app('router')->get('/parent/{parentId}/user/edit/{id}/child/{childId}', [
            'as' => 'user.edit', function () {
                return null;
            },
        ]);
        app('router')->post('/parent/{parentId}/user/destroy/{id}/child/{childId}', [
            'as' => 'user.destroy', function () {
                return null;
            },
        ]);
        app('router')->get('/parent/{parentId}/user/show/{id}/child/{childId}', [
            'as' => 'user.show', function () {
                return null;
            },
        ]);
        $table = (new Table)->routes([
            'index' => ['name' => 'users.index'],
            'edit' => ['name' => 'user.edit', 'params' => ['parentId' => 11, 'childId' => 33]],
            'destroy' => ['name' => 'user.destroy', 'params' => ['parentId' => 11, 'childId' => 33]],
            'show' => ['name' => 'user.show', 'params' => ['parentId' => 11, 'childId' => 33]],
        ])->model(User::class);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString(
            'action="http://localhost/parent/11/user/edit/' . $user->id . '/child/33"',
            $html
        );
        $this->assertStringContainsString(
            'action="http://localhost/parent/11/user/destroy/' . $user->id . '/child/33',
            $html
        );
        $this->assertStringContainsString(
            'action="http://localhost/parent/11/user/show/' . $user->id . '/child/33',
            $html
        );
    }

    public function testSetImplicitBindingRoutesWithOtherRouteParams()
    {
        $user = $this->createUniqueUser();
        app('router')->get('/users', [
            'as' => 'users.index', function () {
                return null;
            },
        ]);
        app('router')->get('/parent/{parent}/user/edit/{user}/child/{child}', [
            'as' => 'user.edit', function () {
                return null;
            },
        ]);
        app('router')->post('/parent/{parent}/user/destroy/{user}/child/{child}', [
            'as' => 'user.destroy', function () {
                return null;
            },
        ]);
        app('router')->get('/parent/{parent}/user/show/{user}/child/{child}', [
            'as' => 'user.show', function () {
                return null;
            },
        ]);
        $table = (new Table)->routes([
            'index' => ['name' => 'users.index'],
            'edit' => ['name' => 'user.edit', 'params' => ['parent' => 11, 'child' => 33]],
            'destroy' => ['name' => 'user.destroy', 'params' => ['parent' => 11, 'child' => 33]],
            'show' => ['name' => 'user.show', 'params' => ['parent' => 11, 'child' => 33]],
        ])->model(User::class);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString(
            'action="http://localhost/parent/11/user/edit/' . $user->id . '/child/33"',
            $html
        );
        $this->assertStringContainsString(
            'action="http://localhost/parent/11/user/destroy/' . $user->id . '/child/33',
            $html
        );
        $this->assertStringContainsString(
            'action="http://localhost/parent/11/user/show/' . $user->id . '/child/33',
            $html
        );
    }
}
