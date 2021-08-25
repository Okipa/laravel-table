<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class ColumnsCountTest extends LaravelTableTestCase
{
    /** @test */
    public function it_can_get_columns_count(): void
    {
        $table = (new Table())->fromModel(User::class);
        $table->column('id');
        $table->column('name');
        $table->column('email');
        self::assertEquals(3, $table->getColumnsCount());
    }

    /** @test */
    public function it_can_get_columns_count_with_interaction_routes_defined(): void
    {
        $this->routes(['users'], ['index', 'edit', 'destroy', 'show']);
        $table = (new Table())->fromModel(User::class)->routes([
            'index' => ['name' => 'users.index'],
            'edit'  => ['name' => 'users.edit'],
            'destroy'  => ['name' => 'users.destroy'],
            'show'  => ['name' => 'users.show'],
        ]);
        $table->column('id');
        $table->column('name');
        $table->column('email');
        self::assertEquals(4, $table->getColumnsCount());
    }
}
