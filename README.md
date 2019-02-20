# Laravel Table

[![Source Code](https://img.shields.io/badge/source-okipa/laravel--table-blue.svg)](https://github.com/Okipa/laravel-table)
[![Latest Version](https://img.shields.io/github/release/okipa/laravel-table.svg?style=flat-square)](https://github.com/Okipa/laravel-table/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/okipa/laravel-table.svg?style=flat-square)](https://packagist.org/packages/okipa/laravel-table)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![Build Status](https://scrutinizer-ci.com/g/Okipa/laravel-table/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Okipa/laravel-table/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/Okipa/laravel-table/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Okipa/laravel-table/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Okipa/laravel-table/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Okipa/laravel-table/?branch=master)

Laravel Table allows you to easily render tables in your views, directly built from PHP code.  
This package is shipped with a pre-configuration for `Bootstrap 4.*` and `FontAwesome 5`.  
**However**, the templates customization makes it very simple to use with another UI framework.

Give it a try !

------------------------------------------------------------------------------------------------------------------------

## Table of Contents
- [Installation](#installation)
- [Configuration](#configuration)
- [Customize translations](#customize-translations)
- [Customize templates](#customize-templates)
- [Table API](#table-api)
  - [->model()](#-model)
  - [->request()](#-request)
  - [->routes()](#-routes)
  - [->destroyConfirmationHtmlAttributes()](#-destroyconfirmationhtmlattributes)
  - [->rowsNumber()](#-rowsnumber)
  - [->rowsNumberSelectionActivation()](#-rowsnumberselectionactivation)
  - [->query()](#-query)
  - [->appends()](#-appends)
  - [->containerClasses()](#-containerclasses)
  - [->tableClasses()](#-tableclasses)
  - [->trClasses()](#-trclasses)
  - [->thClasses()](#-thclasses)
  - [->tdClasses()](#-tdclasses)
  - [->rowsConditionalClasses()](#-rowsconditionalclasses)
  - [->disableRows()](#-disableRows)
  - [->tableTemplate()](#-tabletemplate)
  - [->theadTemplate()](#-theadtemplate)
  - [->tbodyTemplate()](#-tbodytemplate)
  - [->resultsTemplate()](#-resultstemplate)
  - [->tfootTemplate()](#-tfoottemplate)
  - [->column()](#-column)
  - [->result()](#-result)
- [Column API](#column-api)
  - [->classes()](#-classes)
  - [->title()](#-title)
  - [->sortable()](#-sortable)
  - [->searchable()](#-searchable)
  - [->dateTimeFormat()](#-datetimeformat)
  - [->button()](#-button)
  - [->link()](#-link)
  - [->icon()](#-icon)
  - [->stringLimit()](#-stringlimit)
  - [->value()](#-value)
  - [->html()](#-html)
- [Result API](#result-api)
  - ->title()
  - ->html()
  - ->classes()
- [Tips](#tips)
- [Usage examples](#usage-examples)
  - [Basic](#basic)
  - [Advanced](#advances)
- [Changelog](#changelog)
- [Testing](#testing)
- [Credits](#credits)
- [Licence](#license)

------------------------------------------------------------------------------------------------------------------------

## Installation

- Install the package with composer :
```bash
composer require okipa/laravel-table
```

------------------------------------------------------------------------------------------------------------------------

## Configuration
To personalize the package configuration, you have to publish it with the following command :
```bash
php artisan vendor:publish --tag=laravel-table::config
```
Then, override the `config/laravel-table.php` file with you own configuration values.

------------------------------------------------------------------------------------------------------------------------

## Customize translations
You can customize the package translation by publishing them in your project :
```bash
php artisan vendor:publish --tag=laravel-table::translations
```
Once you have published them, override them from your `resources/lang/[locale]\laravel-table.php` directory.

------------------------------------------------------------------------------------------------------------------------

## Customize templates

To modify or use your own template, you will have to publish the package blade templates in your project :
```bash
php artisan vendor:publish --tag=laravel-table::views
```
Then, play with the templates in your `resources/views/vendor/laravel-table` directory.

------------------------------------------------------------------------------------------------------------------------

## Table API
:warning: All the following methods are chainable with `\Okipa\LaravelTable\Table` object **except the [->column()](#-column) and the  [->result()](#-result)method** (returning respectively `\Okipa\LaravelTable\Column` and `\Okipa\LaravelTable\Result` objects).

### `->model()`
> Set the model used during the table generation.

**Notes:**
- Signature : `model(string $tableModel): \Okipa\LaravelTable\Table`
- Required

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->model(\App\News::class);
```

### `->request()`
> Set the request used for the table generation.

**Notes:**
- Signature : `request(Request $request): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
// example in a controller
public function index(Request $request) {
    app(\Okipa\LaravelTable\\Okipa\LaravelTable\Table::class)->request($request);
    // ...
}
```

### `->routes()`
> Set the routes used during the table generation.  
> The routes declarations will be used for the following features :
> - `index` (required) : used for the rows number definition, sort and search features.
> - `create` (optional) : the **create** button is displayed if this route is declared. The button used this route to redirect to the model creation page. 
> - `edit` (optional) : the **edit** button is displayed on each row if this route is declared. The route is used to redirect to the model edition page and takes the model `id` param by default (all other params will be appended to this one).
> - `destroy` (optional) : the **destroy** button is displayed on each row if this route is declared. The route is used to trigger the model destroy action and takes the model `id` param by default (all other params will be appended to this one).

**Note :**
- Signature : `routes(array $routes): \Okipa\LaravelTable\Table`
- Required
- Each route have to be defined with the following structure :
```php
'index' => [
    // required
    'name' => 'news.index',
    // optional
    'params' => [
        // set route params
        // or let the array empty
    ]
]
```

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->routes([
    'index' => ['name' => 'news.index'],
    'create' => ['name' => 'news.create', 'params' => ['param1' => 'value1']],
    'edit' => ['name' => 'news.edit', 'params' => ['param2' => 'value2']],
    'destroy' => ['name' => 'news.destroy'],
]);
```

### `->rowsNumber`
> Override the number of rows to display on the table.  
> The default number of displayed rows is defined in the `config('laravel-table.rows.number.default')` config value.

**Note :**
- Signature : `rowsNumber(int $rows): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->rowsNumber(50);
```

### `->rowsNumberSelectionActivation()`
> Override the default rows number selection activation status.  
> Calling this method displays a rows number input that enable the user to choose how much rows to show.  
> The default rows number selection activation status is managed by the `config('laravel-table.rows.number.selection')` value.

**Note :**`
- Signature : `rowsNumberSelectionActivation($activate = true): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->rowsNumberSelectionActivation(false);
```

### `->query()`
> Set the query closure that will be executed during the table generation.  
> For example, you can define your joined tables here.  
> The closure let you manipulate the following attribute : `$query`.

**Note :**
- Signature : `query(Closure $queryClosure): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->query(function($query){
    $query->select('users.*');
    $query->addSelect('companies.name as company');
    $query->join('users', 'users.id', '=', 'companies.owner_id');
});
```

### `->appends()`
> Add an array of arguments to append to the paginator and to the following table actions :
> - row number selection
> - searching
> - search canceling
> - sorting.

**Note :**
- Signature : `appends(array $appendedValues): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->appends(request()->only('status'));
```

### `->containerClasses()`
> Override default table container classes.  
> The default container classes are defined in the `config('laravel-table.classes.container')` config value.

**Note :**
- Signature : `containerClasses(array $containerClasses): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->containerClasses(['set', 'your', 'classes']);
```

### `->tableClasses()`
> Override default table classes.  
> The default table classes are defined in the `config('laravel-table.classes.table')` config value.

**Note :**
- Signature : `tableClasses(array $tableClasses): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->tableClasses(['set', 'your', 'classes']);
```

### `->trClasses()`
> Override default table tr classes.  
> The default tr classes are defined in the `config('laravel-table.classes.tr')` config value.

**Note :**
- Signature : `trClasses(array $trClasses): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->trClasses(['set', 'your', 'classes']);
```

### `->thClasses()`
> Override default table tr classes.  
> The default th classes are defined in the `config('laravel-table.classes.th')` config value.

**Note :**
- Signature : `thClasses(array $thClasses): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->thClasses(['set', 'your', 'classes']);
```

### `->tdClasses()`
> Override default table td classes.  
> The default td classes are defined in the `config('laravel-table.classes.td')` config value.

**Note :**
- Signature : `tdClasses(array $tdClasses): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->tdClasses(['set', 'your', 'classes']);
```

### `->rowsConditionalClasses()`
> Set rows classes when the given conditions are respected.  
> The closure let you manipulate the following attribute : `$model`.
`
**Note :**
- Signature : `rowsConditionalClasses(Closure $rowClassesClosure, array $rowClasses): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->rowsConditionalClasses(function($model){
    return $model->hasParticularAttribute;
}, ['set', 'your', 'classes']);
```

### `->destroyConfirmationHtmlAttributes()`
> Define html attributes on the destroy buttons to handle dynamic javascript destroy confirmations.  
> **Beware :** the management of the destroy confirmation is on you, if you do not setup a javascript treatment to ask a confirmation, the destroy action will be directly executed.

**Note :**
- Signature : `destroyConfirmationHtmlAttributes(Closure $destroyConfirmationClosure): \Okipa\LaravelTable\Table`
- Optional (but strongly recommended !)

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->destroyHtmlAttributes(function($model){
    return ['data-confirm' => __('Are you sure you want to delete the user :name ?', [
        'name' => $model->name
    ])];
});
```

**Javascript snippet example :**
```javascript
// example of javascript snippet to ask a confirmation before executing the action
// this example assume that a bootstrap modal component has been included in your view
// https://getbootstrap.com/docs/4.3/components/modal/#modal-components
const destroyButton = $('form.destroy button[type="submit"]');
destroyButton.click(function(e){
    e.preventDefault();
    const $this = $(e.target);
    const message = $this.data('confirm');
    const confirmationModal = $('#confirmationModal');
    confirmationModal.find('.modal-body').text(message);
    confirmationModal.modal('show');
})
```

### `->disableRows()`
> Set the disable lines closure that will be executed during the table generation.  
> The optional second param let you override the classes that will be applied for the disabled lines.  
> By default, the « config('laravel-table.rows.disabled.classes') » config value is applied.  
> For example, you can disable the current logged user to prevent him being edited or deleted from the table.  
> The closure let you manipulate the following attribute : `$model`.

**Note :**
- Signature : `disableRows(Closure $rowDisableClosure, array $classes = []): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->disableRows(function($user){
    return $user->id = auth()->id;
}, ['bg-danger', 'text-primary']);
```

### `->tableTemplate()`
> Set a custom template path for the table component.  
> The default table template path is defined in the `config('laravel-table.template.table')` config value.

**Note :**
- Signature : `tableTemplate(string $tableComponentPath): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->tableTemplate('tailwindCss.table');
```

### `->theadTemplate()`
> Set a custom template path for the thead component.  
> The default thead template path is defined in the `config('laravel-table.template.thead')` config value.

**Note :**
- Signature : `theadTemplate(string $theadComponentPath): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->theadTemplate('tailwindCss.thead');
```

### `->tbodyTemplate()`
> Set a custom template path for the tbody component.  
> The default tbody template path is defined in the `config('laravel-table.template.tbody')` config value.

**Note :**
- Signature : `tbodyTemplate(string $tbodyComponentPath): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->tbodyTemplate('tailwindCss.tbody');
```

### `->resultsTemplate()`
> Set a custom template path for the results component.  
> The default results template path is defined in the `config('laravel-table.template.results')` config value.

**Note :**
- Signature : `resultsTemplate(string $resultsComponentPath): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->resultsComponentPath('tailwindCss.results');
```

### `->tfootTemplate()`
> Set a custom template path for the tfoot component.  
> The default tfoot template path is defined in the `config('laravel-table.template.tfoot')` config value.

**Note :**
- Signature : `tfootTemplate(string $tfootComponentPath): \Okipa\LaravelTable\Table`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->tfootTemplate('tailwindCss.tfoot');
```

### `->column()`
> Add a column that will be displayed in the table.  
> The column key is optional if the column is not declared as sortable or searchable.

**Note :**
- Signature : `column(string $databaseColumn = null): \Okipa\LaravelTable\Column`
- Required
- **Warning : ** this method should not be chained with the other `\Okipa\LaravelTable\Table` methods because it returns a `\Okipa\LaravelTable\Column` object. See the use case examples to check how to use this method.

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->column('email');
```

### `->result()`
> Add a result row that will be displayed at the bottom of the table.  

**Note :**
- Signature : `result(): Result`
- Optional
- **Warning : ** this method should not be chained with the other `\Okipa\LaravelTable\Table` methods because it returns a `\Okipa\LaravelTable\Result` object. See the use case examples to check how to use this method.

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->result();
```

## Column API
:warning: All the column methods are chainable with `\Okipa\LaravelTable\Column` object.

### `->classes()`
> Set the custom classes that will be applied on this column only.

**Note :**
- Signature : `classes(array $classes): \Okipa\LaravelTable\Column`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->column()->classes(['font-weight-bold']);
```

### `->title()`
> Set the column title or override the default (`__('validation.attributes.[column key])`) title generated from the column name.

**Note :**
- Signature : `title(string $title = null): \Okipa\LaravelTable\Column`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->column()->title('E-mail');
```

### `->sortable()`
> Make the column sortable.  
> You also can choose to set the column sorted by default.  
> If no column is sorted by default, the first one will be automatically sorted.

**Note :**
- Signature : `sortable(bool $sortByDefault = false, $sortDirection = 'asc'): \Okipa\LaravelTable\Column`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->column('email')->sortable();
// alternative
(new \Okipa\LaravelTable\Table)->column('email')->sortable(true, 'desc');
```

### `->searchable()`
> Make the column searchable.  
> The first param allows you to precise the searched database table (can references a database table alias).  
> The second param allows you to precise the searched database attributes (if not precised, the table database column is searched).

**Note :**
- Signature : `public function searchable(string $databaseSearchedTable = null, array $databaseSearchedColumns = []): \Okipa\LaravelTable\Column`
- Optional

**Use case example :**
```php
// example 1
(new \Okipa\LaravelTable\Table)->column('email')->searchable();
// example 2
$table = (new \Okipa\LaravelTable\Table)->model(\App\User::class)->query(function($query) {
    $query->select('users.*');
    $query->addSelect('companies.name as company');
    $query->join('companies', 'companies.owner_id', '=', 'users.id');
});
$table->column('company')->searchable('companies', ['name']);
// example 3
$table = (new \Okipa\LaravelTable\Table)->model(\App\User::class)->query(function($query) {
    $query->select('users.*');
    $query->addSelect(\DB::raw('CONCAT(companies.name, " ", companies.activity) as company'));
    $query->join('companies as companiesAliasedTable', 'companies.owner_id', '=', 'users.id');
});
$table->column('company')->searchable('companiesAliasedTable', ['name', 'activity']);
```

### `->dateTimeFormat()`
> Set the format for a datetime, date or time database column (optional).  
> (Carbon::parse($value)->format($format) method is used under the hood).

**Note :**
- Signature : `dateTimeFormat(string $dateTimeFormat): \Okipa\LaravelTable\Column`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->column('created_at')->dateTimeFormat('d/m/Y H:i');
```

### `->button()`
> Display the column as a button with the given classes.

**Note :**
- Signature : `button(array $buttonClasses = []): \Okipa\LaravelTable\Column`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->column('email')->button(['btn', 'btn-sm', 'btn-primary']);
```

### `->link()`
> Wrap the column value into a `<a></a>` component.  
> You can declare the link as a string or as a closure which will let you manipulate the following attributes : `$model`, `$column`.  
> If no url is declared, it will be set with the column value.

**Note :**
- Signature : `link($url = null): \Okipa\LaravelTable\Column`
- Optional

**Use case example :**
```php
// example 1
(new \Okipa\LaravelTable\Table)->column('url')->link();
// example 2
(new \Okipa\LaravelTable\Table)->column()->link(route('news.index'));
// example 3
(new \Okipa\LaravelTable\Table)->column()->link(function($news) {
    return route('news.show', $news);
});
```

### `->icon()`
> Add an icon before the displayed value.  
> Set the second param as true if you want the icon to be displayed even if the column has no value.

**Note :**
- Signature : `icon(string $icon, bool $displaydisplayIconWhenNoValue = false): \Okipa\LaravelTable\Column`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->column('email')->icon('<i class="fas fa-envelope"></i>', true);
```

### `->stringLimit()`
> Set the string value display limitation.  
> Shows "..." when the limit is reached.

**Note :**
- Signature : `stringLimit(int $stringLimit): \Okipa\LaravelTable\Column`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->column('email')->stringLimit(30);
```

### `->value()`
> Display a custom value for the column.  
> The closure let you manipulate the following attributes : `$model`, `$column`.

**Note :**
- Signature : `value(Closure $valueClosure): \Okipa\LaravelTable\Column`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->column()->value(function($user) {
    return config('users.type.' . $user->type_id);
});
```

### `->html()`
> Display a custom HTML for the column.  
> The closure let you manipulate the following attributes : `$model`, `$column`.

**Note :**
- Signature : `html(Closure $htmlClosure): \Okipa\LaravelTable\Column`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->column()->html(function($user) {
    return '<div>' . $user->first_name . '</div>';
});
```

## Result API
:warning: All the result methods are chainable with `\Okipa\LaravelTable\Result` object.

### `->title()`
> Set the result row title.

**Note :**
- Signature : `title(string $title): \Okipa\LaravelTable\Result`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->result()->title('Turnover total');
```

### `->html()`
> Display a HTML output for the result row.  
> The closure let you manipulate the following attributes : `$displayedList`.

**Note :**
- Signature : `html(Closure $htmlClosure): \Okipa\LaravelTable\Result`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->result()->html(function($displayedList) {
    return $displayedList->sum('turnover');
});
```

### `->classes()`
> Override the default results classes and apply the given classes only on this result row.  
> The default result classes are managed by the `config('laravel-table.classes.results')` value.

**Note :**
- Signature : `classes(array $classes): \Okipa\LaravelTable\Result`
- Optional

**Use case example :**
```php
(new \Okipa\LaravelTable\Table)->result()->classes(['bg-dark', 'text-white', 'font-weight-bold']);
```

------------------------------------------------------------------------------------------------------------------------

## Tips
- **Request :** No need to transmit the request to the table : it systematically uses the current request given by the `request()` helper to get the number of lines to show and the searching, sorting or pagination data. However, if you need to pass a particular request to the table, you can do it with the `->request()` method.
- **Column titles :** By default, the table columns titles take the following value : `__('validation.attributes.[databaseColumn])`. You can set a custom title using the `title()` method.
- **Columns displaying combination :** The following table column methods can be combined to display a result as wished. If you can't get the wanted result, you should use the `->html()` method to build a custom display.
  - `->button()`
  - `->link()`
  - `->icon()`
  - `->stringLimit()`
  - `->value()`

------------------------------------------------------------------------------------------------------------------------

## Usage examples

### Basic
In your controller, simply call the package like the following example to generate your table :
```php
$table = (new \Okipa\LaravelTable\Table)->model(\App\News::class)->routes(['index' => ['name' => 'news.index']]);
$table->column('title')->sortable()->searchable();
```
Then, send your `$table` object in your view and render your table like this :
```blade
{{ $table }}
```
That's it !

### Advanced
If you need your table for a more advanced usage, with a multilingual project for example, here is an example of what you can do in your controller :
```php
$table = (new \Okipa\LaravelTable\Table)->model(\App\News::class)
    ->request($request)
    ->routes([
        'index'      => ['name' => 'news.index'],
        'create'     => ['name' => 'news.create'],
        'edit'       => ['name' => 'news.edit'],
        'destroy'    => ['name' => 'news.destroy'],
    ])
    ->rowsNumber(50)
    ->rowsNumberSelectionActivation(false)
    ->query(function ($query) use ($category_id) {
        // some examples of what you can do
        $query->select('news.*');
        // add a constraint
        $query->where('category_id', $category_id);
        // get value stored in a json field
        $query->addSelect('news.json_field->>json_attribute as json_attribute');
        // get a formatted value form a pivot table
        $query->selectRaw('count(comments.id) as comments_count');
        $query->leftJoin('news_commment', 'news_commment.news_id', '=', 'news.id');
        $query->leftJoin('comments', 'comments.id', '=', 'news_commment.comment_id');
        $query->groupBy('comments.id');
        // alias a value to make it available from the column model
        $query->addSelect('users.name as author');
        $query->join('users', 'users.id', '=', 'news.author_id');
    })
    ->disableRows(function($model){
        return $model->id === 1 || $model->id === 2;
    }, ['disabled', 'bg-secondary'])
    ->rowsConditionalClasses(function($model){
        return $model->id === 3;
    }, ['highlighted', 'bg-success']);
$table->column('image')->html(function ($model, $column) {
    return $model->{$column->databaseDefaultColumn}
        ? '<img src="' . $model->{$column->databaseDefaultColumn} . '" alt="' .  $model->title . '">'
        : null;
});
$table->column('title')->sortable()->searchable();
$table->column('content')->stringLimit(30);
$table->column('author')->sortable()->searchable('user', ['name']);
$table->column('category_id')
    ->title('Category custom name')
    ->icon('your-icon')
    ->button(['btn', 'btn-sm', 'btn-outline-primary'])
    ->value(function ($model, $column) {
        return config('news.category.' . $model->{$column->databaseDefaultColumn});
    });
$table->column()->link(function($model){
    return route('news.show', ['id' => $model->id]);
})->button(['btn', 'btn-sm', 'btn-primary']);
$table->column('released_at')->sortable()->dateTimeFormat('d/m/Y H:i:s');
$table->result()->title('Total of comments')->html(function($displayedList){
    return $displayedList->sum('comments_count');
});
```

------------------------------------------------------------------------------------------------------------------------

## Changelog
Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

------------------------------------------------------------------------------------------------------------------------

## Testing
```bash
composer test
```

------------------------------------------------------------------------------------------------------------------------

## Credits
- [Okipa](https://github.com/Okipa)
- [Other contributors](https://github.com/Okipa/laravel-table/graphs/contributors)

------------------------------------------------------------------------------------------------------------------------

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
