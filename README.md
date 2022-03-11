![Laravel Table](/docs/laravel-table.png)
<p align="center">
    <a href="https://github.com/Okipa/laravel-table/releases" title="Latest Stable Version">
        <img src="https://img.shields.io/github/release/Okipa/laravel-table.svg?style=flat-square" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/Okipa/laravel-table" title="Total Downloads">
        <img src="https://img.shields.io/packagist/dt/okipa/laravel-table.svg?style=flat-square" alt="Total Downloads">
    </a>
    <a href="https://github.com/Okipa/laravel-table/actions" title="Build Status">
        <img src="https://github.com/Okipa/laravel-table/workflows/CI/badge.svg" alt="Build Status">
    </a>
    <a href="https://coveralls.io/github/Okipa/laravel-table?branch=master" title="Coverage Status">
        <img src="https://coveralls.io/repos/github/Okipa/laravel-table/badge.svg?branch=master" alt="Coverage Status">
    </a>
    <a href="/LICENSE.md" title="License: MIT">
        <img src="https://img.shields.io/badge/License-MIT-blue.svg" alt="License: MIT">
    </a>
</p>

![Generate tables from Eloquent models](docs/screenshot.png)

Save time and easily render tables in your views from models, objects, collections or arrays.

Tables can be generated under the following UI frameworks:
* Bootstrap 5
* Bootstrap 4
* TailwindCSS 2 (upcoming feature)

Found this package helpful? Please consider supporting my work!

[![Donate](https://img.shields.io/badge/Buy_me_a-Ko--fi-ff5f5f.svg)](https://ko-fi.com/arthurlorent)
[![Donate](https://img.shields.io/badge/Donate_on-PayPal-green.svg)](https://paypal.me/arthurlorent)

## Compatibility

| Laravel version | Livewire version | PHP version | Package version |
|---|---|---|---|
| ^8.0 &#124; ^9.0 | ^2.0 | ^8.0 &#124; ^8.1 | ^5.0 |
| ^7.0 &#124; ^8.0 | X | ^7.4 &#124; ^8.0 | ^4.0 |
| ^7.0 &#124; ^8.0 | X | ^7.4 &#124; ^8.0 | ^3.0 |
| ^6.0 &#124; ^7.0 | X | ^7.4 &#124; ^8.0 | ^2.0 |
| ^5.8 &#124; ^6.0 &#124; ^7.0 | X | ^7.2 &#124; ^7.3 &#124; ^7.4 | ^1.3 |
| ^5.5 &#124; ^5.6 &#124; ^5.7 &#124; ^5.8 &#124; ^6.0 | X | ^5.8 &#124; ^7.1 | ^1.0 |

## Upgrade guide

* [From V4 to V5](/docs/upgrade-guides/from-v4-to-v5.md)
* [From V3 to V4](/docs/upgrade-guides/from-v3-to-v4.md)
* [From V2 to V3](/docs/upgrade-guides/from-v2-to-v3.md)
* [From V1 to V2](/docs/upgrade-guides/from-v1-to-v2.md)

## Usage

Create your table class with the following command:

```bash
php artisan make:table UsersTable --model=App/Models/User
```

Set your table configuration in the generated file, which can be found in the `app\Tables` directory:

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Column;
use Okipe\LaravelTable\Formatters\Date;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()->model(User::class);
    }

    protected function columns(): array
    {
        return [
            Column::make('Id')->sortable(),
            Column::make('Name')->searchable()->sortable(),
            Column::make('Email')->searchable()->sortable(),
            Column::make('Created at')
                ->format(new Datetime('d/m/Y H:i', 'Europe/Paris'))
                ->sortable(),
            Column::make('Updated at')
                ->format(new Datetime('d/m/Y H:i', 'Europe/Paris'))
                ->sortable()
                ->sortByDefault('desc'),
        ];
    }
}
```

And display it in a view:

```blade
<livewire:table :config="App\Tables\UsersTable::class"/>
```

## Table of contents

* [Installation](#installation)
* [Configuration](#configuration)
* [Templates](#templates)
* [Translations](#translations)
* [How to](#how-to)
  * [Create table configuration](#create-table-configurations)
  * [Display tables in views](#display-tables-in-views)
  * [Generate tables from Eloquent models](#generate-tables-from-eloquent-models)
  * [Add query instructions on tables](#add-query-instructions-on-tables)
  * [Handle tables number of rows per page, pagination and navigation status](#handle-tables-number-of-rows-per-page-pagination-and-navigation-status)
  * [Define table row actions](#define-table-row-actions)
  * [Declare columns on tables](#declare-columns-on-tables)
  * [Format column values](#format-column-values)
  * [Configure columns searching](#configure-columns-searching)
  * [Configure columns sorting](#configure-columns-sorting)
* [Testing](#testing)
* [Changelog](#changelog)
* [Contributing](#contributing)
* [Credits](#credits)
* [Licence](#license)

## Installation

* Install the package with composer:

```bash
composer require okipa/laravel-table
```

This package uses [Livewire](https://laravel-livewire.com) under the hood and its installation is required.

It will automatically be installed if you don't already have installed it.

However, you'll have to follow the [installation instructions](https://laravel-livewire.com/docs/installation) if it has not already been done.

## Configuration

Optionally publish the package configuration:

```bash
php artisan vendor:publish --tag=laravel-table:config
```

## Templates

Optionally publish the package templates:

```bash
php artisan vendor:publish --tag=laravel-table:views
```

## Translations

All words and sentences used in this package are translatable.

See how to translate them on the Laravel official documentation: https://laravel.com/docs/localization#using-translation-strings-as-keys.

Here is the list of the words and sentences available for translation:

* `Loading in progress...`
* `Create`
* `Show`
* `Edit`
* `Destroy`
* `Are you sure you want to perform this action?`
* `Number of rows per page`
* `Search by:`
* `Reset research`
* `Actions`
* `No results were found.`
* `Showing results <b>:start</b> to <b>:stop</b> on <b>:total</b>`

## How to

### Create table configurations

Generate a table configuration by executing this command : `php artisan make:table UsersTable`.

If you want to generate a configuration with a predefined model, just add this option at the end: `--model=App/Models/User`.

You'll find all your generated table configurations in the `app/Tables` directory.

### Display tables in views

Just call this Livewire component in your view with your configuration class name passed in the `config` parameter.

```blade
<x:livewire.table :config="App\Tables\UsersTable::class"/>
```

In case you have specific attributes to transmit to your table configuration, you should pass them to the `configParams` parameter.

```blade
<x:livewire.table :config="App\Tables\UsersTable::class" :configParams="['userCategoryId' => 1]"/>
```

### Generate tables from Eloquent models

To generate a table from an Eloquent model, you'll just have to call the `model` method on your table.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()->model(User::class);
    }
}
```

### Add query instructions on tables

To add specific query instructions on tables, use the available `query` method.

You'll be able to set specific Eloquent instructions by passing a closure parameter to the `query` method on your table.

This closure will allow you to manipulate a `\Illuminate\Database\Eloquent\Builder $query` argument.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Illuminate\Database\Eloquent\Builder;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->query(fn(Builder $query) => $query->where('active', true));
    }   
}
```

### Handle tables number of rows per page, pagination and navigation status

You have two ways to allow or disallow users to choose the number of rows that will be displayed per page:
* Activate or deactivate it globally from the `laravel-table.enable_number_of_rows_per_page_choice` config boolean value
* Override global activation status by executing the `enableNumberOfRowsPerPageChoice()` method on your table

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->enableNumberOfRowsPerPageChoice(false);
    }
}
```

Following the same logic, you'll be able to define the number of rows per page options that will be available for selection:
* Set options globally from the `laravel-table.number_of_rows_per_page_options` config array value
* Override global options by executing the `numberOfRowsPerPageOptions()` method on your table

The first available option will be automatically selected and applied on table initialization.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->numberOfRowsPerPageOptions([5, 10, 15, 20, 25]); // Table will display initialized 5 rows by default.
    }
}
```

Pagination will automatically be handled, according to the number of rows to display and the total number of rows, as well as a navigation status.

Both of them will be displayed in the table footer.

### Define table row actions

Configure row actions on your table by calling the `rowAction` method.

This package provides the following actions :
* `Show`: requires a `string $showUrl` parameter on instantiation
* `Edit`: requires a `string $editUrl` parameter on instantiation
* `Destroy`: allows an optional `string $confirmationMessage` parameter on instantiation

To use them, you'll have to declare a closure parameter that will allow you to manipulate a `Illuminate\Database\Eloquent $model` argument.

This closure will have to return an array containing your row actions.

Also note that you'll be able to display row action conditionally in the closure.

```
namespace App\Tables;

use App\Models\Users\User;
use Okipa\LaravelTable\Table;
use App\Tables\RowActions\Edit;
use App\Tables\RowActions\Show;
use App\Tables\RowActions\Destroy;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->rowAction(fn(User $user) => [
                new Show(route('user.show', $user)),
                new Edit(route('user.edit', $user)),
                Auth::user()->is($user) // Destroy action will not be available for auth user row.
                    ? null
                    : new Destroy(__('Are you sure you want to delete user :name ?', [
                        'name' => $user->name,
                    ])),
            ]).
    }
}
```

You may want to create your own actions. To do so, execute the following command: `php artisan make:row:action Disable`.

You'll find your generated table row actions in the `app/Tables/RowActions` directory.

Here is an example of the generated row action after being correctly configured.

```
namespace Okipa\LaravelTable\RowActions;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractRowAction;

class Disable extends AbstractRowAction
{
    protected function class(): string
    {
        return 'link-danger';
    }

    protected function key(): string
    {
        return 'disable';
    }

    protected function title(): string
    {
        return __('Disable');
    }

    protected function icon(): string
    {
        return '<i class="fa-solid fa-power-off"></i>';
    }

    protected function shouldBeConfirmed(): bool
    {
        return true;
    }

    public function action(Model $model): void
    {
        $model->update(['active' => false]);
    }
}
```

You will now be able to use your row action in your tables.

```
namespace App\Tables;

use App\Models\Users\User;
use Okipa\LaravelTable\Table;
use App\Tables\RowActions\Disable;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->rowAction(fn(User $user) => [
                new Disable(),
            ]);
    }
}
```

### Declare columns on tables

Declare columns on tables with the `columns` method available in your generated table configuration.

You'll have to pass a `string $title` param to the `column` method, that will be used to:
* Display the column title on the table
* Define a default column key guessed from a snake_case formatting of the column title
* Define a default column/rows value from the column key

Optionally, you can pass a second `string $key` argument to set a specific column key.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()->model(User::class);
    }
    
    protected function columns(): array
    {
        return [
            Column::make('Id'), // Column title set to `Id`, column key set to `id` and value set from `$user->id`
            Column::make('Username', 'name'), // Column title set to `Username`, column key set to `name` and value set from `$user->name`
        ];
    }
}
```

### Format column values

You'll sometimes need to apply specific formatting for your columns. There are a few ways to achieve this.

For specific cases, you should pass a closure parameter to the `format` method on your column.

This closure will allow you to manipulate a `Illuminate\Database\Eloquent $model` argument.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()->model(User::class);
    }
    
    protected function columns(): array
    {
        return [
            Column::make('Id'), // Value set from `$user->id`
            Column::make('Username')
                ->format(fn(User $user) => '<b> ' . $user->companies->implode('name', ', ') . '</b>'), // Value set from closure
        ];
    }
}
```

If you want to apply the same formatting treatment repeatedly, you should create a formatter with the following command: `php artisan make:table:formatter Boolean`.

You'll find the generated formatter in the `app\Table\Formatters` directory.

Here is an example of the generated formatted after being correctly configured.

```php
namespace App\Tables\Formatters;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;

class Boolean extends AbstractFormatter
{
    public function format(Model $model, string $key): string
    {
        return $model->{$key}
            ? '<i class="fa-solid fa-check text-success"></i>'
            : '<i class="fa-solid fa-xmark text-danger"></i>';
    }
}
```

You'll be able to reuse this formatter in your tables.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Column;
use App\Tables\Formatters\Boolean;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()->model(User::class);
    }
    
    protected function columns(): array
    {
        return [
            Column::make('Id'),
            Column::make('Active')->format(new Boolean()),
        ];
    }
}
```

This package provides the following formatters :
* `Boolean`
* `Date`: requires `string $editUrl` and `string $timezone` parameters on instantiation
* `StrLimit`: allows optional `int $limit` and `string $end` parameters on instantiation

### Configure columns searching

Allow searching on columns by calling the `searching` method.

When searchable fields are set, a search input will appear in the table head.

Searchable column titles will be used to indicate which field can be searched on the search input placeholder.

By default, searching will be applied to columns defined keys.

```php
class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()->model(User::class);
    }
    
    protected function columns(): array
    {
        return [
            Column::make('Id'), // Column will not be searchable
            Column::make('Name')->searchable(), // Table will be searchable from `$user->name`
        ];
    }
}
```

You will be able to set up a custom searching behaviour by passing a closure to the `searchable` method.

This closure will be executed when searching will be triggered on the table and will allow you to manipulate a `Illuminate\Database\Eloquent\Builder $query` argument.

```php
class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()->model(User::class);
    }
    
    protected function columns(): array
    {
        return [
            Column::make('Id'), // Column will not be searchable
            Column::make('Owned companies')
                // ... Custom formatting
                ->searchable(fn(Builder $query, string $searchBy) => $query->whereRelation(
                    'companies',
                    'name',
                    'LIKE',
                    '%' . $searchBy . '%'
                ), // Column will be searchable using this closure
        ];
    }
}
```

### Configure columns sorting

Allow sorting on columns by calling the `sortable` method.

Sortable columns will display clickable sort icons before their titles that will trigger ascending or descending sorting.

By default, sorting will be applied to columns defined keys.

```php
class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()->model(User::class);
    }
    
    protected function columns(): array
    {
        return [
            Column::make('Id'), // Column will not be sortable
            Column::make('Name')->sortable(), // Column will be sortable from `$user->name`
        ];
    }
}
```

To sort a column by default, use the `sortByDefault` column method, which will allow you to pass a `string $direction` argument.

You can sort by default a column that is not sortable.

```php
class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()->model(User::class);
    }
    
    protected function columns(): array
    {
        return [
            Column::make('Id'), // Column will not be sortable
            Column::make('Name')->sortByDefault('desc'), // Column will be sorted descending by default on `$user->name`
        ];
    }
}
```

You will be able to set up a custom sorting behaviour by passing a closure to the `sortable` method.

This closure will be executed when sorting will be triggered on the column and will allow you to manipulate a `Illuminate\Database\Eloquent\Builder $query` and a `string $direction` arguments.

```php
class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()->model(User::class);
    }
    
    protected function columns(): array
    {
        return [
            Column::make('Id'), // Column will not be sortable
            Column::make('Companies count') 
                // Custom formatting...
                ->sortable(fn(Builder $query, bool $sortDir) => $query
                    ->withCount('companies')
                    ->orderBy('companies_count', $sortDir)), // Column will be sortable from this closure
        ];
    }
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

* [Arthur LORENT](https://github.com/okipa)
* [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
