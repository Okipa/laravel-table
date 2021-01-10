<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class PaginationTest extends LaravelTableTestCase
{
    public function testAppendData(): void
    {
        $appended = ['foo' => 'bar', 'baz' => 'qux', 'quux_corge' => 'grault garply', 'waldo'];
        $table = (new Table())->appendData($appended);
        self::assertEquals($table->getAppendedToPaginator(), $appended);
        self::assertEquals($table->getGeneratedHiddenFields(), $appended);
    }

    public function testAppendDataHtml(): void
    {
        $this->routes(['users'], ['index']);
        $appended = ['foo' => 'bar', 'baz' => 'qux', 'quux_corge' => 'grault garply', 'waldo'];
        $table = (new Table())->routes(['index' => ['name' => 'users.index']])
            ->model(User::class)
            ->appendData($appended);
        $table->column('name')->title('Name')->searchable();
        $table->configure();
        $rowsNumberDefinitionHtml = view(
            'laravel-table::' . $table->getrowsNumberDefinitionTemplatePath(),
            compact('table')
        )->toHtml();
        self::assertStringContainsString('<input type="hidden" name="foo" value="bar">', $rowsNumberDefinitionHtml);
        self::assertStringContainsString('<input type="hidden" name="baz" value="qux">', $rowsNumberDefinitionHtml);
        self::assertStringContainsString(
            '<input type="hidden" name="quux_corge" value="grault garply">',
            $rowsNumberDefinitionHtml
        );
        self::assertStringContainsString('<input type="hidden" name="0" value="waldo">', $rowsNumberDefinitionHtml);
        $rowsSearchingHtml = view(
            'laravel-table::' . $table->getRowsSearchingTemplatePath(),
            compact('table')
        )->toHtml();
        self::assertStringContainsString('<input type="hidden" name="foo" value="bar">', $rowsSearchingHtml);
        self::assertStringContainsString('<input type="hidden" name="baz" value="qux">', $rowsSearchingHtml);
        self::assertStringContainsString(
            '<input type="hidden" name="quux_corge" value="grault garply">',
            $rowsSearchingHtml
        );
        self::assertStringContainsString('<input type="hidden" name="0" value="waldo">', $rowsSearchingHtml);
    }
}
