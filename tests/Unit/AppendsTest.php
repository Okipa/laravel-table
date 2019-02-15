<?php

namespace Okipa\LaravelTable\Tests\Unit;

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

    public function testSetAppendedToRequest()
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
        $this->assertContains('test=testValue', $html);
    }

    public function testSetAppendedToFiltersHtml()
    {
        $this->createMultipleUsers(20);
        $this->routes(['users'], ['index']);
        $appended = [
            'test'  => 'testValue',
            'array' => ['value1', 'value2'],
        ];
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(10)
            ->appends($appended);
        $table->column('name')->searchable();
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertEquals(2, substr_count($html, '<input type="hidden" name="test" value="testValue">'));
        $this->assertEquals(2, substr_count($html, '<input type="hidden" name="array[0]" value="value1">'));
        $this->assertEquals(2, substr_count($html, '<input type="hidden" name="array[1]" value="value2">'));
    }
}
