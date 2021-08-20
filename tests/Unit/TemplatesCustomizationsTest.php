<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class TemplatesCustomizationsTest extends LaravelTableTestCase
{
    public function testSetTableTemplateAttribute(): void
    {
        $templatePath = 'table-test';
        $table = (new Table())->fromModel(User::class)->tableTemplate($templatePath);
        self::assertEquals($templatePath, $table->getTableTemplatePath());
    }

    public function testSetTheadTemplateAttribute(): void
    {
        $templatePath = 'thead-test';
        $table = (new Table())->fromModel(User::class)->theadTemplate($templatePath);
        self::assertEquals($templatePath, $table->getTheadTemplatePath());
    }

    public function testSetRowsSearchingTemplateAttribute(): void
    {
        $templatePath = 'rows-searching-test';
        $table = (new Table())->fromModel(User::class)->rowsSearchingTemplate($templatePath);
        self::assertEquals($templatePath, $table->getRowsSearchingTemplatePath());
    }

    public function testSetRowsNumberDefinitionTemplateAttribute(): void
    {
        $templatePath = 'rows-number-definition-test';
        $table = (new Table())->fromModel(User::class)->rowsNumberDefinitionTemplate($templatePath);
        self::assertEquals($templatePath, $table->getRowsNumberDefinitionTemplatePath());
    }

    public function testSetCreateTemplateAttribute(): void
    {
        $templatePath = 'create-action-test';
        $table = (new Table())->fromModel(User::class)->createActionTemplate($templatePath);
        self::assertEquals($templatePath, $table->getCreateActionTemplatePath());
    }

    public function testSetColumnTitlesTemplateAttribute(): void
    {
        $templatePath = 'column-titles-test';
        $table = (new Table())->fromModel(User::class)->columnTitlesTemplate($templatePath);
        self::assertEquals($templatePath, $table->getColumnTitlesTemplatePath());
    }

    public function testSetTbodyTemplateAttribute(): void
    {
        $templatePath = 'tbody-test';
        $table = (new Table())->fromModel(User::class)->tbodyTemplate($templatePath);
        self::assertEquals($templatePath, $table->getTbodyTemplatePath());
    }

    public function testSetShowActionTemplateAttribute(): void
    {
        $templatePath = 'show-action-test';
        $table = (new Table())->fromModel(User::class)->showActionTemplate($templatePath);
        self::assertEquals($templatePath, $table->getShowActionTemplatePath());
    }

    public function testSetEditActionTemplateAttribute(): void
    {
        $templatePath = 'edit-action-test';
        $table = (new Table())->fromModel(User::class)->editActionTemplate($templatePath);
        self::assertEquals($templatePath, $table->getEditActionTemplatePath());
    }

    public function testSetDestroyActionTemplateAttribute(): void
    {
        $templatePath = 'destroy-action-test';
        $table = (new Table())->fromModel(User::class)->destroyActionTemplate($templatePath);
        self::assertEquals($templatePath, $table->getDestroyActionTemplatePath());
    }

    public function testSetResultsTemplateAttribute(): void
    {
        $templatePath = 'results-test';
        $table = (new Table())->fromModel(User::class)->resultsTemplate($templatePath);
        self::assertEquals($templatePath, $table->getResultsTemplatePath());
    }

    public function testSetTfootTemplateAttribute(): void
    {
        $templatePath = 'tfoot-test';
        $table = (new Table())->fromModel(User::class)->tfootTemplate($templatePath);
        self::assertEquals($templatePath, $table->getTfootTemplatePath());
    }

    public function testSetNavigationStatusTemplateAttribute(): void
    {
        $templatePath = 'navigation-status-test';
        $table = (new Table())->fromModel(User::class)->navigationStatusTemplate($templatePath);
        self::assertEquals($templatePath, $table->getNavigationStatusTemplatePath());
    }

    public function testSetPaginationTemplateAttribute(): void
    {
        $templatePath = 'pagination-test';
        $table = (new Table())->fromModel(User::class)->paginationTemplate($templatePath);
        self::assertEquals($templatePath, $table->getPaginationTemplatePath());
    }

    public function testSetTableTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tableTemplate('table-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<table id="table-test">', $html);
    }

    public function testSetTheadTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->theadTemplate('thead-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<thead id="thead-test">', $html);
    }

    public function testSetRowsSearchingTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsSearchingTemplate('rows-searching-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getRowsSearchingTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<form id="rows-searching-test"></form>', $html);
    }

    public function testSetRowsNumberDefinitionTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumberDefinitionTemplate('rows-number-definition-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getRowsNumberDefinitionTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<form id="rows-number-definition-test"></form>', $html);
    }

    public function testSetCreateTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->createActionTemplate('create-action-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getCreateActionTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<form id="create-action-test"></form>', $html);
    }

    public function testSetColumnTitlesTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->columnTitlesTemplate('column-titles-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getColumnTitlesTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<tr id="column-titles-test"></tr>', $html);
    }

    public function testSetTbodyTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tbodyTemplate('tbody-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<tbody id="tbody-test">', $html);
    }

    public function testSetShowActionTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->showActionTemplate('show-action-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getShowActionTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<form id="show-action-test">', $html);
    }

    public function testSetEditActionTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->editActionTemplate('edit-action-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getEditActionTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<form id="edit-action-test">', $html);
    }

    public function testSetDestroyActionTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->destroyActionTemplate('destroy-action-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getDestroyActionTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<form id="destroy-action-test">', $html);
    }

    public function testSetResultsTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->resultsTemplate('results-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getResultsTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<tr id="results-test"><td></td></tr>', $html);
    }

    public function testSetTfootTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tfootTemplate('tfoot-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTfootTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<tfoot id="tfoot-test">', $html);
    }

    public function testSetNavigationStatusTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->navigationStatusTemplate('navigation-status-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getNavigationStatusTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<div id="navigation-status-test"></div>', $html);
    }

    public function testSetPaginationTemplateHtml(): void
    {
        view()->addNamespace('laravel-table', 'tests/views');
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->paginationTemplate('pagination-test');
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getPaginationTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('<nav id="pagination-test"></nav>', $html);
    }
}
