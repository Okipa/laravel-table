<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class CustomValueTest extends LaravelTableTestCase
{
    public function testSetIsCustomValueAttribute()
    {
        $table = (new Table)->model(User::class);
        $closure = function ($model, $column) {
        };
        $table->column('name')->value($closure);
        $this->assertEquals($closure, $table->getColumns()->first()->getCustomValueClosure());
    }

    public function testIsCustomValueHtml()
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('updated_at')->value(function ($model) {
            return 'user name = ' . $model->name;
        });
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('user name = ' . $user->name, $html);
    }
}
