<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class TemplatesCustomizationTest extends LaravelTableTestCase
{
    public function testSetTableTemplateAttribute()
    {
        $templatePath = 'table-test';
        $table = (new Table)->model(User::class)->tableTemplate($templatePath);
        $this->assertEquals($templatePath, $table->tableTemplatePath);
    }

    public function testSetTheadTemplateAttribute()
    {
        $templatePath = 'thead-test';
        $table = (new Table)->model(User::class)->theadTemplate($templatePath);
        $this->assertEquals($templatePath, $table->theadTemplatePath);
    }

    public function testSetTbodyTemplateAttribute()
    {
        $templatePath = 'tbody-test';
        $table = (new Table)->model(User::class)->tbodyTemplate($templatePath);
        $this->assertEquals($templatePath, $table->tbodyTemplatePath);
    }

    public function testSetShowTemplateAttribute()
    {
        $templatePath = 'show-test';
        $table = (new Table)->model(User::class)->showTemplate($templatePath);
        $this->assertEquals($templatePath, $table->showTemplatePath);
    }

    public function testSetEditTemplateAttribute()
    {
        $templatePath = 'edit-test';
        $table = (new Table)->model(User::class)->editTemplate($templatePath);
        $this->assertEquals($templatePath, $table->editTemplatePath);
    }

    public function testSetDestroyTemplateAttribute()
    {
        $templatePath = 'edit-test';
        $table = (new Table)->model(User::class)->destroyTemplate($templatePath);
        $this->assertEquals($templatePath, $table->destroyTemplatePath);
    }

    public function testSetResultsTemplateAttribute()
    {
        $templatePath = 'results-test';
        $table = (new Table)->model(User::class)->resultsTemplate($templatePath);
        $this->assertEquals($templatePath, $table->resultsComponentPath);
    }

    public function testSetTfootTemplateAttribute()
    {
        $templatePath = 'tfoot-test';
        $table = (new Table)->model(User::class)->tfootTemplate($templatePath);
        $this->assertEquals($templatePath, $table->tfootComponentPath);
    }

    public function testSetTableTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tableTemplate('table-test');
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->tableTemplatePath, compact('table'))->render();
        $this->assertStringContainsString('<table id="table-test">', $html);
    }

    public function testSetTheadTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->theadTemplate('thead-test');
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->theadTemplatePath, compact('table'))->render();
        $this->assertStringContainsString('<thead id="thead-test">', $html);
    }

    public function testSetTbodyTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tbodyTemplate('tbody-test');
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->render();
        $this->assertStringContainsString('<tbody id="tbody-test">', $html);
    }

    public function testSetShowTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->showTemplate('show-test');
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->showTemplatePath, compact('table'))->render();
        $this->assertStringContainsString('<form id="show-test">', $html);
    }

    public function testSetEditTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->editTemplate('edit-test');
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->editTemplatePath, compact('table'))->render();
        $this->assertStringContainsString('<form id="edit-test">', $html);
    }

    public function testSetDestroyTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->destroyTemplate('destroy-test');
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->destroyTemplatePath, compact('table'))->render();
        $this->assertStringContainsString('<form id="destroy-test">', $html);
    }

    public function testSetResultsTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->resultsTemplate('results-test');
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->resultsComponentPath, compact('table'))->render();
        $this->assertStringContainsString('<tr id="results-test"><td></td></tr>', $html);
    }

    public function testSetTfootTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tfootTemplate('tfoot-test');
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->tfootComponentPath, compact('table'))->render();
        $this->assertStringContainsString('<tfoot id="tfoot-test">', $html);
    }
}
