![Laravel Table](/docs/laravel-table.png)
<p style="text-align: center">
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

DISCLAIMER: PACKAGE IN DEVELOPMENT, DO NOT USE IN PRODUCTION.

**V5 roadmap:**
* Table config `results` => set table result lines
* Table `reorderable` => allow rows reordering with drag & drop
* Table `bulkActions` => set bulk actions for checked rows
* Table `filters` => set actionable filters

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

Create your table with the following command:

```bash
php artisan make:table UsersTable --model=App/Models/User
```

Configure your table in the `UsersTable` generated class, which can be found in the `app\Tables` directory:

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
  * [Set conditional row class](#set-conditional-row-class)
  * [Define table head action](#define-table-head-action)
  * [Define table row actions](#define-table-row-actions)
  * [Declare columns on tables](#declare-columns-on-tables)
  * [Format column values](#format-column-values)
  * [Define column actions](#define-column-actions)
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

You'll have to follow the [installation instructions](https://laravel-livewire.com/docs/installation) if Livewire is not already installed on your project.

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
* `Number of rows per page`
* `Search by:`
* `Reset research`
* `No results were found.`
* `Actions`
* `Create`
* `Show`
* `Edit`
* `Destroy`
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

This could be useful when you have to transmit external information to your table.

```blade
<x:livewire.table :config="App\Tables\UsersTable::class" :configParams="['onlyDisplayUsersFromCategoryId' => 1]"/>
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
            // Table will display 5 rows on initialization and will allow displaying 10, 15, 20 or 25 rows.
            ->numberOfRowsPerPageOptions([5, 10, 15, 20, 25]);
    }
}
```

Pagination will automatically be handled, according to the number of rows to display and the total number of rows, as well as a navigation status.

Both of them will be displayed in the table footer.

### Set conditional row class

Define conditional row class on tables by passing a closure argument to the `rowClass` method.

This closure will allow you to manipulate a `Illuminate\Database\Eloquent $model` argument and has to return an array of classes where the array key contains the class or classes you wish to add, while the value is a boolean expression.

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
            ->rowClass(fn(User $user) => [
                'table-danger' => ! $user->active,
            ]);
    }
}
```

### Define table head action

Configure a table action that will be displayed as a button positioned at the right of the table head.

If no head action is declared, the dedicated slot for it in the table head will remain empty.

This package provides the following built-in head actions:
* `Create`:
  * Requires a `string $createUrl` argument on instantiation
  * Redirects to the model create page from a click on a `Create` button

To use it, you'll have to pass an instance of it to the `headAction` method.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\HeadActions\Create;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->headAction(new Create(route('user.create')));
    }
}
```

You may need to create your own head actions. To do so, execute the following command: `php artisan make:table:head:action MyNewHeadAction`.

You'll find your generated table head action in the `app/Tables/HeadActions` directory.

You will now be able to use your new head action in your tables.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use App\Tables\HeadActions\Configure;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->headAction(new MyNewHeadAction());
    }
}
```

### Define table row actions

Configure row actions on your table that will be displayed at the end of each row.

If no row action is declared on your table, the dedicated `Actions` column at the right of the table will not be displayed.

This package provides the built-in following actions:
* `Show`:
  * Requires a `string $showUrl` argument on instantiation
  * Redirects to the model edit page on click
* `Edit`:
  * Requires a `string $editUrl` argument on instantiation
  * Redirects to the model edit page on click
* `Destroy`:
  * Destroys the line after being asked to confirm

To use them, you'll have to pass a closure parameter to the `rowActions` method. This closure will allow you to manipulate a `Illuminate\Database\Eloquent $model` argument and has to return an array containing row actions instances.

You'll ben able to chain the following methods to your actions:
* `when(bool $condition): Okipa\LaravelTable\Abstracts\AbstractRowAction`
  * Determines if action should be available on table rows
* `confirmationQuestion(string|false $confirmationQuestion): Okipa\LaravelTable\Abstracts\AbstractRowAction`
  * Overrides the default action confirmation message
* `feedbackMessage(string|false $feedbackMessage): Okipa\LaravelTable\Abstracts\AbstractRowAction`:
  * Overrides the default action feedback message

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\RowActions\Edit;
use Okipa\LaravelTable\RowActions\Show;
use Okipa\LaravelTable\RowActions\Destroy;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->rowActions(fn(User $user) => [
                new Show(route('user.show', $user)),
                new Edit(route('user.edit', $user)),
                (new Destroy())
                    // Destroy action will not be available for authenticated user
                    ->when(Auth::user()->isNot($user))
                    // Override the action default confirmation question
                    // Or set `false` if you do not want to require any confirmation for this action
                    ->confirmationQuestion('Are you sure you want to delete user ' . $user->name . '?')
                    // Override the action default feedback message
                    // Or set `false` if you do not want to trigger any feedback message for this action
                    ->feedbackMessage('User ' . $user->name . ' has been deleted.'),
            ]).
    }
}
```

You may need to create your own row actions. To do so, execute the following command: `php artisan make:table:row:action MyNewRowAction`.

You'll find your generated table row actions in the `app/Tables/RowActions` directory.

You will now be able to use your new row action in your tables.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use App\Tables\RowActions\ToggleActivation;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->rowActions(fn(User $user) => [
                new MyNewRowAction(),
            ]);
    }
}
```

When an action has confirmation question configured, it will not be directly executed but a `table:action:confirm` Livewire event will be emitted instead with the following parameters:
1. The action type
2. The action identifier
3. The model primary key related to your action
4. The `$confirmationQuestion` attribute from your action

As you will see below, the 4th param of this event is the only one you'll have to use for in order to request the user confirmation. The 3 first params are only there to be sent back to a new event when the action is confirmed by the user. Just ignore them in your treatment.

You will have to intercept this event from your own JS script and display the action confirmation request from your favorite modal/alert/toast library (a basic example is provided below).

When action is confirmed by the user, you'll have to emit a new `table:action:confirmed` Livewire event that will trigger the action execution. You'll have to pass it the 3 first arguments provided in the `table:action:confirm` event:
1. The action type 
2. The action identifier
3. The model primary key related to your action

Here is an JS snippet to show you how to proceed:

```javascript
// Listen to the action confirmation request
Livewire.on('table:action:confirm', (actionType, actionIdentifier, modelPrimary, confirmationQuestion) => {
    // You can replace this native JS confirm dialog by your favorite modal/alert/toast library implementation. Or keep it this way!
    if (window.confirm(confirmationQuestion)) {
        // As explained above, just send back the 3 first argument from the `table:action:confirm` event when the action is confirmed
        Livewire.emit('table:action:confirmed', actionType, actionIdentifier, modelPrimary);
    }
});
```

Once the action executed, a last `table:action:executed` Livewire event will be triggered if the action has a configured feedback message.

Following the same logic, you'll have to intercept it from a JS script like this one to provide an immediate feedback to the user:

```javascript
Livewire.on('table:action:feedback', (feedbackMessage) => {
    // Replace this native JS alert by your favorite modal/alert/toast library implementation. Or keep it this way!
    window.alert(feedbackMessage);
});
```

### Declare columns on tables

Declare columns on tables with the `columns` method available in your generated table configuration.

You'll have to pass a `string $title` param to the `column` method, that will be used to:
* Display the column title on the table
* Define a default column key guessed from a snake_case formatting of the column title
* Define a default cell value from the column key

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
            // Column title set to `Id`, column key set to `id` and value set from `$user->id`
            Column::make(__(Id')),
            // Column title set to `Username`, column key set to `name` and value set from `$user->name`
            Column::make('Username', 'name'),
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
            // Value set from `$user->id`
            Column::make('Id'),
            // Value set from closure
            Column::make('Username')
                ->format(fn(User $user) => '<b> ' . $user->companies->implode('name', ', ') . '</b>'),
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
    public function format(Model $model, string $attribute): string
    {
        return $model->{$attribute}
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

This package provides the following built-in formatters :
* `Boolean`:
  * Displays a yes/no status from a `boolean` value
* `Datetime`:
  * Requires `string $format` and `string $timezone` arguments on instantiation
  * Displays a formatted string from a `date` or `datetime` value
* `StrLimit`:
  * Allows optional `int $limit` and `string $end` arguments on instantiation
  * Displays a truncated string with a title allowing to see the full string on hover

### Define column actions

Configure column actions on your table that will be displayed on their own cells.

Column actions have a lot in common with row actions.

This package provides the built-in following actions:
* `ToggleEmailVerified`:
  * Toggles the email verification status
* `ToggleBoolean`:
  * Toggles a boolean value

To use them, you'll have to pass a closure parameter to the `action` method. This closure will allow you to manipulate a `Illuminate\Database\Eloquent $model` argument and has to return an `AbstractColumnAction` instance.

You'll be able to chain the same methods as for a row action => [See row actions configuration](#define-table-row-actions).

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\ColumnActions\ToggleBoolean;
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
            Column::make('Email verified', 'email_verified_at')
                // ToggleEmailVerified action will not trigger any feedback message
                ->action(fn(User $user) => (new ToggleEmailVerified()->feedbackMessage(false))
            Column::make('Toggle', 'active')
                // ToggleBoolean action will not be available for authenticated user
                ->action(fn(User $user) => (new ToggleBoolean())->when(Auth::user()->isNot($user))),
        ];
    }
}
```

You may need to create your own column actions. To do so, execute the following command: `php artisan make:table:column:action MyNewColumnAction`.

You'll find your generated table column actions in the `app/Tables/ColumnActions` directory.

You will now be able to use your new column action in your tables.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use App\Tables\ColumnActions\ManageReviews;
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
            Column::make('My new column action')->action(fn() => new MyNewColumnAction()),
        ];
    }
}
```

You may need your column actions to be confirmed before they'll be executed and to trigger feedback messages.

You'll have to configure them in the same way you did for [row actions](#define-table-row-actions). 

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
            // Column will not be searchable
            Column::make('Id'),
            // Table will be searchable from `$user->name`
            Column::make('Name')->searchable(),
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
            // Column will not be searchable
            Column::make('Id'),
            // Column will be searchable using this closure
            Column::make('Owned companies')
                // ... Your custom formatting here
                ->searchable(fn(Builder $query, string $searchBy) => $query->whereRelation(
                    'companies',
                    'name',
                    'LIKE',
                    '%' . $searchBy . '%'
                ),
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
            // Column will not be sortable
            Column::make('Id'),
            // Column will be sortable from `$user->name`
            Column::make('Name')->sortable(),
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
            // Column will not be sortable
            Column::make('Id'),
            // Column will be sorted descending by default on `$user->name`
            Column::make('Name')->sortByDefault('desc'),
        ];
    }
}
```

You will be able to set up a custom sorting behaviour by passing a closure to the `sortable` method.

This closure will be executed when sorting will be triggered on the column and will allow you to manipulate a `Illuminate\Database\Eloquent\Builder $query` and a `string $sortDir` arguments (`asc` or `desc`).

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
            // Column will not be sortable
            Column::make('Id'),
            // Column will be sortable from this closure
            Column::make('Companies count') 
                // Custom formatting...
                ->sortable(fn(Builder $query, string $sortDir) => $query
                    ->withCount('companies')
                    ->orderBy('companies_count', $sortDir)),
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
