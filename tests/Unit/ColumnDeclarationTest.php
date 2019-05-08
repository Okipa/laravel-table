<?php

namespace Okipa\LaravelTable\Tests\Unit;

use ErrorException;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ColumnDeclarationTest extends LaravelTableTestCase
{
    public function testAddColumn()
    {
        $columnAttribute = 'name';
        $table = (new Table)->model(User::class);
        $table->column($columnAttribute);
        $this->assertEquals($table->columns->count(), 1);
        $this->assertEquals($table->columns->first()->table, $table);
        $this->assertEquals($table->columns->first()->databaseDefaultTable, app(User::class)->getTable());
        $this->assertEquals($table->columns->first()->databaseDefaultColumn, $columnAttribute);
    }

    public function testAddColumnWithAttributeAndNoTitleHtml()
    {
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $html = $table->render();
        $this->assertStringContainsString('validation.attributes.name', $html);
    }

    public function testAddColumnWithNoDefinedModel()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The table model has not been defined or is not an instance of '
                                      . '« Illuminate\Database\Eloquent\Model ».');
        (new Table)->column('name');
    }

    public function testNoDeclaredColumn()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('No column has been added to the table. Please add at least one column by '
                                      . 'using the « column() » method on the table object.');
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->render();
    }
}
