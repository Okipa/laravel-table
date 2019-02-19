<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\Company;
use Okipa\LaravelTable\Test\Models\User;

class ResultsTest extends LaravelTableTestCase
{
    public function testSetResultsAttribute()
    {
        $closure = function($displayedList) {
            $displayedList->test = 'hello';
        };
        $table = (new Table)->model(User::class);
        $table->column()->result($closure);
        $this->assertEquals($closure, $table->columns->first()->resultClosures->first());
    }

    public function testSetResultsHtml()
    {
        $this->createMultipleUsers(10);
        $companies = $this->createMultipleCompanies(5);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('turnover')->result(function($displayedList) {
            return $displayedList->sum('turnover');
        });
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertEquals([null, $companies->sum('turnover')], $table->results->first()->toArray());
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
        $table->column('turnover')->result(function($displayedList) {
            return $displayedList->sum('turnover');
        })->result(function() {
            return (new Company)->all()->sum('turnover');
        });
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        $this->assertContains((string) $table->list->getCollection()->sum('turnover'), $html);
        $this->assertContains((string) $companies->sum('turnover'), $html);
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
        $this->assertNotContains('results', $html);
        $this->assertNotContains('result', $html);
    }
}
