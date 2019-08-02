<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Support\Str;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class IdentifierTest extends LaravelTableTestCase
{
    public function testSetIdentifierAttribute()
    {
        $identifier = 'identifier test';
        $table = (new Table)->model(User::class)->identifier($identifier);
        $this->assertEquals('identifier-test', $table->identifier);
    }

    public function testRowsFieldWithIdentifierAttribute()
    {
        $identifier = 'identifier test';
        $table = (new Table)->model(User::class)->identifier($identifier);
        $this->assertEquals('identifier_test_rows', $table->rowsField);
    }

    public function testSearchFieldWithIdentifierAttribute()
    {
        $identifier = 'identifier test';
        $table = (new Table)->model(User::class)->identifier($identifier);
        $this->assertEquals('identifier_test_search', $table->searchField);
    }

    public function testSortByFieldWithIdentifierAttribute()
    {
        $identifier = 'identifier test';
        $table = (new Table)->model(User::class)->identifier($identifier);
        $this->assertEquals('identifier_test_sort_by', $table->sortByField);
    }

    public function testSortDirFieldWithIdentifierAttribute()
    {
        $identifier = 'identifier test';
        $table = (new Table)->model(User::class)->identifier($identifier);
        $this->assertEquals('identifier_test_sort_dir', $table->sortDirField);
    }

    public function testSetIdentifierHtml()
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $identifier = 'identifier test';
        $table = (new Table)->model(User::class)
            ->identifier($identifier)
            ->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->sortable()->searchable();
        $table->render();
        $html = view('laravel-table::' . $table->tableComponentPath, compact('table'))->render();
        $this->assertEquals(1, substr_count($html, '<table id="identifier-test"'));
    }

    public function testRowsFieldWithIdentifierHtml()
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $identifier = 'identifier test';
        $table = (new Table)->model(User::class)
            ->identifier($identifier)
            ->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->sortable()->searchable();
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertEquals(2, substr_count($html, 'name="identifier_test_rows"'));
    }

    public function testSearchFieldWithIdentifierHtml()
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $identifier = 'identifier test';
        $table = (new Table)->model(User::class)
            ->identifier($identifier)
            ->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->sortable()->searchable();
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertEquals(1, substr_count($html, 'name="identifier_test_search"'));
    }

    public function testSortByFieldWithIdentifierHtml()
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $identifier = 'identifier test';
        $table = (new Table)->model(User::class)
            ->identifier($identifier)
            ->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->sortable()->searchable();
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertEquals(2, substr_count($html, 'name="identifier_test_sort_by"'));
    }

    public function testSortDirFieldWithIdentifierHtml()
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $identifier = 'identifier test';
        $table = (new Table)->model(User::class)
            ->identifier($identifier)
            ->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->sortable()->searchable();
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertEquals(2, substr_count($html, 'name="identifier_test_sort_dir"'));
    }
}
