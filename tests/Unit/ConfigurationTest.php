<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Http\Request;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ConfigurationTest extends LaravelTableTestCase
{
    public function testToHtmlTriggersConfiguration()
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $this->assertFalse($table->hasBeenConfigured());
        $table->toHtml();
        $this->assertTrue($table->hasBeenConfigured());
    }

    public function testConfigureTriggersConfiguration()
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $this->assertFalse($table->hasBeenConfigured());
        $table->configure();
        $this->assertTrue($table->hasBeenConfigured());
    }

    public function testConfigurationIsNotExecutedTwice()
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->configure();
        $table->column('email');
        $table->toHtml();
        $this->assertEquals('name', $table->getColumns()->first()->getDbField());
        $this->assertNotEquals('email', $table->getColumns()->first()->getDbField());
    }
}
