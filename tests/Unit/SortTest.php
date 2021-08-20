<?php

namespace Okipa\LaravelTable\Tests\Unit;

use ErrorException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\Company;
use Okipa\LaravelTable\Test\Models\User;

class SortTest extends LaravelTableTestCase
{
    public function testNoSortableAttribute(): void
    {
        $users = $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('email');
        $table->configure();
        foreach ($users as $key => $user) {
            self::assertEquals($user->name, $table->getPaginator()->items()[$key]['name']);
        }
    }

    public function testSetSortableAttribute(): void
    {
        $table = (new Table())->fromModel(User::class);
        $table->column('name')->sortable();
        self::assertTrue($table->getColumns()->first()->getIsSortable());
        self::assertEquals(1, $table->getSortableColumns()->count());
        self::assertEquals('name', $table->getSortableColumns()->first()->getDataSourceField());
    }

    public function testSetSortByDefaultAttribute(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('email')->sortable(true, 'desc');
        self::assertEquals('email', $table->getSortByValue());
        self::assertEquals('desc', $table->getSortDirValue());
        $table->configure();
        self::assertEquals('email', $table->getSortByValue());
        self::assertEquals('desc', $table->getSortDirValue());
    }

    public function testSortByDefault(): void
    {
        $users = $this->createMultipleUsers(5);
        $this->routes(['users'], ['index']);
        $table = (new Table())->routes(['index' => ['name' => 'users.index']])->fromModel(User::class);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email')->sortable(true);
        $table->configure();
        self::assertEquals(
            $users->sortBy('email')->values()->toArray(),
            $table->getPaginator()->getCollection()->toArray()
        );
    }

    public function testSortByDefaultCalledMultiple(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The table is already sorted by the "name" database column. You only can sort '
            . 'a table column by default once');
        $table = (new Table())->fromModel(User::class);
        $table->column('name')->sortable(true);
        $table->column('email')->sortable(true);
    }

    public function testNoSortableColumnDefined(): void
    {
        $this->createMultipleUsers(5);
        $this->routes(['users'], ['index']);
        $table = (new Table())->routes(['index' => ['name' => 'users.index']])->fromModel(User::class);
        $table->column('name');
        $table->column('email');
        $table->configure();
        $this->assertNull($table->getSortByValue());
        self::assertEquals($table->getSortDirValue(), 'asc');
    }

    public function testSortableColumnDefinedWithNoDefaultSort(): void
    {
        $this->createMultipleUsers(5);
        $this->routes(['users'], ['index']);
        $table = (new Table())->routes(['index' => ['name' => 'users.index']])->fromModel(User::class);
        $table->column('name')->sortable();
        $table->column('email')->sortable();
        $table->configure();
        self::assertEquals($table->getSortByValue(), $table->getColumns()->first()->getDataSourceField());
        self::assertEquals($table->getSortDirValue(), 'asc');
    }

    public function testSortByColumnWithoutAttribute(): void
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('One of the sortable table columns has no defined database column. '
            . 'You have to define a database column for each sortable table columns by '
            . 'setting a string parameter in the "column" method.');
        $this->createMultipleUsers(5);
        $this->routes(['companies'], ['index']);
        $table = (new Table())->routes(['index' => ['name' => 'companies.index']])->fromModel(User::class);
        $table->column()->sortable();
        $table->configure();
    }

    public function testSortByColumn(): void
    {
        $users = $this->createMultipleUsers(3);
        $customRequest = (new Request())->merge([
            (new Table())->getRowsNumberField() => 20,
            (new Table())->getSortByField() => 'email',
            (new Table())->getSortDirField() => 'desc',
        ]);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest);
        $table->column('name')->title('Name')->sortable();
        $table->column('email')->title('Email')->sortable();
        $table->configure();
        self::assertEquals('email', $table->getSortByValue());
        self::assertEquals('desc', $table->getSortDirValue());
        self::assertEquals(
            $users->sortByDesc('email')->values()->toArray(),
            $table->getPaginator()->toArray()['data']
        );
    }

    public function testSortOnOtherTableFieldWithoutCustomTableDeclaration(): void
    {
        $this->createMultipleUsers(5);
        $companies = $this->createMultipleCompanies(5);
        $this->routes(['companies'], ['index']);
        $customRequest = (new Request())->merge([
            (new Table())->getRowsNumberField() => 20,
            (new Table())->getSortByField() => 'owner',
            (new Table())->getSortDirField() => 'desc',
        ]);
        $table = (new Table())->fromModel(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->query(function (Builder $query) {
                $query->select('companies_test.*');
                $query->addSelect('users_test.name as owner');
                $query->join('users_test', 'users_test.id', '=', 'companies_test.owner_id');
            })
            ->request($customRequest);
        $table->column('owner')->sortable();
        $table->configure();
        foreach ($companies->load('owner')->sortByDesc('owner.name')->values() as $key => $company) {
            self::assertEquals($company->owner->name, $table->getPaginator()->toArray()['data'][$key]['owner']);
        }
    }

    public function testPaginateSortOnOtherTableField(): void
    {
        $this->createMultipleUsers(5);
        $this->createMultipleCompanies(10);
        $this->routes(['companies'], ['index']);
        $customRequest = (new Request())->merge([
            (new Table())->getRowsNumberField() => 5,
            (new Table())->getSortByField() => 'owner',
            (new Table())->getSortDirField() => 'desc',
        ]);
        $table = (new Table())->fromModel(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->query(function (Builder $query) {
                $query->select('companies_test.*');
                $query->addSelect('users_test.name as owner');
                $query->join('users_test', 'users_test.id', '=', 'companies_test.owner_id');
            })
            ->request($customRequest);
        $table->column('owner')->sortable();
        $table->configure();
        $paginatedCompanies = Company::join('users_test', 'users_test.id', '=', 'companies_test.owner_id')
            ->orderBy('users_test.name', 'desc')
            ->select('companies_test.*')
            ->with('owner')
            ->paginate(5);
        foreach ($paginatedCompanies as $key => $company) {
            self::assertEquals($company->owner->name, $table->getPaginator()->toArray()['data'][$key]['owner']);
        }
    }

    public function testSortableColumnHtml(): void
    {
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->title('Name')->sortable();
        $table->column('email')->title('Email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(
            'href="http://localhost/users/index?' . $table->getRowsNumberField() . '=20&amp;'
            . $table->getSortByField() . '=name&amp;'
            . $table->getSortDirField() . '=desc"',
            $html
        );
        self::assertStringNotContainsString(
            'href="http://localhost/users/index?' . $table->getRowsNumberField() . '=20&amp;'
            . $table->getSortByField() . '=email&amp;'
            . $table->getSortDirField() . '=desc"',
            $html
        );
    }
}
