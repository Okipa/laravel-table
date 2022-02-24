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
| ^8.0 | ^2.0 | ^8.0 | ^5.0 |
| ^7.0 | X | ^7.4 | ^4.0 |
| ^7.0 | X | ^7.4 | ^3.0 |
| ^6.0 | X | ^7.4 | ^2.0 |
| ^5.8 | X | ^7.2 | ^1.3 |
| ^5.5 | X | ^7.1 | ^1.0 |

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

use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Table;
use App\Models\User;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(Table $table): void
    {
        $table->model(User::class)
            ->routes([
                'index' => ['name' => 'users.index'],
                'create' => ['name' => 'user.create'],
                'edit' => ['name' => 'user.edit'],
                'destroy' => ['name' => 'user.destroy'],
            ])
            ->destroyConfirmationHtmlAttributes(fn(User $user) => [
                'data-confirm' => __('Are you sure you want to delete the user :name ?', [
                    'name' => $user->name
                ])
            ]);
    }

    protected function columns(Table $table): void
    {
        $table->column('id')->sortable(true);
        $table->column('name')->sortable()->searchable();
        $table->column('email')->sortable()->searchable();
        $table->column('created_at')->dateTimeFormat('d/m/Y H:i')->sortable();
        $table->column('updated_at')->dateTimeFormat('d/m/Y H:i')->sortable();
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
  * [Generate tables from models](#generate-tables-from-models)
  * [Add query instructions on tables](#add-query-instructions-on-tables)
  * [Handle tables number of rows per page, pagination and navigation status](#handle-tables-number-of-rows-per-page-pagination-and-navigation-status)
  * [Declare columns on tables](#declare-columns-on-tables)
  * [Set custom column titles](#set-custom-column-titles)
  * [Format column values](#format-column-values)
  * [Sort columns](#handle-columns-sorting)
  * [Configure columns searching](#configure-columns-searching)
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

This uses [Livewire](https://laravel-livewire.com) under the hood and its installation is required.

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

### Generate tables from models

To generate a table from an Eloquent model, you'll just have to define it with the `model()` method on your table.

```php
class UsersTable extends AbstractTableConfiguration
{
    protected function table(Table $table): void
    {
        $table->model(User::class);
    }
}
```

### Add query instructions on tables

To add specific query instructions on tables, use the available `query` method.

You'll be able to set specific Eloquent instructions by passing a closure parameter to the `query` method on your table.

This closure will allow you to manipulate a `\Illuminate\Database\Eloquent\Builder $query` argument.

```php
class UsersTable extends AbstractTableConfiguration
{
    protected function table(Table $table): void
    {
        $table->model(User::class)->query(fn(Builder $query) => $query->where('active', true));
    }   
}
```

### Handle tables number of rows per page, pagination and navigation status

You have two ways to allow or disallow users to choose the number of rows that will be displayed per page:
* Activate or deactivate it globally from the `laravel-table.enable_number_of_rows_per_page_choice` config boolean value
* Override global activation status by executing the `enableNumberOfRowsPerPageChoice()` method on your table

```php
class UsersTable extends AbstractTableConfiguration
{
    protected function table(Table $table): void
    {
        $table->model(User::class)->enableNumberOfRowsPerPageChoice(false);
    }
}
```

Following the same logic, you'll be able to define the number of rows per page options that will be available to select:
* Set options globally from the `laravel-table.number_of_rows_per_page_options` config array value
* Override global options by executing the `numberOfRowsPerPageOptions()` method on your table

The first option will be selected and applied on initialization.

```php
class UsersTable extends AbstractTableConfiguration
{
    protected function table(Table $table): void
    {
        $table->model(User::class)->numberOfRowsPerPageOptions([5, 10, 15, 20, 25]);
    }
}
```

Pagination will automatically be handled, according to the number of rows to display and the total number of rows, as well as a navigation status.

Both of them will be displayed in the table footer.

### Declare columns on tables

Declare columns on tables with the `columns` method available in your generated table configuration.

You'll have to pass a `string $key` param to the `column` method, that will be used to:
* Define a default column title to `__('validation.attributes.<key>')`
* Define a default column/rows value

```php
class UsersTable extends AbstractTableConfiguration
{
    protected function table(Table $table): void
    {
        $table->model(User::class);
    }
    
    protected function columns(Table $table): void
    {
        $table->column('id'); // Column title set to `__('validation.attributes.id')` and colum/rows value set to `$user->id`
        $table->column('name'); // Column title set to `__('validation.attributes.name')` and column/rows value set to `$user->name`
    }
}
```

### Set custom column titles

You can set a specific column title by using the `title` method.

```php
class UsersTable extends AbstractTableConfigurations
{
    protected function table(Table $table): void
    {
        $table->model(User::class);
    }
    
    protected function columns(Table $table): void
    {
        $table->column('id'); // Column title set to `__('validation.attributes.id')`
        $table->column('name')->title('Username'); // Column title set to `User`
    }
}
```

### Format column values

You'll sometimes need to format column values. There are a few ways to achieve this.

For specific cases, you should pass a closure parameter to the `format` method on your column.

This closure will allow you to manipulate a `Illuminate\Database\Eloquent $model` argument.

There is no obligation to set keys for formatted columns as their displayed values will be set from their closure. However, you will have to manually set titles to your columns as they are automatically generated from keys.

```php
class UsersTable extends AbstractTableConfigurations
{
    protected function table(Table $table): void
    {
        $table->model(User::class);
    }
    
    protected function columns(Table $table): void
    {
        $table->column('id'); // Value assumed from `$user->id`
        $table->column()->title('Name')->format(fn(User $user) => 'Custom formatted ' . $user->name); // Value set from closure
    }
}
```

If you want to apply the same formatting treatment repeatedly, you should create a formatter with the following command: `php artisan make:table:formatter ActiveFormatter`.

You'll find the generated formatter in the `app\Table\Formatters` directory.

```php
class ActiveFormatter extends AbstractFormatter
{
    public function format(Model $user): string
    {
        return $user->active
            ? '<i class="fa-solid fa-check text-success"></i>'
            : '<i class="fa-solid fa-xmark text-danger"></i>';
    }
}
```

You'll be able to reuse this formatter in your tables.

```php
class UsersTable extends AbstractTableConfigurations
{
    protected function table(Table $table): void
    {
        $table->model(User::class);
    }
    
    protected function columns(Table $table): void
    {
        $table->column('id')->format(new ActiveFormatter());
        $table->column()->title('Active')->format(new ActiveFormatter());
    }
}
```

### Handle columns sorting

Allow sorting on columns by calling the `sortable` method.

Sortable columns will display clickable sort icons before their titles that will trigger ascending or descending sorting.

By default, sorting will be applied to columns defined keys.

```php
class UsersTable extends AbstractTableConfigurations
{
    protected function table(Table $table): void
    {
        $table->model(User::class);
    }
    
    protected function columns(Table $table): void
    {
        $table->column('id'); // Column will not be sortable
        $table->column('name')->sortable(); // Column will be sortable on `$user->name`
    }
}
```

You will be able to set up a custom sorting behaviour for formatted columns by passing a closure to the `sortable` method.

This closure will be executed when sorting will be triggered on the column and allow you to manipulate a `Illuminate\Database\Eloquent\Builder $query` and a `bool $sortAsc` argument.

This feature will be useful for formatted columns, for example.

```php
class UsersTable extends AbstractTableConfigurations
{
    protected function table(Table $table): void
    {
        $table->model(User::class);
    }
    
    protected function columns(Table $table): void
    {
        $table->column('id'); // Column will not be sortable
        $table->column()
            ->title('Active')
            ->format(fn(User $user) => $user->active
                ? '<i class="fa-solid fa-check text-success"></i>'
                : '<i class="fa-solid fa-xmark text-danger"></i>';)
            ->sortable(fn(Builder $query, bool $sortAsc) => $query->orderBy('active', $sortAsc ? 'asc' : 'desc'));
    }
}
```

### Configure columns searching

ToDo

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
