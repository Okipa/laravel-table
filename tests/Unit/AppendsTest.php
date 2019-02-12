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
        $this->assertEquals($appended, $table->appendsToPagination);
    }

    public function testSetAppendsHtml()
    {
        $this->createMultipleUsers(20);
        $this->routes(['users'], ['index']);
        $appended = ['test' => 'testValue'];
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']])->rowsNumber(10)->appends($appended);
        $table->column('name');
        $table->render();
        $html = $table->list->links()->toHtml();
        $this->assertContains('test=testValue', $html);
    }
}
