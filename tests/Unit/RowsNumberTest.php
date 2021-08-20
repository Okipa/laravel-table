<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Http\Request;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class RowsNumberTest extends LaravelTableTestCase
{
    public function testSetRowsNumberDefinitionActivationAttribute(): void
    {
        $table = (new Table())->activateRowsNumberDefinition();
        self::assertTrue($table->getRowsNumberDefinitionActivation());
    }

    public function testSetRowsNumberAttribute(): void
    {
        $rowsNumber = 10;
        $table = (new Table())->rowsNumber($rowsNumber);
        self::assertEquals($rowsNumber, $table->getRowsNumberValue());
    }

    public function testSetUnlimitedRowsNumberAttribute(): void
    {
        $rowsNumber = false;
        $table = (new Table())->rowsNumber(false);
        self::assertEquals($rowsNumber, $table->getRowsNumberValue());
    }

    public function testDeactivateRowsNumberDefinitionFromConfigHtml(): void
    {
        config()->set('laravel-table.behavior.activate_rows_number_definition', false);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->searchable();
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString('rows-number-definition', $html);
        self::assertStringNotContainsString('type="hidden" name="search"', $html);
        self::assertStringNotContainsString(
            'placeholder="Number of rows"',
            $html
        );
        self::assertStringNotContainsString(
            'aria-label="Number of rows"',
            $html
        );
    }

    public function testDeactivateRowsNumberDefinitionFromMethodHtml(): void
    {
        config()->set('laravel-table.behavior.activate_rows_number_definition', true);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->activateRowsNumberDefinition(false);
        $table->column('name')->searchable();
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertStringNotContainsString('rows-number-definition', $html);
        self::assertStringNotContainsString('type="hidden" name="search"', $html);
        self::assertStringNotContainsString(
            'placeholder="Number of rows"',
            $html
        );
        self::assertStringNotContainsString(
            'aria-label="Number of rows"',
            $html
        );
    }

    public function testActivateRowsNumberDefinitionHtml(): void
    {
        config()->set('laravel-table.behavior.activate_rows_number_definition', false);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->activateRowsNumberDefinition();
        $table->column('name');
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('rows-number-definition', $html);
        self::assertStringContainsString('type="hidden" name="search"', $html);
        self::assertStringContainsString(
            'placeholder="Number of rows"',
            $html
        );
        self::assertStringContainsString(
            'aria-label="Number of rows"',
            $html
        );
    }

    public function testSetCustomRowsNumberFromConfigHtml(): void
    {
        config()->set('laravel-table.behavior.rows_number', 15);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('value="15"', $html);
    }

    public function testSetCustomRowsNumberFromMethodHtml(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(15);
        $table->column('name');
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('value="15"', $html);
    }

    public function testSetCustomRowsNumberFromRequest(): void
    {
        $this->createMultipleUsers(20);
        $customRequest = (new Request())->merge([(new Table())->getRowsNumberField() => 10]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest);
        $table->column('name')->sortable(true, 'asc');
        $table->column('email');
        $table->configure();
        self::assertEquals(
            App(User::class)->orderBy('name', 'asc')->paginate(10)->toArray()['data'],
            $table->getPaginator()->toArray()['data']
        );
    }

    public function testSetUnlimitedRowsNumberHtml(): void
    {
        config()->set('laravel-table.behavior.rows_number', 5);
        $users = $this->createMultipleUsers(20);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->rowsNumber(null);
        $table->column('name');
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('value=""', $html);
        foreach ($users as $user) {
            self::assertStringContainsString($user->name, $html);
            self::assertStringContainsString($user->email, $html);
        }
    }
}
