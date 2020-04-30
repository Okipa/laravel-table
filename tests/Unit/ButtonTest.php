<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Support\Str;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ButtonTest extends LaravelTableTestCase
{
    public function testSetButtonAttribute()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->button(['buttonClass']);
        $this->assertEquals(['buttonClass'], $table->getColumns()->first()->getButtonClasses());
    }

    public function testIsButtonHtml()
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary']);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
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
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringNotContainsString('<button class="btn btn-primary', $html);
        $this->assertStringNotContainsString('</button>', $html);
    }

    public function testIsButtonWithNoValueWithPrependedHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary'])->prependsHtml('html', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<button class="btn btn-primary', $html);
        $this->assertStringContainsString('</button>', $html);
    }

    public function testIsButtonWithNoValueWithAppendedHtml()
    {
        $user = $this->createUniqueUser();
        $user->update(['name' => null]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->button(['btn', 'btn-primary'])->appendsHtml('icon', true);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
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
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString(
            '<button class="buttonClass user-name-' . Str::slug(strip_tags($user->name)) . '">',
            $html
        );
        $this->assertStringContainsString('</button>', $html);
    }
}
