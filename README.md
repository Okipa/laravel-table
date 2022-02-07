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
* [Advanced configuration example](#advanced-configuration-example)
* [How to](#how-to)
  * [Create table configuration files](#create-table-configuration-files)
  * [Display tables in views](#display-tables-in-views)
  * [Generate tables from models](#generate-tables-from-models)
  * [Handle number of rows per page, pagination and navigation status](#handle-number-of-rows-per-page-pagination-and-navigation-status)
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

## Advanced configuration example

```php
namespace App\Tables;

use App\News;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class NewsTable extends AbstractTableConfiguration
{
    public function __construct(protected Request $request, protected int $categoryId)
    {
        //
    }

    protected function table(Table $table): void
    {
        $table->model(News::class)
            ->identifier('news-table')
            ->request($this->request)
            ->routes([
                'index' => ['name' => 'news.index'],
                'create' => ['name' => 'news.create'],
                'edit' => ['name' => 'news.edit'],
                'destroy' => ['name' => 'news.destroy'],
                'show' => ['name' => 'news.show'],
            ])
            ->numberOfRowsPerPageChoiceEnabled(false)
            ->numberOfRowsPerPageOptions([5, 10, 15, 20, 25])
            ->query(function (Builder $query) {
                // Some examples of what you can do
                $query->select('news.*');
                // Add a constraint
                $query->where('category_id', $this->categoryId);
                // Get value stored in a json field
                $query->addSelect('news.json_field->>json_attribute as json_attribute');
                // Get a formatted value from a pivot table
                $query->selectRaw('count(comments.id) as comments_count');
                $query->leftJoin('news_commment', 'news_commment.news_id', '=', 'news.id');
                $query->leftJoin('comments', 'comments.id', '=', 'news_commment.comment_id');
                $query->groupBy('comments.id');
                // Alias a value to make it available from the column model
                $query->addSelect('users.name as author');
                $query->join('users', 'users.id', '=', 'news.author_id');
            })
            ->disableRows(fn(News $news) => in_array($news->id, [1, 2]), ['disabled', 'bg-secondary', 'text-white'])
            ->rowsConditionalClasses(fn(News $news) => $news->id === 3, ['highlighted', 'bg-success'])
            ->rowsConditionalClasses(
                fn(News $news) => $news->category,
                fn(News $news) => 'category-' . Str::snake($news->category)
            )
            // Append all request params to the paginator
            ->appendData($this->request->all());
    }
    
    protected function columns(Table $table): void
    {
        $table->column('id')->sortable(true);
        $table->column()->title(__('Illustration'))->html(fn(News $news) => $news->image_src
            ? '<img src="' . $news->image_src . '" alt="' .  $news->title . '">'
            : null);
        $table->column('title')->sortable()->searchable();
        $table->column('content')->stringLimit(30);
        $table->column('author')->sortable(true)->searchable('user', ['name']);
        $table->column('category_id')
            ->title(__('Category'))
            ->prependHtml('<i class="fas fa-hand-point-right"></i>')
            ->appendsHtml('<i class="fas fa-hand-point-left"></i>')
            ->button(['btn', 'btn-sm', 'btn-outline-primary'])
            ->value(fn(News $news) => config('news.category.' . $news->category_id))
        $table->column()
            ->title(__('Display'))
            ->link(fn(News $news) => route('news.show', $news))
            ->button(['btn', 'btn-sm', 'btn-primary']);
        $table->column('created_at')->dateTimeFormat('d/m/Y H:i')->sortable();
        $table->column('updated_at')->dateTimeFormat('d/m/Y H:i')->sortable();
        $table->column('published_at')->dateTimeFormat('d/m/Y H:i')->sortable(true, 'desc');
    }

    protected function resultLines(Table $table): void
    {
        $table->result()
            ->title('Total of comments')
            ->html(fn(Collection $paginatedRows) => $paginatedRows->sum('comments_count'));
    }
}
```

## How to

### Create table configuration files

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

### Handle number of rows per page, pagination and navigation status

You have two ways to allow or disallow users to choose the number of rows that will be displayed per page:
* Activate or deactivate it globally from the `laravel-table.enable_number_of_rows_per_page_choice` config boolean value
* Override global activation status by executing the `numberOfRowsPerPageChoiceEnabled()` method on your table

```php
class UsersTable extends AbstractTableConfiguration
{
    protected function table(Table $table): void
    {
        $table->model(User::class)->numberOfRowsPerPageChoiceEnabled(false);
    }
}
```

Following the same logic, you'll be able to define the number of rows per page options that will be available to select:
* Set options globally from the `laravel-table.number_of_rows_per_page_options` config array value
* Override global options by executing the `numberOfRowsPerPageOptions()` method on your table

The first of the table defined options will be selected and applied on initialization.

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
