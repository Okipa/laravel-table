<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ButtonTest extends LaravelTableTestCase
{
    public function testSetButtonAttribute()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->button(['buttonClass']);
        $this->assertEquals(['buttonClass'], $table->columns->first()->buttonClasses);
    }

    public function testIsButtonHtml()
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary']);
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertStringContainsString('<button class="btn btn-primary', $html);
        $this->assertStringContainsString('</button>', $html);
    }

    public function testIsButtonWithNoValueHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary']);
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertStringNotContainsString('<button class="btn btn-primary', $html);
        $this->assertStringNotContainsString('</button>', $html);
    }

    public function testIsButtonWithNoValueWithIconHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary'])->icon('icon', true);
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertStringContainsString('<button class="btn btn-primary', $html);
        $this->assertStringContainsString('</button>', $html);
    }

    public function testIsButtonWithCustomValueHtml()
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column()->button(['buttonClass'])->value(function ($model) {
            return 'user name = ' . $model->name;
        });
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertStringContainsString(
            '<button class="buttonClass user-name-' . str_slug(strip_tags($user->name)) . '">',
            $html
        );
        $this->assertStringContainsString('</button>', $html);
    }
}
