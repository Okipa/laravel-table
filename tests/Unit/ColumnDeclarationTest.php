<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Exceptions\TableDataSourceNotDefined;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ColumnDeclarationTest extends LaravelTableTestCase
{
    /** @test */
    public function it_can_add_column_to_table_with_model_data_source(): void
    {
        $table = (new Table())->fromModel(User::class);
        $table->column('name');
        self::assertEquals(1, $table->getColumns()->count());
        self::assertEquals($table, $table->getColumns()->first()->getTable());
        self::assertEquals(app(User::class)->getTable(), $table->getColumns()->first()->getDbTable());
        self::assertEquals('name', $table->getColumns()->first()->getDataSourceField());
    }

    /** @test */
    public function it_can_add_column_to_table_with_collection_data_source(): void
    {
        $table = (new Table())->fromCollection(collect([['name' => 'Name test']]));
        $table->column('name');
        self::assertEquals(1, $table->getColumns()->count());
        self::assertEquals($table, $table->getColumns()->first()->getTable());
        self::assertNull($table->getColumns()->first()->getDbTable());
        self::assertEquals('name', $table->getColumns()->first()->getDataSourceField());
    }

    /** @test */
    public function it_can_setup_default_title_from_field_with_model_data_source(): void
    {
        $user = $this->createUniqueUser();
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $html = $table->toHtml();
        self::assertStringContainsString('validation.attributes.name', $html);
    }

    /** @test */
    public function it_can_setup_default_title_from_field_with_collection_data_source(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromCollection(collect([['name' => 'Name test']]))->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $html = $table->toHtml();
        self::assertStringContainsString('validation.attributes.name', $html);
    }

    /** @test */
    public function it_cant_add_column_when_no_data_source_is_defined(): void
    {
        $this->expectException(TableDataSourceNotDefined::class);
        $this->expectExceptionMessage('The table has no defined build source. '
            . 'Please defined a build source by calling the `model()` or `collection()` method.');
        (new Table())->column('name');
    }
}
