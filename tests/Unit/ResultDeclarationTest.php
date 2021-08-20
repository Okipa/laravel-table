<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Support\Collection;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\Company;
use Okipa\LaravelTable\Test\Models\User;

class ResultDeclarationTest extends LaravelTableTestCase
{
    public function testSetResultsAttribute(): void
    {
        $table = (new Table())->fromModel(User::class);
        $table->result()->title('Test');
        self::assertEquals($table->getResults()->count(), 1);
        self::assertEquals($table->getResults()->first()->getTitle(), 'Test');
    }

    public function testResultRowsGivePaginatedRowsToManipulate(): void
    {
        $this->createMultipleUsers(10);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(5);
        $table->column('name');
        $table->result()->title('Test')->html(fn(Collection $paginatedRows) => $this->assertCount(5, $paginatedRows));
        $table->configure();
        view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
    }

    public function testSetResultsHtml(): void
    {
        $this->createMultipleUsers(10);
        $companies = $this->createMultipleCompanies(5);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(Company::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('turnover');
        $table->result()->title('Result !')->html(fn(Collection $paginatedRows) => $paginatedRows->sum('turnover'));
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('Result !', $html);
        self::assertStringContainsString((string) $companies->sum('turnover'), $html);
    }

    public function testSetResultsMultipleHtml(): void
    {
        $this->createMultipleUsers(10);
        $companies = $this->createMultipleCompanies(5);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(Company::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(2);
        $table->column('name');
        $table->column('turnover');
        $table->result()
            ->title('Selected turnover')
            ->html(fn(Collection $paginatedRows) => $paginatedRows->sum('turnover'));
        $table->result()->title('Total turnover')->html(fn() => (new Company())->all()->sum('turnover'));
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('Selected turnover', $html);
        self::assertStringContainsString((string) $companies->sum('turnover'), $html);
        self::assertStringContainsString('Total turnover', $html);
    }

    public function testSetNoResult(): void
    {
        $this->createMultipleUsers(10);
        $this->createMultipleCompanies(5);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(Company::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(2);
        $table->column('name');
        $table->column('turnover');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString('result', $html);
    }

    public function testResultColspanWithSingleColumn(): void
    {
        $this->createMultipleUsers(10);
        $this->createMultipleCompanies(5);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(Company::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->result()
            ->title('Selected turnover')
            ->html(fn(Collection $paginatedRows) => $paginatedRows->sum('turnover'));
        $table->configure();
        $html = view('laravel-table::' . $table->getResultsTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(1, substr_count($html, '<td'));
        self::assertStringNotContainsString('colspan', $html);
    }

    public function testResultColspanWithMultipleColumns(): void
    {
        $this->createMultipleUsers(10);
        $this->createMultipleCompanies(5);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(Company::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('turnover');
        $table->result()
            ->title('Selected turnover')
            ->html(fn(Collection $paginatedRows) => $paginatedRows->sum('turnover'));
        $table->configure();
        $html = view('laravel-table::' . $table->getResultsTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(1, substr_count($html, '<td'));
        self::assertStringContainsString('colspan="2"', $html);
    }

    public function testResultColspanTestWithEditRoute(): void
    {
        $this->createMultipleUsers(10);
        $this->createMultipleCompanies(5);
        $this->routes(['users'], ['index', 'edit']);
        $table = (new Table())->fromModel(Company::class)->routes([
            'index' => ['name' => 'users.index'],
            'edit' => ['name' => 'users.edit'],
        ]);
        $table->column('owner_id');
        $table->column('name');
        $table->column('turnover');
        $table->result()
            ->title('Selected turnover')
            ->html(fn(Collection $paginatedRows) => $paginatedRows->sum('turnover'));
        $table->configure();
        $html = view('laravel-table::' . $table->getResultsTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(1, substr_count($html, '<td'));
        self::assertStringContainsString('colspan="4"', $html);
    }
}
