<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Support\Str;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class IdentifierTest extends LaravelTableTestCase
{
    public function testSetIdentifierAttribute(): void
    {
        $identifier = 'identifier test';
        $table = (new Table())->model(User::class)->identifier($identifier);
        self::assertEquals('identifier-test', $table->getIdentifier());
    }

    public function testRowsFieldWithIdentifierAttribute(): void
    {
        $identifier = 'identifier test';
        $table = (new Table())->model(User::class)->identifier($identifier);
        self::assertEquals('identifier_test_rows', $table->getRowsNumberField());
    }

    public function testSearchFieldWithIdentifierAttribute(): void
    {
        $identifier = 'identifier test';
        $table = (new Table())->model(User::class)->identifier($identifier);
        self::assertEquals('identifier_test_search', $table->getSearchField());
    }

    public function testSortByFieldWithIdentifierAttribute(): void
    {
        $identifier = 'identifier test';
        $table = (new Table())->model(User::class)->identifier($identifier);
        self::assertEquals('identifier_test_sort_by', $table->getSortByField());
    }

    public function testSortDirFieldWithIdentifierAttribute(): void
    {
        $identifier = 'identifier test';
        $table = (new Table())->model(User::class)->identifier($identifier);
        self::assertEquals('identifier_test_sort_dir', $table->getSortDirField());
    }

    public function testSetIdentifierHtml(): void
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $identifier = 'identifier test';
        $table = (new Table())->model(User::class)
            ->identifier($identifier)
            ->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->sortable()->searchable();
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(1, substr_count($html, '<table id="identifier-test"'));
    }

    public function testRowsFieldWithIdentifierHtml(): void
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $identifier = 'identifier test';
        $table = (new Table())->model(User::class)
            ->identifier($identifier)
            ->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->sortable()->searchable();
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(2, substr_count($html, 'name="identifier_test_rows"'));
    }

    public function testSearchFieldWithIdentifierHtml(): void
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $identifier = 'identifier test';
        $table = (new Table())->model(User::class)
            ->identifier($identifier)
            ->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->sortable()->searchable();
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(2, substr_count($html, 'name="identifier_test_search"'));
    }

    public function testSortByFieldWithIdentifierHtml(): void
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $identifier = 'identifier test';
        $table = (new Table())->model(User::class)
            ->identifier($identifier)
            ->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->sortable()->searchable();
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(2, substr_count($html, 'name="identifier_test_sort_by"'));
    }

    public function testSortDirFieldWithIdentifierHtml(): void
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $identifier = 'identifier test';
        $table = (new Table())->model(User::class)
            ->identifier($identifier)
            ->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->sortable()->searchable();
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(2, substr_count($html, 'name="identifier_test_sort_dir"'));
    }
}
