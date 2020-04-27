<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Facades\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class FacadeTest extends LaravelTableTestCase
{
    public function testFacade()
    {
        $this->assertEquals((new \Okipa\LaravelTable\Table)->model(User::class), Table::model(User::class));
    }
}
