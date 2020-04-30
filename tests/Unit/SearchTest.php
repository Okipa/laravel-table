<?php

namespace Okipa\LaravelTable\Tests\Unit;

use DB;
use ErrorException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\Company;
use Okipa\LaravelTable\Test\Models\User;
use PDOException;

class SearchTest extends LaravelTableTestCase
{
    public function testSetIsSearchableAttribute()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->searchable();
        $this->assertEquals('name', $table->getSearchableColumns()->first()->getDbField());
    }

    public function testSetSearchedDatabaseTableAttributeOnly()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->searchable('dbSearchedTable');
        $this->assertEquals('dbSearchedTable', $table->getColumns()->first()->getDbSearchedTable());
        $this->assertEquals([], $table->getColumns()->first()->getDbSearchedFields());
    }

    public function testSetSearchedDatabaseTableAndSearchedDatabaseColumns()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->searchable('dbSearchedTable', ['searchedField']);
        $this->assertEquals('dbSearchedTable', $table->getColumns()->first()->getDbSearchedTable());
        $this->assertEquals(['searchedField'], $table->getColumns()->first()->getDbSearchedFields());
    }

    public function testNotExistingSearchableColumn()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The table column with related « not_existing_column » database column is '
            . 'searchable and does not exist in the « users_test » table. Set the database '
            . 'searched table and (optionally) columns with the « sortable() » '
            . 'method arguments.');
        $this->routes(['users'], ['index']);
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('not_existing_column')->searchable();
        $table->configure();
    }

    public function testSearchAccurateRequest()
    {
        $users = $this->createMultipleUsers(5);
        $searchedValue = $users->sortBy('name')->values()->first()->name;
        $customRequest = (new Request)->merge([(new Table)->getRowsNumberField() => 20, 'search' => $searchedValue]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest);
        $table->column('name')->searchable();
        $table->column('email');
        $table->configure();
        $this->assertEquals(
            $users->sortBy('name')->where('name', $searchedValue)->values()->toArray(),
            $table->getPaginator()->toArray()['data']
        );
    }

    public function testSearchInaccurateRequest()
    {
        $this->createMultipleUsers(10);
        $searchedValue = 'al';
        $customRequest = (new Request)->merge([(new Table)->getRowsNumberField() => 20, 'search' => $searchedValue]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest);
        $table->column('name')->sortable(true);
        $table->column('email')->searchable();
        $table->configure();
        $this->assertEquals(
            App(User::class)
                ->orderBy('name', 'asc')
                ->where('email', 'like', '%' . $searchedValue . '%')
                ->get()
                ->toArray(),
            $table->getPaginator()->toArray()['data']
        );
    }

    public function testGetSearchableTitlesSingle()
    {
        $this->routes(['users'], ['index']);
        $this->createMultipleUsers(10);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->title('Name')->searchable();
        $table->column('email');
        $table->configure();
        $this->assertEquals('Name', $table->getSearchableTitles());
    }

    public function testGetSearchableTitlesMultiple()
    {
        $this->routes(['users'], ['index']);
        $this->createMultipleUsers(10);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->title('Name')->searchable();
        $table->column('email')->title('Email')->searchable();
        $table->configure();
        $this->assertEquals('Name, Email', $table->getSearchableTitles());
    }

    public function testSearchFieldFromOnOtherTableWithoutDeclaringSearchedDatabaseTable()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The table column with related « owner » database column is searchable and does '
            . 'not exist in the « companies_test » table. Set the database searched table '
            . 'and (optionally) columns with the « sortable() » method arguments.');
        $this->createMultipleUsers(5);
        $this->createMultipleCompanies(2);
        $this->routes(['companies'], ['index']);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->query(function (Builder $query) {
                $query->select('companies_test.*');
                $query->addSelect('users_test.name as owner');
                $query->join('users_test', 'users_test.id', '=', 'companies_test.owner_id');
            });
        $table->column('owner')->searchable();
        $table->configure();
    }

    public function testSearchWithoutColumnAttribute()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('One of the searchable table columns has no defined database column. You have '
            . 'to define a database column for each searchable table columns by setting a '
            . 'string parameter in the « column() » method.');
        $this->createMultipleUsers(5);
        $this->createMultipleCompanies(2);
        $this->routes(['companies'], ['index']);
        $table = (new Table)->model(Company::class)->routes(['index' => ['name' => 'companies.index']]);
        $table->column()->searchable('users_test', ['name']);
        $table->configure();
    }

    public function testSearchOnOtherTableFieldWithCustomTableDeclarationWithoutAlias()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The table column with related « owner » database column is searchable and does '
            . 'not exist in the « users_test » table. Set the database searched table and '
            . '(optionally) columns with the « sortable() » method arguments.');
        $this->createMultipleUsers(5);
        $this->createMultipleCompanies(2);
        $this->routes(['companies'], ['index']);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->query(function (Builder $query) {
                $query->select('companies_test.*');
                $query->addSelect('users_test.name as owner');
                $query->join('users_test', 'users_test.id', '=', 'companies_test.owner_id');
            });
        $table->column('owner')->searchable('users_test');
        $table->configure();
    }

    public function testSearchNonExistentFieldOnAliasedTable()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The table column with related « nonExistent » database column is searchable '
            . 'and does not exist in the « aliasesUserTable » (aliased as « users_test ») '
            . 'table. Set the database searched table and (optionally) columns with the '
            . '« sortable() » method arguments.');
        $this->createMultipleUsers(5);
        $this->createMultipleCompanies(2);
        $this->routes(['companies'], ['index']);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->query(function (Builder $query) {
                $query->select('companies_test.*');
                $query->join('users_test as aliasesUserTable', 'aliasesUserTable.id', '=', 'companies_test.owner_id');
            });
        $table->column('nonExistent')->searchable('aliasesUserTable');
        $table->configure();
    }

    public function testSearchOnOtherTableFieldWithCustomTableDeclarationHtml()
    {
        $this->createMultipleUsers(5);
        $companies = $this->createMultipleCompanies(2);
        $this->routes(['companies'], ['index']);
        $searchedValue = $companies->first()->owner->name;
        $customRequest = (new Request)->merge([(new Table)->getRowsNumberField() => 20, 'search' => $searchedValue]);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->query(function (Builder $query) {
                $query->select('companies_test.*');
                $query->addSelect('users_test.name as owner');
                $query->join('users_test', 'users_test.id', '=', 'companies_test.owner_id');
            })
            ->request($customRequest);
        $table->column('owner')->searchable('users_test', ['name']);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        foreach ($companies as $company) {
            if ($company->owner->name === $searchedValue) {
                $this->assertStringContainsString($company->owner->name, $html);
            } else {
                $this->assertStringNotContainsString($company->owner->name, $html);
            }
        }
    }

    public function testPaginateSearchOnOtherTableField()
    {
        $users = $this->createMultipleUsers(1);
        $this->createMultipleCompanies(10);
        $this->routes(['companies'], ['index']);
        $searchedValue = $users->first()->name;
        $customRequest = (new Request)->merge([
            (new Table)->getRowsNumberField() => 5,
            'search' => $searchedValue,
            'page' => 2,
        ]);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->query(function (Builder $query) {
                $query->select('companies_test.*');
                $query->addSelect('users_test.name as owner');
                $query->join('users_test', 'users_test.id', '=', 'companies_test.owner_id');
            })
            ->request($customRequest);
        $table->column('owner')->searchable('users_test', ['name']);
        $table->configure();
        foreach (App(Company::class)->paginate(5) as $key => $company) {
            $this->assertEquals($company->name, $table->getPaginator()->toArray()['data'][$key]['name']);
        }
    }

    public function testSearchOnSeveralCustomQueryFields()
    {
        $this->createMultipleUsers(5);
        $this->createMultipleCompanies(10);
        $this->routes(['companies'], ['index']);
        $searchedValue = '@';
        $customRequest = (new Request)->merge([(new Table)->getRowsNumberField() => 20, 'search' => $searchedValue]);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->request($customRequest)
            ->query(function (Builder $query) {
                $query->select('companies_test.*');
                $query->addSelect(DB::raw('users_test.name || " "|| users_test.email as owner'));
                $query->leftJoin('users_test', 'users_test.id', '=', 'companies_test.owner_id');
            });
        $table->column('name')->sortable(true);
        $table->column('owner')->searchable('users_test', ['name', 'email']);
        $table->configure();
        foreach ($table->getPaginator() as $company) {
            $owner = app(User::class)->find($company->owner_id);
            $this->assertEquals($company->owner, $owner->name . ' ' . $owner->email);
        }
    }

    public function testSearchOnSeveralCustomQueryFieldsFromAliasedTable()
    {
        $this->createMultipleUsers(5);
        $this->createMultipleCompanies(10);
        $this->routes(['companies'], ['index']);
        $searchedValue = '@';
        $customRequest = (new Request)->merge([(new Table)->getRowsNumberField() => 20, 'search' => $searchedValue]);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->request($customRequest)
            ->query(function (Builder $query) {
                $query->select('companies_test.*');
                $query->addSelect(DB::raw('userAliasedTable.name || " "|| userAliasedTable.email as owner'));
                $query->leftJoin('users_test as unusedAlias', 'unusedAlias.id', '=', 'companies_test.owner_id');
                $query->leftJoin(
                    'users_test as userAliasedTable',
                    'userAliasedTable.id',
                    '=',
                    'companies_test.owner_id'
                );
            });
        $table->column('name')->sortable(true);
        $table->column('owner')->searchable('userAliasedTable', ['name', 'email']);
        $table->configure();
        foreach ($table->getPaginator() as $company) {
            $owner = app(User::class)->find($company->owner_id);
            $this->assertEquals($company->owner, $owner->name . ' ' . $owner->email);
        }
    }

    public function testSearchOnRegularTableFieldWithSeveralAliasesTables()
    {
        $this->createMultipleUsers(5);
        $companies = $this->createMultipleCompanies(10);
        $this->routes(['companies'], ['index']);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->query(function (Builder $query) {
                $query->select('companies_test.*');
                $query->addSelect(DB::raw('userAliasedTable.name || " "|| userAliasedTable.email as owner'));
                $query->leftJoin('users_test as unusedAlias', 'unusedAlias.id', '=', 'companies_test.owner_id');
                $query->leftJoin(
                    'users_test as userAliasedTable',
                    'userAliasedTable.id',
                    '=',
                    'companies_test.owner_id'
                );
            });
        $table->column('name')->searchable();
        $table->configure();
        foreach ($table->getPaginator() as $company) {
            $this->assertEquals($company->name, $companies->where('id', $company->id)->first()->name);
        }
    }

    public function testSearchableHtml()
    {
        $this->routes(['users'], ['index']);
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name');
        $table->column('email')->searchable();
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        $this->assertStringContainsString('searching', $html);
        $this->assertStringContainsString('type="hidden" name="rows"', $html);
        $this->assertStringContainsString('placeholder="Search by: ' . $table->getSearchableTitles() . '"', $html);
        $this->assertStringContainsString('aria-label="Search by: ' . $table->getSearchableTitles() . '"', $html);
    }

    public function testNoSearchableHtml()
    {
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('email');
        $table->configure();
        $html = view('laravel-table::' . $table->getTheadTemplatePath(), compact('table'))->toHtml();
        $this->assertStringNotContainsString('searching', $html);
        $this->assertStringNotContainsString('type="hidden" name="rows"', $html);
        $this->assertStringNotContainsString(
            'placeholder="' . __('laravel-table::laravel-table.thead.search') . ' '
            . $table->getSearchableTitles() . '"',
            $html
        );
        $this->assertStringNotContainsString(
            'title="' . __('laravel-table::laravel-table.thead.search') . ' '
            . $table->getSearchableTitles() . '"',
            $html
        );
    }

    public function testSearchWrappedInSubWhereQuery()
    {
        $userAlpha = (new User)->create([
            'name' => 'User Alpha',
            'email' => 'alpha@test.fr',
            'password' => Hash::make('secret'),
        ]);
        $userBeta = (new User)->create([
            'name' => 'User Beta',
            'email' => 'beta@test.fr',
            'password' => Hash::make('secret'),
        ]);
        $userCharlie = (new User)->create([
            'name' => 'User Charlie',
            'email' => 'charlie@test.fr',
            'password' => Hash::make('secret'),
        ]);
        $companyAlpha = (new Company)->create([
            'owner_id' => $userAlpha->id,
            'name' => 'Company Alpha',
            'turnover' => rand(1000, 99999),
        ]);
        $companyBeta = (new Company)->create([
            'owner_id' => $userBeta->id,
            'name' => 'Company Beta',
            'turnover' => rand(1000, 99999),
        ]);
        $companyCharlie = (new Company)->create([
            'owner_id' => $userCharlie->id,
            'name' => 'Company Charlie',
            'turnover' => rand(1000, 99999),
        ]);
        $this->createMultipleCompanies(3);
        $this->routes(['users'], ['index']);
        $searchedValue = $userAlpha->email;
        $customRequest = (new Request)->merge([(new Table)->getRowsNumberField() => 20, 'search' => $searchedValue]);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest)
            ->query(function ($query) use ($companyBeta) {
                $query->select('users_test.*');
                $query->join('companies_test', 'companies_test.owner_id', '=', 'users_test.id');
                $query->where('companies_test.id', $companyBeta->id);
            });
        $table->column('name')->searchable();
        $table->column('email')->searchable();
        $table->configure();
        $this->assertEmpty($table->getPaginator()->toArray()['data']);
    }

    public function testSqliteCaseInsensitiveTestHtml()
    {
        $users = $this->createMultipleUsers(10);
        $users->each(function ($user, $key) {
            if ($key === 0) {
                $user->update(['name' => 'alpha']);
            } elseif ($key === 1) {
                $user->update(['name' => 'ALPHA']);
            } else {
                $user->update(['name' => 'omega']);
            }
        });
        $searchedValue = 'alpha';
        $customRequest = (new Request)->merge([(new Table)->getRowsNumberField() => 20, 'search' => $searchedValue]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest);
        $table->column('name')->searchable();
        $table->column('email')->searchable();
        $table->configure();
        $this->assertEquals($users->filter(function ($user) {
            return in_array($user->name, ['alpha', 'ALPHA']);
        })->toArray(), $table->getPaginator()->toArray()['data']);
    }

    public function testPostgresCaseInsensitiveTestHtml()
    {
        $this->expectException(PDOException::class);
        $this->expectExceptionMessage('SQLSTATE[HY000]: General error: 1 near "ILIKE": syntax error (SQL: select '
            . 'count(*) as aggregate from "users_test" where ("users_test"."name" ILIKE '
            . '%alpha% or "users_test"."email" ILIKE %alpha%))');
        $connection = config('database.default');
        config()->set('database.connections.' . $connection . '.driver', 'pgsql');
        $this->createMultipleUsers(10);
        $searchedValue = 'alpha';
        $customRequest = (new Request)->merge([(new Table)->getRowsNumberField() => 20, 'search' => $searchedValue]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest);
        $table->column('name')->searchable();
        $table->column('email')->searchable();
        $table->configure();
    }
}
