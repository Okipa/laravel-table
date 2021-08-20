<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class HtmlTest extends LaravelTableTestCase
{
    public function testHtmlAttribute(): void
    {
        $table = (new Table())->fromModel(User::class);
        $closure = fn(User $user) => null;
        $table->column('name')->html($closure);
        self::assertEquals($closure, $table->getColumns()->first()->getCustomHtmlClosure());
    }
}
