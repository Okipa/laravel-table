<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Http\Request;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\Company;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class SetCustomTableTest extends LaravelTableTestCase
{
    public function testSetAddQueryInstructionsAttribute()
    {
        $queryClosure = function ($query) {
            $query->select('users.*')->where('users.activated');
        };
        $table = (new Table)->query($queryClosure);
        $this->assertEquals($queryClosure, $table->queryClosure);
    }
}
