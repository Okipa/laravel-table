<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class PaginationTest extends LaravelTableTestCase
{
    public function testAppendData(): void
    {
        $appended = ['foo' => 'bar', 'baz' => ['qux', 'quux'], 7 => 'corge'];
        $table = (new Table())->appendData($appended);
        self::assertEquals($table->getAppendedToPaginator(), $appended);
        self::assertEquals($table->getGeneratedHiddenFields(), $appended);
    }

    public function testAppendDataHtml(): void
    {
        $this->routes(['users'], ['index']);
        $appended = ['foo' => 'bar', 'baz' => ['qux', 'quux'], 7 => 'corge'];
        $table = (new Table())->routes(['index' => ['name' => 'users.index']])
            ->model(User::class)
            ->appendData($appended);
        $table->column('name')->title('Name')->searchable();
        $table->configure();
        $rowsNumberDefinitionHtml = view(
            'laravel-table::' . $table->getrowsNumberDefinitionTemplatePath(),
            compact('table')
        )->toHtml();
        self::assertStringContainsString(
            '<form role="form" method="GET" action="' . $table->getRoute('index')
            . '?' . e(http_build_query($table->getAppendedToPaginator())),
            $rowsNumberDefinitionHtml
        );
    }
}
