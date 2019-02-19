<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\Company;
use Okipa\LaravelTable\Test\Models\User;

class ResultDeclarationTest extends LaravelTableTestCase
{
    public function testSetResultsAttribute()
    {
        $table = (new Table)->model(User::class);
        $table->result()->title('Test');
        $this->assertEquals($table->results->count(), 1);
        $this->assertEquals($table->results->first()->title, 'Test');
    }

    public function testSetResultsHtml()
    {
        $this->createMultipleUsers(10);
        $companies = $this->createMultipleCompanies(5);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(Company::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('turnover');
        $table->result()->title('Result !')->html(function ($displayedList) {
            return $displayedList->sum('turnover');
        });
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertContains('Result !', $html);
        $this->assertContains((string) $companies->sum('turnover'), $html);
    }

    public function testSetResultsMultipleHtml()
    {
        $this->createMultipleUsers(10);
        $companies = $this->createMultipleCompanies(5);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(2);
        $table->column('name');
        $table->column('turnover');
        $table->result()->title('Selected turnover')->html(function ($displayedList) {
            return $displayedList->sum('turnover');
        });
        $table->result()->title('Total turnover')->html(function () {
            return (new Company)->all()->sum('turnover');
        });
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertContains('Selected turnover', $html);
        $this->assertContains((string) $companies->sum('turnover'), $html);
        $this->assertContains('Total turnover', $html);
    }

    public function testSetNoResult()
    {
        $this->createMultipleUsers(10);
        $this->createMultipleCompanies(5);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(2);
        $table->column('name');
        $table->column('turnover');
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertNotContains('result', $html);
    }
}
