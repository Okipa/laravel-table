<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class CustomHtmlElementTest extends LaravelTableTestCase
{
    public function testHtmlAttribute()
    {
        $table = (new Table)->model(User::class);
        $closure = function ($model, $column) {
        };
        $table->column('name')->html($closure);
        $this->assertEquals($closure, $table->columns->first()->htmlClosure);
    }
}
