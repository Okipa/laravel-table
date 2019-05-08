<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Support\Str;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class StringLimitTest extends LaravelTableTestCase
{
    public function testSetStringLimitAttribute()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->stringLimit(10);
        $this->assertEquals(10, $table->columns->first()->stringLimit);
    }

    public function testSetStringLimitHtml()
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email')->stringLimit(2);
        $html = $table->render();
        $this->assertStringContainsString(Str::limit($user->email, 2), $html);
    }
}
