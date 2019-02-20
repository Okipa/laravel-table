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
        $this->assertEquals($templatePath, $table->tableComponentPath);
    }

    public function testSetTheadTemplateAttribute()
    {
        $templatePath = 'thead-test';
        $table = (new Table)->model(User::class)->theadTemplate($templatePath);
        $this->assertEquals($templatePath, $table->theadComponentPath);
    }

    public function testSetTbodyTemplateAttribute()
    {
        $templatePath = 'tbody-test';
        $table = (new Table)->model(User::class)->tbodyTemplate($templatePath);
        $this->assertEquals($templatePath, $table->tbodyComponentPath);
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
        $html = view('laravel-table::' . $table->tableComponentPath, compact('table'))->render();
        $this->assertContains('<table id="table-test">', $html);
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
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertContains('<thead id="thead-test">', $html);
    }

    public function testSetTbodyTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->theadTemplate('tbody-test');
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertContains('<tbody id="tbody-test">', $html);
    }

    public function testSetResultsTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->theadTemplate('results-test');
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertContains('<tr id="results-test"><td></td></tr>', $html);
    }

    public function testSetTfootTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->theadTemplate('tfoot-test');
        $table->column('name');
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertContains('<tfoot id="tfoot-test">', $html);
    }
}
