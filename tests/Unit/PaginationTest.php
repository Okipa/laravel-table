<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class PaginationTest extends LaravelTableTestCase
{
    public function testAppendData(): void
    {
        $appended = [
            'foo' => 'bar',
            'baz' => [
                'qux',
                'quux' => 'corge',
                'grault',
            ],
            7 => 'garply',
        ];
        $table = (new Table())->appendData($appended);
        self::assertEquals($table->getAppendedToPaginator(), $appended);
    }
}
