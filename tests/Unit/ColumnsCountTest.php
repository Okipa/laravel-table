<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class ColumnsCountTest extends LaravelTableTestCase
{
    public function testGetColumnsCount(): void
    {
        $table = (new Table())->fromModel(User::class);
        $table->column('id');
        $table->column('name');
        $table->column('email');
        self::assertEquals(3, $table->getColumnsCount());
    }

    public function testGetColumnsCountWithEditRouteDefined(): void
    {
        $this->routes(['users'], ['index', 'edit']);
        $table = (new Table())->fromModel(User::class)->routes([
            'index' => ['name' => 'users.index'],
            'edit'  => ['name' => 'users.edit'],
        ]);
        $table->column('id');
        $table->column('name');
        $table->column('email');
        self::assertEquals(4, $table->getColumnsCount());
    }
}
