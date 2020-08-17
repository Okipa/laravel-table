<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Http\Request;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\Company;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class CustomQueriesTest extends LaravelTableTestCase
{
    public function testSetAddQueryInstructionsAttribute()
    {
        $additionalQueriesClosure = fn ($query) => $query->select('users.*')->where('users.activated');
        $table = (new Table)->query($additionalQueriesClosure);
        $this->assertEquals($additionalQueriesClosure, $table->getAdditionalQueriesClosure());
    }
}
