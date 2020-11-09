<?php

namespace Okipa\LaravelTable\Tests\Unit;

use ErrorException;
use InvalidArgumentException;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class RoutesTest extends LaravelTableTestCase
{
    public function testNoDeclaredRoutes(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The required « index » route key is missing. Use the « routes() » method to '
            . 'declare it.');
        $table = (new Table())->model(User::class);
        $table->configure();
    }

    public function testSetRoutesSuccess(): void
    {
        $routes = [
            'index' => ['name' => 'users.index'],
            'create' => ['name' => 'users.create'],
            'edit' => ['name' => 'users.edit'],
            'destroy' => ['name' => 'users.destroy'],
            'show' => ['name' => 'users.show'],
        ];
        $table = (new Table())->routes($routes);
        self::assertEquals($routes, $table->getRoutes());
    }

    public function testSetRoutesWithMissingIndex(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The required « index » route key is missing. Use the « routes() » '
            . 'method to declare it.');
        (new Table())->routes([
            'create' => ['name' => 'users.create'],
            'edit' => ['name' => 'users.edit'],
            'destroy' => ['name' => 'users.destroy'],
        ]);
    }

    public function testSetRoutesWithWrongStructure(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The « name » key is missing from the « create » route definition. Each route '
            . 'definition should follow this structure: '
            . '["index" => ["name" => "news.index"]. Fix your routes declaration in the '
            . '« routes() » method.');
        (new Table())->routes([
            'index' => ['name' => 'users.index'],
            'create' => ['test' => 'test'],
        ]);
    }

    public function testSetRoutesWithNotAllowedKey(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The « activate » key is not an authorized route key '
            . '(index, create, edit, destroy, show). Fix your routes declaration in the '
            . '« routes() » method.');
        (new Table())->routes([
            'index' => ['name' => 'users.index'],
            'activate' => ['name' => 'users.activate'],
        ]);
    }

    public function testGetRouteSuccess(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->routes([
            'index' => ['name' => 'users.index'],
            'create' => ['name' => 'users.create'],
            'edit' => ['name' => 'users.edit'],
            'destroy' => ['name' => 'users.destroy'],
            'show' => ['name' => 'show.destroy'],
        ]);
        self::assertEquals('http://localhost/users/index', $table->getRoute('index'));
    }

    public function testGetRouteOnNotExistingRoute(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid « $routeKey » argument for the « route() » method. The route key '
            . '« create » has not been found in the routes stack.');
        $routes = ['index' => ['name' => 'users.index']];
        $table = (new Table())->routes($routes);
        $table->getRoute('create');
    }

    public function testGetRouteWithNoDeclaredRouteStack(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid « $routeKey » argument for the « route() » method. The route key '
            . '« update » has not been found in the routes stack.');
        (new Table())->getRoute('update');
    }

    public function testIsRouteDefined(): void
    {
        $table = (new Table())->routes(['index' => ['name' => 'users.index']]);
        self::assertTrue($table->isRouteDefined('index'));
        $this->assertFalse($table->isRouteDefined('update'));
    }

    public function testSetCreateRouteHtml(): void
    {
        $this->routes(['users'], ['index', 'create']);
        $table = (new Table())->routes([
            'index' => ['name' => 'users.index'],
            'create' => ['name' => 'users.create'],
        ])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('create-action', $html);
        self::assertStringContainsString('href="http://localhost/users/create"', $html);
        self::assertStringContainsString('title="Create"', $html);
    }

    public function testSetCreateRouteWillShowCreateActionEventIfSearchingAndRowsNumberDefinitionAreDisabledHtml(): void
    {
        $this->routes(['users'], ['index', 'create']);
        $table = (new Table())->routes([
            'index' => ['name' => 'users.index'],
            'create' => ['name' => 'users.create'],
        ])->model(User::class)->activateRowsNumberDefinition(false);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('create-action', $html);
        self::assertStringContainsString('href="http://localhost/users/create"', $html);
        self::assertStringContainsString('title="Create"', $html);
    }

    public function testSetNoCreateRouteHtml(): void
    {
        $this->routes(['users'], ['index', 'create']);
        $table = (new Table())->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTfootTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString('<div class="create-action', $html);
        self::assertStringNotContainsString('href="http://localhost/users/create"', $html);
        self::assertStringNotContainsString('title="Add"', $html);
    }

    public function testSetEditRouteHtml(): void
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'edit']);
        $table = (new Table())->routes([
            'index' => ['name' => 'users.index'],
            'edit' => ['name' => 'users.edit'],
        ])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        foreach ($users as $user) {
            self::assertStringContainsString('edit-' . $user->id, $html);
            self::assertStringContainsString('href="http://localhost/users/edit?' . $user->id . '"', $html);
        }
    }

    public function testSetNoEditRouteHtml(): void
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'edit']);
        $table = (new Table())->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        foreach ($users as $user) {
            self::assertStringNotContainsString('<form class="edit-' . $user->id, $html);
            self::assertStringNotContainsString('href="http://localhost/users/edit?' . $user->id . '"', $html);
        }
    }

    public function testSetDestroyRouteHtml(): void
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'destroy']);
        $table = (new Table())->routes([
            'index' => ['name' => 'users.index'],
            'destroy' => ['name' => 'users.destroy'],
        ])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        foreach ($users as $user) {
            self::assertStringContainsString('destroy-' . $user->id, $html);
            self::assertStringContainsString('action="http://localhost/users/destroy?' . $user->id . '"', $html);
        }
    }

    public function testSetNoDestroyRouteHtml(): void
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'destroy']);
        $table = (new Table())->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        foreach ($users as $user) {
            self::assertStringNotContainsString('<form class="destroy-' . $user->id, $html);
            self::assertStringNotContainsString('action="http://localhost/users/destroy?' . $user->id . '"', $html);
        }
    }

    public function testSetShowRouteHtml(): void
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'show']);
        $table = (new Table())->routes([
            'index' => ['name' => 'users.index'],
            'show' => ['name' => 'users.show'],
        ])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        foreach ($users as $user) {
            self::assertStringContainsString('show-' . $user->id, $html);
            self::assertStringContainsString('href="http://localhost/users/show?' . $user->id . '"', $html);
        }
    }

    public function testSetNoShowRouteHtml(): void
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index', 'show']);
        $table = (new Table())->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        foreach ($users as $user) {
            self::assertStringNotContainsString('<form class="show-' . $user->id, $html);
            self::assertStringNotContainsString('href="http://localhost/users/show?' . $user->id . '"', $html);
        }
    }

    public function testSetRouteDefinitionWithProvidedId(): void
    {
        $user = $this->createUniqueUser();
        app('router')->get('/users', ['as' => 'users.index', fn() => null]);
        app('router')->get('/user/edit/{id}', ['as' => 'user.edit', fn() => null]);
        app('router')->post('/user/destroy/{id}', ['as' => 'user.destroy', fn() => null]);
        app('router')->get('/user/show/{id}', ['as' => 'user.show', fn() => null]);
        $table = (new Table())->routes([
            'index' => ['name' => 'users.index'],
            'edit' => ['name' => 'user.edit'],
            'destroy' => ['name' => 'user.destroy'],
            'show' => ['name' => 'user.show'],
        ])->model(User::class);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('href="http://localhost/user/show/' . $user->id . '"', $html);
        self::assertStringContainsString('href="http://localhost/user/edit/' . $user->id . '"', $html);
        self::assertStringContainsString('action="http://localhost/user/destroy/' . $user->id . '"', $html);
    }

    public function testSetImplicitBindingRoutes(): void
    {
        $user = $this->createUniqueUser();
        app('router')->get('/users', ['as' => 'users.index', fn() => null]);
        app('router')->get('/user/edit/{user}', ['as' => 'user.edit', fn() => null]);
        app('router')->post('/user/destroy/{user}', ['as' => 'user.destroy', fn() => null]);
        app('router')->get('/user/show/{user}', ['as' => 'user.show', fn() => null]);
        $table = (new Table())->routes([
            'index' => ['name' => 'users.index'],
            'edit' => ['name' => 'user.edit'],
            'destroy' => ['name' => 'user.destroy'],
            'show' => ['name' => 'user.show'],
        ])->model(User::class);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('href="http://localhost/user/show/' . $user->id . '"', $html);
        self::assertStringContainsString('href="http://localhost/user/edit/' . $user->id . '"', $html);
        self::assertStringContainsString('action="http://localhost/user/destroy/' . $user->id . '"', $html);
    }

    public function testSetRouteDefinitionWithProvidedIdAndOtherRouteParams(): void
    {
        $user = $this->createUniqueUser();
        app('router')->get('/users', ['as' => 'users.index', fn() => null]);
        app('router')->get('/parent/{parentId}/user/edit/{id}/child/{childId}', ['as' => 'user.edit', fn() => null]);
        app('router')->post('/parent/{parentId}/user/destroy/{id}/child/{childId}', [
            'as' => 'user.destroy', fn() => null
        ]);
        app('router')->get('/parent/{parentId}/user/show/{id}/child/{childId}', ['as' => 'user.show', fn() => null]);
        $table = (new Table())->routes([
            'index' => ['name' => 'users.index'],
            'edit' => ['name' => 'user.edit', 'params' => ['parentId' => 11, 'childId' => 33]],
            'destroy' => ['name' => 'user.destroy', 'params' => ['parentId' => 11, 'childId' => 33]],
            'show' => ['name' => 'user.show', 'params' => ['parentId' => 11, 'childId' => 33]],
        ])->model(User::class);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(
            'href="http://localhost/parent/11/user/edit/' . $user->id . '/child/33"',
            $html
        );
        self::assertStringContainsString(
            'action="http://localhost/parent/11/user/destroy/' . $user->id . '/child/33',
            $html
        );
        self::assertStringContainsString(
            'href="http://localhost/parent/11/user/show/' . $user->id . '/child/33',
            $html
        );
    }

    public function testSetImplicitBindingRoutesWithOtherRouteParams(): void
    {
        $user = $this->createUniqueUser();
        app('router')->get('/users', ['as' => 'users.index', fn() => null]);
        app('router')->get('/parent/{parent}/user/edit/{user}/child/{child}', ['as' => 'user.edit', fn() => null]);
        app('router')->post('/parent/{parent}/user/destroy/{user}/child/{child}', [
            'as' => 'user.destroy', fn () => null
        ]);
        app('router')->get('/parent/{parent}/user/show/{user}/child/{child}', ['as' => 'user.show', fn() => null]);
        $table = (new Table())->routes([
            'index' => ['name' => 'users.index'],
            'edit' => ['name' => 'user.edit', 'params' => ['parent' => 11, 'child' => 33]],
            'destroy' => ['name' => 'user.destroy', 'params' => ['parent' => 11, 'child' => 33]],
            'show' => ['name' => 'user.show', 'params' => ['parent' => 11, 'child' => 33]],
        ])->model(User::class);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(
            'href="http://localhost/parent/11/user/edit/' . $user->id . '/child/33"',
            $html
        );
        self::assertStringContainsString(
            'action="http://localhost/parent/11/user/destroy/' . $user->id . '/child/33',
            $html
        );
        self::assertStringContainsString(
            'href="http://localhost/parent/11/user/show/' . $user->id . '/child/33',
            $html
        );
    }
}
