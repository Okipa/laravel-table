<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ValueTest extends LaravelTableTestCase
{
    /** @test */
    public function it_can_set_custom_value(): void
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $closure = fn(array $row) => 'user name = ' . $row['name'];
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('updated_at')->value($closure);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertEquals($closure, $table->getColumns()->last()->getCustomValueClosure());
        self::assertStringContainsString('user name = ' . $user->name, $html);
    }
}
