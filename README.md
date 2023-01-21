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

Save time and easily render tables in your views from Eloquent models.

Tables can be generated under the following UI frameworks:
* Bootstrap 5
* Bootstrap 4
* TailwindCSS 3 (upcoming feature)

Found this package helpful? Please consider supporting my work!

[![Donate](https://img.shields.io/badge/Buy_me_a-Ko--fi-ff5f5f.svg)](https://ko-fi.com/arthurlorent)
[![Donate](https://img.shields.io/badge/Donate_on-PayPal-green.svg)](https://paypal.me/arthurlorent)

## Compatibility

| Laravel version                                      | Livewire version | PHP version                  | Package version |
|------------------------------------------------------|------------------|------------------------------|-----------------|
| ^9.0 &#124; ^10.0                                    | ^2.0             | 8.1.* &#124; 8.2.*           | ^5.3            |
| ^8.0 &#124; ^9.0                                     | ^2.0             | ^8.1                         | ^5.0            |
| ^7.0 &#124; ^8.0                                     | X                | ^7.4 &#124; ^8.0             | ^4.0            |
| ^7.0 &#124; ^8.0                                     | X                | ^7.4 &#124; ^8.0             | ^3.0            |
| ^6.0 &#124; ^7.0                                     | X                | ^7.4 &#124; ^8.0             | ^2.0            |
| ^5.8 &#124; ^6.0 &#124; ^7.0                         | X                | ^7.2 &#124; ^7.3 &#124; ^7.4 | ^1.3            |
| ^5.5 &#124; ^5.6 &#124; ^5.7 &#124; ^5.8 &#124; ^6.0 | X                | ^5.8 &#124; ^7.1             | ^1.0            |

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
            Column::make('id')->sortable(),
            Column::make('name')->searchable()->sortable(),
            Column::make('email')->searchable()->sortable(),
            Column::make('created_at')
                ->format(new DateFormatter('d/m/Y H:i', 'Europe/Paris'))
                ->sortable(),
            Column::make('updated_at')
                ->format(new DateFormatter('d/m/Y H:i', 'Europe/Paris'))
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
  * [Pass external data to your tables](#pass-external-data-to-your-tables)
  * [Generate tables from Eloquent models](#generate-tables-from-eloquent-models)
  * [Override native selects behaviour on your tables](#override-native-selects-behaviour-on-your-tables)
  * [Add query instructions on tables](#add-query-instructions-on-tables)
  * [Handle tables number of rows per page, pagination and navigation status](#handle-tables-number-of-rows-per-page-pagination-and-navigation-status)
  * [Set conditional row class](#set-conditional-row-class)
  * [Setup table filters](#setup-table-filters)
  * [Define table head action](#define-table-head-action)
  * [Define table bulk actions](#define-table-bulk-actions)
  * [Define table row actions](#define-table-row-actions)
  * [Declare columns on tables](#declare-columns-on-tables)
  * [Format column values](#format-column-values)
  * [Define column actions](#define-column-actions)
  * [Configure columns searching](#configure-columns-searching)
  * [Configure columns sorting](#configure-columns-sorting)
  * [Allow columns to be reordered from drag and drop action](#allow-columns-to-be-reordered-from-drag-and-drop-action)
  * [Declare results on tables](#declare-results-on-tables)
  * [Set up a few lines of JavaScript](#set-up-a-few-lines-of-javascript)
  * [Trigger Livewire events on table load](#trigger-livewire-events-on-table-load)
  * [Interact with your tables from events](#interact-with-your-tables-from-events)
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

Status
* `Loading in progress...`
* `No results were found.`
* `You can rearrange the order of the items in this list using a drag and drop action.`
* `Reset filters`
* `Yes`
* `No`
* `Search by:`
* `Reset research`
* `Number of rows per page`
* `Sort ascending`
* `Sort descending`
* `Actions`
* `Bulk Actions`
* `Create`
* `Add`
* `Show`
* `Edit`
* `Destroy`
* `Activate`
* `Deactivate`
* `Verify Email`
* `Unverify Email`
* `Toggle On`
* `Toggle Off`
* `Are you sure you want to execute the action :action on the line #:primary?`
* `Are you sure you want to execute the action :action on the field :attribute from the line #:primary?`
* `Are you sure you want to execute the action :action on the :count selected lines?`
* `The line #:primary does not allow the action :action and will not be affected.`
* `:count selected lines do not allow the action :action and will not be affected.`
* `The action :action has been executed on the line #:primary.`
* `The action :action has been executed on the field :attribute from the line #:primary.`
* `The action :action has been executed on the :count selected lines.`
* `The line #:primary does not allow the action :action and was not affected.`
* `:count selected lines do not allow the action :action and were not affected.`
* `The list has been reordered.`
* `Showing results <b>:start</b> to <b>:stop</b> on <b>:total</b>`

## How to

### Create table configurations

Generate a table configuration by executing this command : `php artisan make:table UsersTable`.

If you want to generate a configuration with a predefined model, just add this option at the end: `--model=App/Models/User`.

You'll find all your generated table configurations in the `app/Tables` directory.

### Display tables in views

Just call this Livewire component in your view with your configuration class name passed in the `config` parameter.

```blade
<livewire:table :config="App\Tables\UsersTable::class"/>
```

### Pass external data to your tables

In case you have specific attributes to transmit to your table configuration, you should pass them to the `configParams` parameter.

This could be useful when you have to transmit external information to your table.

```blade
<livewire:table :config="App\Tables\UsersTable::class" :configParams="['categoryId' => 1]"/>
```

You should then declare the passed attributes as `public` attributes your table configuration.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    // You will now be able to use the provided `$this->categoryId` category ID in your table configuration.
    public int $categoryId;

    // ...
}
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

### Override native selects behaviour on your tables

You may want to override native HTML select components behaviour on your tables.

You will be able to add a data attribute (which is known as the best practice to add extra features to a HTML component) to all the HTML select components displayed on your tables by defining an array of HTML attribute as value for the `laravel-table.html_select_components_attributes` config key.

```php
// `data-selector` HTML attribute will be appended to all tables HTML select components.
'html_select_components_attributes' => ['data-selector' => true],
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
            ->query(fn(Builder $query) => $query->where('category_id', 1));
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
* Set options globally from the `laravel-table.number_of_rows_per_page_default_options` config array value
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

### Setup table filters

Configuring table filters will make them appear as `select` HTML components on a dedicated bar above the table.

The filters bar will not appear if no filter is declared.

This package provides the following built-in filters:
* `ValueFilter`:
  * Requires `string $label`, `string $attribute`, `array $options` and `bool $multiple = true` arguments on instantiation
  * Filters the table based on whether the value of the selected options (or single option if multiple mode is disabled) is found in the given attribute
* `RelationshipFilter`:
  * Requires `string $label`, `string $relationship`, `array $options` and `bool $multiple = true` arguments on instantiation
  * Filters the table based on whether the value of the selected options (or single option if multiple mode is disabled) is found in the given relationship
* `NullFilter`
  * Requires a `string $attribute` argument on instantiation
  * Filters the table based on whether the value of the given attribute is `null` or not
* `BooleanFilter`
  * Requires `string $label` and `string $attribute` arguments on instantiation
  * Filters the table based on whether the value of the given attribute is `true` or `false`

To use them, you'll have to pass an array to the `filters` method, containing the filter instances to declare.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Filters\NullFilter;
use Okipa\LaravelTable\Filters\ValueFilter;
use Okipa\LaravelTable\Filters\BooleanFilter;
use Okipa\LaravelTable\Filters\RelationshipFilter;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->filters([
                new ValueFilter('Email', 'email', User::pluck('email', 'email')->toArray()),
                new RelationshipFilter('Categories', 'categories', UserCategory::pluck('name', 'id')->toArray()),
                new NullFilter('Email Verified', 'email_verified_at'),
                new BooleanFilter('Active', 'active'),
            ]);
    }
}
```

You may need to create your own filters. To do so, execute the following command: `php artisan make:table:filter MyNewFilter`.

You'll find your generated table filter in the `app/Tables/Filters` directory.

You will now be able to use your new filter in your tables.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use App\Tables\Filters\MyNewFilter;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->filters([
                new MyNewFilter(),
            ]);
    }
}
```

### Define table head action

Configure a table action that will be displayed as a button positioned at the right of the table head.

If no head action is declared, the dedicated slot for it in the table head will remain empty.

This package provides the following built-in head actions:
* `RedirectHeadAction`:
    * Requires `string $url`, `string $label`, `string $icon`, `array $class = ['btn', 'btn-success']` and `bool $openInNewWindow = false` arguments on instantiation
    * Redirects to the given URL from a click on the button
* `CreateHeadAction`:
    * Requires `string $createUrl` and `bool $openInNewWindow = false` arguments on instantiation
    * Instantiate a pre-configured `RedirectHeadAction` with `$createUrl` as URL, `__('Create')` as label and `config('laravel-table.icon.create')` as icon

To use one of them, you'll have to pass an instance of it to the `headAction` method.

You'll be able to chain the following method to your head action:
* `when(bool $condition): Okipa\LaravelTable\Abstracts\AbstractHeadAction`
    * Determines whether the head action should be enabled

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\HeadActions\AddHeadAction;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            // Create head action will not be available when authenticated user is not allowed to create users
            ->headAction((new AddHeadAction(route('user.create')))->when(Auth::user()->cannot('create_users')));
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
use App\Tables\HeadActions\MyNewHeadAction;
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

### Define table bulk actions

Configure table bulk actions that will be available in a dropdown positioned at the left of the table head.

If no bulk action is declared on your table, the dedicated column will not be displayed.

**Important note:** [you'll have to set up a few lines of javascript](#set-up-a-few-lines-of-javascript) to allow bulk actions confirmation requests and feedback to be working properly.

This package provides the built-in following bulk actions:
* `VerifyEmailBulkAction`:
    * Requires a `string $attribute` argument on instantiation
    * Update the given attribute with the current datetime for all selected lines
* `CancelEmailVerificationBulkAction`:
    * Requires a `string $attribute` argument on instantiation
    * Update the given attribute to `null` for all selected lines
* `ActivateBulkAction`:
    * Requires a `string $attribute` argument on instantiation
    * Update the given attribute to `true` for all selected lines
* `DeactivateBulkAction`:
    * Requires a `string $attribute` argument on instantiation
    * Update the given attribute to `false` for all selected lines
* `DestroyBulkAction`:
    * Destroys all the selected lines

To use them, you'll have to pass a closure parameter to the `bulkActions` method. This closure will allow you to manipulate a `Illuminate\Database\Eloquent $model` argument and has to return an array containing bulk action instances.

You'll be able to chain the following methods to your bulk actions:
* `when(bool $condition): Okipa\LaravelTable\Abstracts\AbstractBulkAction`
    * Determines whether the bulk action should be enabled on the table rows
* `confirmationQuestion(string|false $confirmationQuestion): Okipa\LaravelTable\Abstracts\AbstractBulkAction`
    * Overrides the default action confirmation message
* `feedbackMessage(string|false $feedbackMessage): Okipa\LaravelTable\Abstracts\AbstractBulkAction`:
    * Overrides the default action feedback message

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\BulkActions\DestroyBulkAction;
use Okipa\LaravelTable\BulkActions\ActivateBulkAction;
use Okipa\LaravelTable\BulkActions\DeactivateBulkAction;
use Okipa\LaravelTable\BulkActions\VerifyEmailBulkAction;
use Okipa\LaravelTable\BulkActions\CancelEmailVerificationBulkAction;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->bulkActions(fn(User $user) => [
                new VerifyEmailBulkAction('email_verified_at'),
                new CancelEmailVerificationBulkAction('email_verified_at'),
                new ActivateBulkAction('active'),
                new DeactivateBulkAction('active'),
                (new DestroyBulkAction())
                    // Destroy action will not be available for authenticated user
                    ->when(Auth::user()->isNot($user))
                    // Override the action default confirmation question
                    // Or set `false` if you do not want to require any confirmation for this action
                    ->confirmationQuestion('Are you sure you want to delete selected users ?')
                    // Override the action default feedback message
                    // Or set `false` if you do not want to trigger any feedback message for this action
                    ->feedbackMessage('Selected users have been deleted.'),
            ]);
    }
}
```

You may need to create your own bulk actions. To do so, execute the following command: `php artisan make:table:bulk:action MyNewBulkAction`.

You'll find your generated table bulk actions in the `app/Tables/BulkActions` directory.

You will now be able to use your new bulk action in your tables.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use App\Tables\BulkActions\MyNewBulkAction;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->bulkActions(fn(User $user) => [
                new MyNewBulkAction(),
            ]);
    }
}
```

### Define table row actions

Configure row actions on your table that will be displayed at the end of each row.

If no row action is declared on your table, the dedicated `Actions` column at the right of the table will not be displayed.

**Important note:** [you'll have to set up a few lines of javascript](#set-up-a-few-lines-of-javascript) to allow row actions confirmation requests and feedback to be working properly.

This package provides the built-in following row actions:
* `RedirectRowAction`:
    * Requires `string $url`, `string $title`, `string $icon`, `array $class = ['link-info']`, `string|null $defaultConfirmationQuestion = null`, `string|null $defaultFeedbackMessage = null` and `bool $openInNewWindow = false` arguments on instantiation
    * Redirects to the given URL from a click on the link
* `ShowRowAction`:
  * Requires `string $showUrl` and `bool $openInNewWindow = false` arguments on instantiation
  * Instantiate a pre-configured `RedirectRowAction` with `$showUrl` as URL, `__('Show')` as label and `config('laravel-table.icon.show')` as icon
* `EditRowAction`:
  * Requires a `string $editUrl` argument on instantiation
  * Redirects to the model edit page on click
* `DestroyRowAction`:
  * Destroys the line after being asked to confirm

To use them, you'll have to pass a closure parameter to the `rowActions` method. This closure will allow you to manipulate a `Illuminate\Database\Eloquent $model` argument and has to return an array containing row action instances.

You'll be able to chain the same methods as for a bulk action => [See bulk actions configuration](#define-table-bulk-actions).

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\RowActions\EditRowAction;
use Okipa\LaravelTable\RowActions\ShowRowAction;
use Okipa\LaravelTable\RowActions\DestroyRowAction;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->model(User::class)
            ->rowActions(fn(User $user) => [
                new ShowRowAction(route('user.show', $user)),
                new EditRowAction(route('user.edit', $user)),
                (new DestroyRowAction())
                    // Destroy action will not be available for authenticated user
                    ->when(Auth::user()->isNot($user))
                    // Override the action default confirmation question
                    // Or set `false` if you do not want to require any confirmation for this action
                    ->confirmationQuestion('Are you sure you want to delete user ' . $user->name . '?')
                    // Override the action default feedback message
                    // Or set `false` if you do not want to trigger any feedback message for this action
                    ->feedbackMessage('User ' . $user->name . ' has been deleted.'),
            ]);
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
use App\Tables\RowActions\MyNewRowAction;
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

You may need your row actions to be confirmed before they'll be executed and to trigger feedback messages.

You'll have to configure them in the same way you did for [bulk actions](#define-table-bulk-actions).

### Declare columns on tables

Declare columns on tables with the `columns` method available in your generated table configuration, from which you'll have to return an array of column instances.

To declare columns, just use the static `make` method that will await a `string $attribute` argument. This attribute will be used to get the default cell value.

By default, the column title will be defined to `__('validation.attributes.<attribute>')` in order to reuse attributes translations.

If you need to, you may use the `title` method that will await a `string $title` argument to set a specific column title that will override the default one.

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
            // Column attribute set to `id`, column value set from `$user->id` and colum title set to `__('validation.attributes.id')`
            Column::make('id'),
            // Column attribute set to `name`, value set from `$user->name` and column title set to `Username`
            Column::make('name')->title('Username'),
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
            Column::make('id'),
            // Value set from closure
            Column::make('username')
                ->format(fn(User $user) => '<b> ' . $user->companies->implode('name', ', ') . '</b>'),
        ];
    }
}
```

If you want to apply the same formatting treatment repeatedly, you should create a formatter with the following command: `php artisan make:table:formatter NewFormatter`.

You'll find the generated formatter in the `app\Table\Formatters` directory.

You'll be able to reuse this formatter in your tables.

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Column;
use App\Tables\Formatters\NewFormatter;
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
            Column::make('id'),
            Column::make('name')->format(new NewFormatter()),
        ];
    }
}
```

This package provides the following built-in formatters :
* `BooleanFormatter`:
  * Displays a yes/no status from a `boolean` value
* `DateFormatter`:
  * Requires `string $format` and `string $timezone` arguments on instantiation
  * Displays a formatted string from a `date` or `datetime` value
* `StrLimitFormatter`:
  * Allows optional `int $limit` and `string $end` arguments on instantiation
  * Displays a truncated string with a title allowing to see the full string on hover

### Define column actions

Configure column actions on your table that will be displayed on their own cells.

Column actions have a lot in common with row actions.

**Important note:** [you'll have to set up a few lines of javascript](#set-up-a-few-lines-of-javascript) to allow column actions confirmation requests and feedback to be working properly.

This package provides the built-in following actions:
* `ToggleBooleanColumnAction`:
  * Toggles the email verification status
* `ToggleBooleanColumnAction`:
  * Toggles a boolean value

To use them, you'll have to pass a closure parameter to the `action` method. This closure will allow you to manipulate a `Illuminate\Database\Eloquent $model` argument and has to return an `AbstractColumnAction` instance.

You'll be able to chain the same methods as for a bulk action => [See bulk actions configuration](#define-table-bulk-actions).

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\ColumnActions\ToggleBooleanColumnAction;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\ColumnActions\ToggleBooleanColumnAction;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()->model(User::class);
    }
    
    protected function columns(): array
    {
        return [
            Column::make('id'),
            Column::make('email_verified_at')
                // ToggleBooleanColumnAction action will not trigger any feedback message
                ->action(fn(User $user) => (new ToggleBooleanColumnAction()->feedbackMessage(false))
            Column::make('active')
                // ToggleBooleanColumnAction action will not be available for authenticated user
                ->action(fn(User $user) => (new ToggleBooleanColumnAction())->when(Auth::user()->isNot($user))),
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
use App\Tables\ColumnActions\MyNewColumnAction;
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
            Column::make('id'),
            Column::make('action')->action(fn() => new MyNewColumnAction()),
        ];
    }
}
```

You may need your column actions to be confirmed before they'll be executed and to trigger feedback messages.

You'll have to configure them in the same way you did for [bulk actions](#define-table-bulk-actions). 

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
            Column::make('id'),
            // Table will be searchable from `$user->name`
            Column::make('name')->searchable(),
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
            Column::make('id'),
            // Column will be searchable using this closure
            Column::make('owned_companies')
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
            Column::make('id'),
            // Column will be sortable from `$user->name`
            Column::make('name')->sortable(),
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
            Column::make('id'),
            // Column will be sorted descending by default on `$user->name`
            Column::make('name')->sortByDefault('desc'),
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
            Column::make('id'),
            // Column will be sortable from this closure
            Column::make('companies_count') 
                // Custom formatting...
                ->sortable(fn(Builder $query, string $sortDir) => $query
                    ->withCount('companies')
                    ->orderBy('companies_count', $sortDir)),
        ];
    }
}
```

### Allow columns to be reordered from drag and drop action

Allow columns to be reordered from drag and drop action by calling the `reorderable` method on your table.

This method will await a first `string $attribute` argument, an optional second `string $title` argument, and an optional third `string $sortDirByDefault` argument (accepting `asc` or `desc` values).

**Important notes:**
* [You'll have to set up a few lines of javascript](#set-up-a-few-lines-of-javascript) to allow reorder action feedback to be working properly
* You'll have to install the [Livewire Sortable Plugin](https://github.com/livewire/sortable), that will handle the drag and drop utility for us

Activating this feature will:
* Prepend a new column that will display the drag-and-drop icon defined in the `laravel-table.icon.drag_drop` config value, followed by the defined model order attribute value
* Sort the rows from the defined model order attribute (`asc` by default)
* Disable all other columns sorting as it is not compatible with drag-and-drop reordering
* And of course, enable the drag-and-drop columns reordering by adding all the **Livewire Sortable Plugin** necessary markup

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
            // A new column will display the drag-and-drop icon, followed by the `position` attribute value
            // Rows will be sorted from the `position` model attribute and all other columns sorting will be disable
            ->reorderable('position');
    }
}
```

Tip: if you are using packages like [spatie/eloquent-sortable](https://github.com/spatie/eloquent-sortable) to handle your Eloquent models sorting behaviour with a [grouping query](https://github.com/spatie/eloquent-sortable#grouping), you'll have to also set this grouping query in the [table query instruction](#add-query-instructions-on-tables).

### Declare results on tables

To display results, you'll have to return an array of result instances from the `results` method available in your generated table configuration.

If no result is declared, their dedicated space will remain empty.

Results should be declared this way:
1. Create a `Result` instance with the static `make` method
2. Chain the `title` method that will await a `string $title` argument
3. Chain the `format` method that will await a closure, letting you manipulate `Illuminate\Database\Query\Builder $totalRowsQuery` and `Illuminate\Support\Collection $displayedRowsCollection` params

```php
namespace App\Tables;

use App\Models\User;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Result;
use Illuminate\Support\Collection;
use Illuminate\Database\Query\Builder;
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
            Column::make('id'),
        ];
    }
    
    protected function results(): array
    {
        return [
            // This result uses the first $totalRowsQuery closure param to compute its value.
            // In this example, all users contained in database with unverified email will be count.
            Result::make()
                ->title('Total of users with unverified email')
                ->format(static fn(Builder $totalRowsQuery) => $totalRowsQuery
                    ->whereNull('email_verified_at')
                    ->count()),
            // This result uses the second $displayedRowsCollection closure param to compute its value.
            // In this example, all displayed inactive users will be count.
            Result::make()
                ->title('Displayed inactive users')
                ->format(static fn(
                    Builder $totalRowsQuery,
                    Collection $displayedRowsCollection
                ) => $displayedRowsCollection->where('active', false)->count()),
        ];
    }
}
```

### Set up a few lines of JavaScript

You'll have to add few Javascript lines to your project once this package is installed, in order to allow confirmation requests and actions feedback to be working properly. 

When an action is requesting the user confirmation, it will not be directly executed. A `table:action:confirm` Livewire event will be emitted instead with the following parameters:
1. The action type
2. The action identifier
3. The model primary key related to your action
4. The `$confirmationQuestion` attribute from your action

As you will see on the provided snippet below, the 4th param of this event is the only one you'll have to use in order to request the user confirmation. The 3 first params are only there to be sent back to a new event when the action is confirmed by the user. Just ignore them in your treatment.

You will have to intercept this event from your own JS script and prompt a confirmation request.

When the action is confirmed by the user, you'll have to emit a new `laraveltable:action:confirmed` Livewire event that will trigger the action execution. You'll have to pass it the 3 first arguments provided in the `table:action:confirm` event:
1. The action type
2. The action identifier
3. The model primary key related to your action

Here is an JS snippet to show you how to proceed:

```javascript
// Listen to the action confirmation request
Livewire.on('laraveltable:action:confirm', (actionType, actionIdentifier, modelPrimary, confirmationQuestion) => {
    // You can replace this native JS confirm dialog by your favorite modal/alert/toast library implementation. Or keep it this way!
    if (window.confirm(confirmationQuestion)) {
        // As explained above, just send back the 3 first argument from the `table:action:confirm` event when the action is confirmed
        Livewire.emit('laraveltable:action:confirmed', actionType, actionIdentifier, modelPrimary);
    }
});
```

Once an action is executed, a `table:action:feedback` Livewire event is triggered (it sometimes depends on the configuration of a feedback message).

Following the same logic, you'll have to intercept it from a JS script as shown on the snippet below to provide an immediate feedback to the user:

```javascript
Livewire.on('laraveltable:action:feedback', (feedbackMessage) => {
    // Replace this native JS alert by your favorite modal/alert/toast library implementation. Or keep it this way!
    window.alert(feedbackMessage);
});
```

Finally, in order to allow head `RedirectHeadAction` and `CreateHeadAction` to open link in new tab, you'll also have to add the following JS snippet:

```javascript
Livewire.on('laraveltable:link:open:newtab', (url) => {
    window.open(url, '_blank').focus();
});
```

### Trigger Livewire events on table load

You may want to trigger some events on table load, in order to load UI third party JS libraries for example.

You can do it using the table `emitEventsOnLoad` method, that will await an array of events.

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
            // This event will be loaded each time your table will be rendered
            // in order to keep your UI third party libraries rendering,
            // even when its HTML is refreshed.
            ->emitEventsOnLoad(['js:selector:init' => ['some', 'params']]);
    }   
}
```

### Interact with your tables from events

You will be able to interact with your tables by sending them the following Livewire events:
* `laraveltable:refresh`
  * Allows optional `array $configParams = []`, and `array $targetedConfigs = []` arguments
  * Refreshes your tables and (optionaly) set [external table config data](#pass-external-data-to-your-tables) with (optional) table targeting to only refresh specific ones (empty `$targetedConfigs` array will refresh all tables one page) 

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
