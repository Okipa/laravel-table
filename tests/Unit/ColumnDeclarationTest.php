<?php

namespace Okipa\LaravelTable\Tests\Unit;

use ErrorException;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ColumnDeclarationTest extends LaravelTableTestCase
{
    public function testAddColumn(): void
    {
        $columnAttribute = 'name';
        $table = (new Table())->model(User::class);
        $table->column($columnAttribute);
        self::assertEquals(1, $table->getColumns()->count());
        self::assertEquals($table, $table->getColumns()->first()->getTable());
        self::assertEquals(app(User::class)->getTable(), $table->getColumns()->first()->getDbTable());
        self::assertEquals($columnAttribute, $table->getColumns()->first()->getDbField());
    }

    public function testAddColumnWithAttributeAndNoTitleHtml(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $html = $table->toHtml();
        self::assertStringContainsString('validation.attributes.name', $html);
    }

    public function testAddColumnWithNoDefinedModel(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The table model has not been defined or is not an instance of '
                                      . '« Illuminate\Database\Eloquent\Model ».');
        (new Table())->column('name');
    }

    public function testNoDeclaredColumn(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('No column has been added to the table. Please add at least one column by '
                                      . 'using the « column() » method on the table object.');
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->configure();
    }
}
