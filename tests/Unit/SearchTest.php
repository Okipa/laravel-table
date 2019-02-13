<?php

namespace Okipa\LaravelTable\Tests\Unit;

use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\Company;
use Okipa\LaravelTable\Test\Models\User;

class SearchTest extends LaravelTableTestCase
{
    public function testSetIsSearchableAttribute()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->searchable();
        $this->assertEquals('name', $table->searchableColumns->first()->attribute);
    }

    public function testSetSearchedDatabaseTableAttributeOnly()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->searchable('searchedDatabaseTable');
        $this->assertEquals('searchedDatabaseTable', $table->columns->first()->searchedDatabaseTable);
        $this->assertEquals([], $table->columns->first()->searchedDatabaseColumns);
    }

    public function testSetSearchedDatabaseTableAndSearchedDatabaseColumns()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->searchable('searchedDatabaseTable', ['searchedField']);
        $this->assertEquals('searchedDatabaseTable', $table->columns->first()->searchedDatabaseTable);
        $this->assertEquals(['searchedField'], $table->columns->first()->searchedDatabaseColumns);
    }

    public function testNotExistingSearchableColumn()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The given attribute « not_existing_column » has not been found in the '
                                      . 'searchable-column « users_test » table. Set the searched table and '
                                      . 'attributes with the « sortable() » method.');
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('not_existing_column')->searchable();
        $table->render();
    }

    public function testSearchAccurateRequest()
    {
        $users = $this->createMultipleUsers(5);
        $searchedValue = $users->sortBy('name')->values()->first()->name;
        $customRequest = (new Request)->merge(['rows' => 20, 'search' => $searchedValue]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest);
        $table->column('name')->title('Name')->searchable();
        $table->column('email')->title('Email');
        $table->render();
        $this->assertEquals(
            $users->sortBy('name')->where('name', $searchedValue)->values()->toArray(),
            $table->list->toArray()['data']
        );
    }

    public function testSearchInaccurateRequest()
    {
        $this->createMultipleUsers(10);
        $searchedValue = 'al';
        $customRequest = (new Request)->merge(['rows' => 20, 'search' => $searchedValue]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest);
        $table->column('name')->title('Name')->sortable(true);
        $table->column('email')->title('Email')->searchable();
        $table->render();
        $this->assertEquals(
            App(User::class)
                ->orderBy('name', 'asc')
                ->where('email', 'like', '%' . $searchedValue . '%')
                ->get()
                ->toArray(),
            $table->list->toArray()['data']
        );
    }

    public function testGetSearchableTitlesSingle()
    {
        $this->routes(['users'], ['index']);
        $this->createMultipleUsers(10);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->title('Name')->searchable();
        $table->column('email')->title('Email');
        $table->render();
        $this->assertEquals('Name', $table->searchableTitles());
    }

    public function testGetSearchableTitlesMultiple()
    {
        $this->routes(['users'], ['index']);
        $this->createMultipleUsers(10);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->title('Name')->searchable();
        $table->column('email')->title('Email')->searchable();
        $table->render();
        $this->assertEquals('Name, Email', $table->searchableTitles());
    }

    public function testSearchFieldFromOnOtherTableWithoutDeclaringSearchedDatabaseTable()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The given attribute « owner » has not been found in the searchable-column '
                                      . '« companies_test » table. Set the searched table and attributes with the '
                                      . '« sortable() » method.');
        $this->createMultipleUsers(5);
        $this->createMultipleCompanies(2);
        $this->routes(['companies'], ['index']);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->query(function ($query) {
                $query->select('companies_test.*');
                $query->addSelect('users_test.name as owner');
                $query->join('users_test', 'users_test.id', '=', 'companies_test.owner_id');
            });
        $table->column('owner')->searchable();
        $table->render();
    }

    public function testSearchWithoutColumnAttribute()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('One of the searchable columns has no defined attribute. You have to define a '
                                      . 'column attribute for each searchable columns by setting a string parameter '
                                      . 'in the « column() » method.');
        $this->createMultipleUsers(5);
        $this->createMultipleCompanies(2);
        $this->routes(['companies'], ['index']);
        $table = (new Table)->model(Company::class)->routes(['index' => ['name' => 'companies.index']]);
        $table->column()->searchable('users_test', ['name']);
        $table->render();
    }

    public function testSearchOnOtherTableFieldWithCustomTableDeclarationWithoutAlias()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The given attribute « owner » has not been found in the searchable-column '
                                      . '« users_test » table. Set the searched table and attributes with the '
                                      . '« sortable() » method.');
        $this->createMultipleUsers(5);
        $this->createMultipleCompanies(2);
        $this->routes(['companies'], ['index']);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->query(function ($query) {
                $query->select('companies_test.*');
                $query->addSelect('users_test.name as owner');
                $query->join('users_test', 'users_test.id', '=', 'companies_test.owner_id');
            });
        $table->column('owner')->searchable('users_test');
        $table->render();
    }

    public function testSearchNonExistentFieldOnAliasedTable()
    {
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The given attribute « nonExistent » has not been found in the '
                                      . 'searchable-column « users_test » (aliased as « aliasesUserTable ») table. '
                                      . 'Set the searched table and attributes with the « sortable() » method.');
        $this->createMultipleUsers(5);
        $this->createMultipleCompanies(2);
        $this->routes(['companies'], ['index']);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->query(function ($query) {
                $query->select('companies_test.*');
                $query->join('users_test as aliasesUserTable', 'aliasesUserTable.id', '=', 'companies_test.owner_id');
            });
        $table->column('nonExistent')->searchable('aliasesUserTable');
        $table->render();
    }

    public function testSearchOnOtherTableFieldWithCustomTableDeclarationHtml()
    {
        $this->createMultipleUsers(5);
        $companies = $this->createMultipleCompanies(2);
        $this->routes(['companies'], ['index']);
        $searchedValue = $companies->first()->owner->name;
        $customRequest = (new Request)->merge(['rows' => 20, 'search' => $searchedValue]);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index'],])
            ->query(function ($query) {
                $query->select('companies_test.*');
                $query->addSelect('users_test.name as owner');
                $query->join('users_test', 'users_test.id', '=', 'companies_test.owner_id');
            })
            ->request($customRequest);
        $table->column('owner')->searchable('users_test', ['name']);
        $table->render();
        $html = view('laravel-table::' . $table->tbodyComponentPath, compact('table'))->render();
        foreach ($companies as $company) {
            if ($company->owner->name === $searchedValue) {
                $this->assertContains($company->owner->name, $html);
            } else {
                $this->assertNotContains($company->owner->name, $html);
            }
        }
    }

    public function testPaginateSearchOnOtherTableField()
    {
        $users = $this->createMultipleUsers(1);
        $this->createMultipleCompanies(10);
        $this->routes(['companies'], ['index']);
        $searchedValue = $users->first()->name;
        $customRequest = (new Request)->merge(['rows' => 5, 'search' => $searchedValue, 'page' => 2]);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->query(function ($query) {
                $query->select('companies_test.*');
                $query->addSelect('users_test.name as owner');
                $query->join('users_test', 'users_test.id', '=', 'companies_test.owner_id');
            })
            ->request($customRequest);
        $table->column('owner')->searchable('users_test', ['name']);
        $table->render();
        foreach (App(Company::class)->paginate(5) as $key => $company) {
            $this->assertEquals($company->name, $table->list->toArray()['data'][$key]['name']);
        }
    }

    public function testSearchOnSeveralCustomQueryFields()
    {
        $this->createMultipleUsers(5);
        $this->createMultipleCompanies(10);
        $this->routes(['companies'], ['index']);
        $searchedValue = '@';
        $customRequest = (new Request)->merge(['rows' => 20, 'search' => $searchedValue]);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->request($customRequest)
            ->query(function ($query) {
                $query->select('companies_test.*');
                $query->addSelect(\DB::raw('users_test.name || " "|| users_test.email as owner'));
                $query->leftJoin('users_test', 'users_test.id', '=', 'companies_test.owner_id');
            });
        $table->column('name')->sortable(true);
        $table->column('owner')->searchable('users_test', ['name', 'email']);
        $table->render();
        foreach ($table->list as $company) {
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
        $customRequest = (new Request)->merge(['rows' => 20, 'search' => $searchedValue]);
        $table = (new Table)->model(Company::class)
            ->routes(['index' => ['name' => 'companies.index']])
            ->request($customRequest)
            ->query(function ($query) {
                $query->select('companies_test.*');
                $query->addSelect(\DB::raw('userAliasedTable.name || " "|| userAliasedTable.email as owner'));
                $query->leftJoin(
                    'users_test as userAliasedTable',
                    'userAliasedTable.id',
                    '=',
                    'companies_test.owner_id'
                );
                $query->leftJoin('users_test as unusedAlias', 'unusedAlias.id', '=', 'companies_test.owner_id');
            });
        $table->column('name')->sortable(true);
        $table->column('owner')->searchable('userAliasedTable', ['name', 'email']);
        $table->render();
        foreach ($table->list as $company) {
            $owner = app(User::class)->find($company->owner_id);
            $this->assertEquals($company->owner, $owner->name . ' ' . $owner->email);
        }
    }

    public function testSearchableHtml()
    {
        $this->routes(['users'], ['index']);
        $table = (new Table)->routes(['index' => ['name' => 'users.index']])->model(User::class);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email')->searchable();
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertContains('search-bar', $html);
        $this->assertContains('name="search"', $html);
        $this->assertContains(
            'placeholder="' . __('laravel-table::laravel-table.search') . ' '
            . $table->searchableTitles() . '"',
            $html
        );
        $this->assertContains(
            'aria-label="' . __('laravel-table::laravel-table.search') . ' '
            . $table->searchableTitles() . '"',
            $html
        );
    }

    public function testNoSearchableHtml()
    {
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email');
        $table->render();
        $html = view('laravel-table::' . $table->theadComponentPath, compact('table'))->render();
        $this->assertNotContains('<div class="search-bar', $html);
        $this->assertNotContains(
            'placeholder="' . __('laravel-table::laravel-table.thead.search') . ' '
            . $table->searchableTitles() . '"',
            $html
        );
        $this->assertNotContains(
            'title="' . __('laravel-table::laravel-table.thead.search') . ' '
            . $table->searchableTitles() . '"',
            $html
        );
    }

    public function testSearchWithCustomQueryConstraint()
    {
        $userAlpha = (new User)->create([
            'name'     => 'User Alpha',
            'email'    => 'alpha@test.fr',
            'password' => Hash::make('secret'),
        ]);
        $userBeta = (new User)->create([
            'name'     => 'User Beta',
            'email'    => 'beta@test.fr',
            'password' => Hash::make('secret'),
        ]);
        $userCharlie = (new User)->create([
            'name'     => 'User Charlie',
            'email'    => 'charlie@test.fr',
            'password' => Hash::make('secret'),
        ]);
        $companyAlpha = (new Company)->create([
            'name'     => 'Company Alpha',
            'owner_id' => $userAlpha->id,
        ]);
        $companyBeta = (new Company)->create([
            'name'     => 'Company Beta',
            'owner_id' => $userBeta->id,
        ]);
        $companyCharlie = (new Company)->create([
            'name'     => 'Company Charlie',
            'owner_id' => $userCharlie->id,
        ]);
        $this->createMultipleCompanies(3);
        $this->routes(['users'], ['index']);
        $searchedValue = $userAlpha->email;
        $customRequest = (new Request)->merge(['rows' => 20, 'search' => $searchedValue]);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest)
            ->query(function ($query) use ($companyBeta) {
                $query->select('users_test.*');
                $query->join('companies_test', 'companies_test.owner_id', '=', 'users_test.id');
                $query->where('companies_test.id', $companyBeta->id);
            });
        $table->column('name')->title('Name')->searchable();
        $table->column('email')->title('Email')->searchable();
        $table->render();
        $this->assertEmpty($table->list->toArray()['data']);
    }

    public function testCaseInsensitiveTestHtml()
    {
        $users = $this->createMultipleUsers(10);
        $users->each(function($user, $key){
            if($key === 0) {
                $user->update(['name' => 'alpha']);
            } else if($key === 1) {
                $user->update(['name' => 'ALPHA']);
            } else {
                $user->update(['name' => 'omega']);
            }
        });
        $searchedValue = 'alpha';
        $customRequest = (new Request)->merge(['rows' => 20, 'search' => $searchedValue]);
        $this->routes(['users'], ['index']);
        $table = (new Table)->model(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->request($customRequest);
        $table->column('name')->title('Name')->searchable();
        $table->column('email')->title('Email')->searchable();
        $table->render();
        $this->assertEquals($users->filter(function($user){
            return in_array($user->name, ['alpha', 'ALPHA']);
        })->toArray(), $table->list->toArray()['data']);
    }
}
