<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Http\Request;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class AppendsTest extends LaravelTableTestCase
{
    public function testSetAppendsAttribute()
    {
        $appended = ['test' => 'testValue'];
        $table = (new Table)->model(User::class)->appends($appended);
        $table->column();
        $this->assertEquals($appended, $table->appendedValues);
    }

    public function testSetAppendedToPaginationLink()
    {
        $this->createMultipleUsers(20);
        $this->routes(['users'], ['index']);
        $appended = ['test' => 'testValue'];
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(10)
            ->appends($appended);
        $table->column('name');
        $table->render();
        $html = $table->list->links()->toHtml();
        $this->assertStringContainsString('test=testValue', $html);
    }

    public function testSetAppendedToRequest()
    {
        $this->createMultipleUsers(20);
        $this->routes(['users'], ['index']);
        $appended = ['test' => 'testValue', 'array' => ['value1', 'value2']];
        $customRequest = (new Request)->merge([
            (new Table)->rowsField    => 20,
            (new Table)->searchField  => 'test',
            (new Table)->sortByField  => 'name',
            (new Table)->sortDirField => 'desc',
        ]);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(10)
            ->request($customRequest)
            ->appends($appended);
        $table->column('name')->sortable()->searchable();
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $sortingHttpArguments = htmlspecialchars(http_build_query(array_merge([
            $table->rowsField    => 20,
            $table->searchField  => 'test',
            $table->sortByField  => 'name',
            $table->sortDirField => 'asc',
        ], $appended)));
        $searchCancelingHttpArguments = htmlspecialchars(http_build_query(array_merge([
            $table->rowsField    => 20,
            $table->searchField  => null,
            $table->sortByField  => 'name',
            $table->sortDirField => 'desc',
        ], $appended)));
        $this->assertEquals(1, substr_count($html, $sortingHttpArguments));
        $this->assertEquals(1, substr_count($html, $searchCancelingHttpArguments));
        $this->assertEquals(2, substr_count($html, '<input type="hidden" name="test" value="testValue">'));
        $this->assertEquals(2, substr_count($html, '<input type="hidden" name="array[0]" value="value1">'));
        $this->assertEquals(2, substr_count($html, '<input type="hidden" name="array[1]" value="value2">'));
    }
}
