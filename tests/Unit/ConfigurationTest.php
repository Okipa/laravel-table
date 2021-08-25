<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Http\Request;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ConfigurationTest extends LaravelTableTestCase
{
    /** @test */
    public function it_can_set_configured_to_true_when_calling_to_html_method(): void
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $this->assertFalse($table->hasBeenConfigured());
        $table->toHtml();
        self::assertTrue($table->hasBeenConfigured());
    }

    /** @test */
    public function it_can_set_configured_to_true_when_calling_configure_method(): void
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $this->assertFalse($table->hasBeenConfigured());
        $table->configure();
        self::assertTrue($table->hasBeenConfigured());
    }

    /** @test */
    public function it_can_set_execute_configuration_twice(): void
    {
        $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->configure();
        $table->column('email');
        $table->toHtml();
        self::assertEquals('name', $table->getColumns()->first()->getDataSourceField());
        $this->assertNotEquals('email', $table->getColumns()->first()->getDataSourceField());
    }
}
