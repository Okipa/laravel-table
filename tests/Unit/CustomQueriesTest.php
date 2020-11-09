<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Http\Request;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\Company;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class CustomQueriesTest extends LaravelTableTestCase
{
    public function testSetAddQueryInstructionsAttribute(): void
    {
        $additionalQueriesClosure = fn ($query) => $query->select('users.*')->where('users.activated');
        $table = (new Table())->query($additionalQueriesClosure);
        self::assertEquals($additionalQueriesClosure, $table->getAdditionalQueriesClosure());
    }
}
