<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class TemplatesCustomizationsTest extends LaravelTableTestCase
{
    public function testSetTableTemplateAttribute()
    {
        $templatePath = 'table-test';
        $table = (new Table())->model(User::class)->tableTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getTableTemplatePath());
    }

    public function testSetTheadTemplateAttribute()
    {
        $templatePath = 'thead-test';
        $table = (new Table())->model(User::class)->theadTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getTheadTemplatePath());
    }

    public function testSetRowsSearchingTemplateAttribute()
    {
        $templatePath = 'rows-searching-test';
        $table = (new Table())->model(User::class)->rowsSearchingTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getRowsSearchingTemplatePath());
    }

    public function testSetrowsNumberDefinitionTemplateAttribute()
    {
        $templatePath = 'rows-number-definition-test';
        $table = (new Table())->model(User::class)->rowsNumberDefinitionTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getrowsNumberDefinitionTemplatePath());
    }

    public function testSetCreateTemplateAttribute()
    {
        $templatePath = 'create-action-test';
        $table = (new Table())->model(User::class)->createActionTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getCreateActionTemplatePath());
    }

    public function testSetColumnTitlesTemplateAttribute()
    {
        $templatePath = 'column-titles-test';
        $table = (new Table())->model(User::class)->columnTitlesTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getColumnTitlesTemplatePath());
    }

    public function testSetTbodyTemplateAttribute()
    {
        $templatePath = 'tbody-test';
        $table = (new Table())->model(User::class)->tbodyTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getTbodyTemplatePath());
    }

    public function testSetShowActionTemplateAttribute()
    {
        $templatePath = 'show-action-test';
        $table = (new Table())->model(User::class)->showActionTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getShowActionTemplatePath());
    }

    public function testSetEditActionTemplateAttribute()
    {
        $templatePath = 'edit-action-test';
        $table = (new Table())->model(User::class)->editActionTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getEditActionTemplatePath());
    }

    public function testSetDestroyActionTemplateAttribute()
    {
        $templatePath = 'destroy-action-test';
        $table = (new Table())->model(User::class)->destroyActionTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getDestroyActionTemplatePath());
    }

    public function testSetResultsTemplateAttribute()
    {
        $templatePath = 'results-test';
        $table = (new Table())->model(User::class)->resultsTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getResultsTemplatePath());
    }

    public function testSetTfootTemplateAttribute()
    {
        $templatePath = 'tfoot-test';
        $table = (new Table())->model(User::class)->tfootTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getTfootTemplatePath());
    }

    public function testSetNavigationStatusTemplateAttribute()
    {
        $templatePath = 'navigation-status-test';
        $table = (new Table())->model(User::class)->navigationStatusTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getNavigationStatusTemplatePath());
    }

    public function testSetPaginationTemplateAttribute()
    {
        $templatePath = 'pagination-test';
        $table = (new Table())->model(User::class)->paginationTemplate($templatePath);
        $this->assertEquals($templatePath, $table->getPaginationTemplatePath());
    }

    public function testSetTableTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tableTemplate('table-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<table id="table-test">', $html);
    }

    public function testSetTheadTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->theadTemplate('thead-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<thead id="thead-test">', $html);
    }

    public function testSetRowsSearchingTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsSearchingTemplate('rows-searching-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getRowsSearchingTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<form id="rows-searching-test"></form>', $html);
    }

    public function testSetrowsNumberDefinitionTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumberDefinitionTemplate('rows-number-definition-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getrowsNumberDefinitionTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<form id="rows-number-definition-test"></form>', $html);
    }

    public function testSetCreateTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->createActionTemplate('create-action-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getCreateActionTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<form id="create-action-test"></form>', $html);
    }

    public function testSetColumnTitlesTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->columnTitlesTemplate('column-titles-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getColumnTitlesTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<tr id="column-titles-test"></tr>', $html);
    }

    public function testSetTbodyTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tbodyTemplate('tbody-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<tbody id="tbody-test">', $html);
    }

    public function testSetShowActionTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->showActionTemplate('show-action-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getShowActionTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<form id="show-action-test">', $html);
    }

    public function testSetEditActionTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->editActionTemplate('edit-action-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getEditActionTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<form id="edit-action-test">', $html);
    }

    public function testSetDestroyActionTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->destroyActionTemplate('destroy-action-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getDestroyActionTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<form id="destroy-action-test">', $html);
    }

    public function testSetResultsTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->resultsTemplate('results-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getResultsTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<tr id="results-test"><td></td></tr>', $html);
    }

    public function testSetTfootTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tfootTemplate('tfoot-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTfootTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<tfoot id="tfoot-test">', $html);
    }

    public function testSetNavigationStatusTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->navigationStatusTemplate('navigation-status-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getNavigationStatusTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<div id="navigation-status-test"></div>', $html);
    }

    public function testSetPaginationTemplateHtml()
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->paginationTemplate('pagination-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getPaginationTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('<nav id="pagination-test"></nav>', $html);
    }
}
