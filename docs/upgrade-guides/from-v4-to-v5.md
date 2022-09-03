# Upgrade from v4 to v5

Follow the steps below to upgrade the package.

## Removed okipa/laravel-html-helper dependency

If you do use the `laravel-html-helper` in your projet without having installed through composer, you should add it.

However, please note that this package is now useless for all my packages and has been abandoned, so it will not be maintained anymore.

## Added livewire/livewire dependency

In order to reduce the high complexity involved by the HTTP requests data handling, and in order to plug several additional features, I chose to rebuild this package to work with Livewire.

As so, you will have to install it properly before using this package : https://laravel-livewire.com/docs/installation.

## Config changes

The package config file changed entirely. If you published it, you'll have to delete it, [re-publish it](../../README.md#configuration) and reapply your changes.

## Translation changes

Here are the words and sentences that were translatable in the v4:
* `Create`
* `Show`
* `Edit`
* `Destroy`
* `Number of rows`
* `Search by:`
* `Reset research`
* `Actions`
* `No results were found.`
* `Showing results <b>:start</b> to <b>:stop</b> on <b>:total</b>`

There are new translations in v5 that [you'll find here](../../README.md#translations), you should add them to your locale json file if you need to get them translated.

## Template changes

Templates have changed entirely as the package has migrated to work with Livewire. If you published them, you'll have to [re-publish them](../../README.md#templates) and reapply your changes.

## Table configuration changes

Table configuration signature has changed and now look this way:

```
namespace App\Tables;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Result;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()->model(<model_namespace>);
    }

    protected function columns(): array
    {
        return [
            Column::make('<model_attribute>')->...
            // ... Other column declarations
        ];
    }
    
    // Optional: do not declare this method if no results are displayed on the table
    protected function results(): array
    {
        return [
            Result::make()->...
            // ... Other result declarations
        ];
    }
}
```

As so, the following changes must be reported in your table configurations:
* Replace `extend AbstractTable` by `extend AbstractTableConfiguration`
* Replace `return (new Table())` by `return Table::make()->` at the beginning of the `table()` function
* Replace `protected function columns(Table $table): void` function signatures by `protected function columns(): array`
* replace columns declaration previously done with `$table->column()` by [new column declaration](../../README.md#declare-columns-on-tables)
* Replace `protected function results(Table $table): void` function signatures by `protected function results(): array`
* replace results declaration previously done with `$table->results()` by [new result declaration](../../README.md#declare-results-on-tables)

## Table API changes

* The method `identifier` has been removed as it is now useless
* The method `request` has been removed as it is now useless
  * If you need to pass external data to your table, [do it this way](../../README.md#pass-external-data-to-your-tables)
* The method `routes` has been removed
  * Index route is now useless as the whole table is handled with Livewire
  * Create route has now to be configured as [head action](../../README.md#define-table-head-action)
  * Show/Edit/Delete routes have now to be configured as [row actions](../../README.md#define-table-row-actions)
* The method `rowsNumber` has been renamed `numberOfRowsPerPageOptions` and [has changed its signature](../../README.md#handle-tables-number-of-rows-per-page-pagination-and-navigation-status)
* The method `activateRowsNumberDefinition` has been renamed `enableNumberOfRowsPerPageChoice`
* The method `appendData` has been removed as it is now useless
* The method `containerClasses` has been removed
* The method `tableClasses` has been removed
* The method `trClasses` has been removed
* The method `thClasses` has been removed
* The method `tdClasses` has been removed
* The method `rowsConditionalClasses` has been renamed to `rowClass` and [has changed its signature](../../README.md#set-conditional-row-class)
* The method `destroyConfirmationHtmlAttributes` has been removed
  * Actions confirmations and feedbacks have now to be handled with [a few lines of JavaScript that you'll have to add to your project](../../README.md#set-up-a-few-lines-of-javascript)
* The method `disableRows` has been removed
  * You'll have to use the [rowClass](../../README.md#set-conditional-row-class) method to set custom styles to rows
  * You'll have the ability to independently enable/disable [bulk actions](../../README.md#define-table-bulk-actions), [row actions](../../README.md#define-table-row-actions) and [column actions](../../README.md#define-column-actions)
* The method `tableTemplate` has been removed
* The method `theadTemplate` has been removed
* The method `rowsSearchingTemplate` has been removed
* The method `rowsNumberDefinitionTemplate` has been removed
* The method `createActionTemplate` has been removed
* The method `columnTitlesTemplate` has been removed
* The method `tbodyTemplate` has been removed
* The method `showActionTemplate` has been removed
* The method `editActionTemplate` has been removed
* The method `destroyActionTemplate` has been removed
* The method `resultsTemplate` has been removed
* The method `tfootTemplate` has been removed
* The method `navigationStatusTemplate` has been removed
* The method `paginationTemplate` has been removed
* The method `column` has been removed
  * There is now a [new way to define columns](../../README.md#define-column-actions)
* The method `result` has been removed
  * There is now a [new way to define results](../../README.md#declare-results-on-tables)

## Column API changes

* The method `classes` has been removed
* The method `sortable` [has changed its signature](../../README.md#configure-columns-sorting)
* The method `searchable` [has changed its signature](../../README.md#configure-columns-searching)
* The method `dateTimeFormat` has been removed
  * Column formatting is [now handled differently](../../README.md#format-column-values) and a built-in formater is available to replace it
* The method `button` has been removed
  * [New column formatting feature](../../README.md#format-column-values) can be used to format values the way you want
* The method `link` has been removed
  * [New column formatting feature](../../README.md#format-column-values) can be used to format values the way you want
* The method `prependHtml` has been removed
  * [New column formatting feature](../../README.md#format-column-values) can be used to format values the way you want
* The method `appendsHtml` has been removed
  * [New column formatting feature](../../README.md#format-column-values) can be used to format values the way you want
* The method `stringLimit` has been removed
  * Column formatting is [now handled differently](../../README.md#format-column-values) and a built-in formater is available to replace it
* The method `value` has been removed
  * [New column formatting feature](../../README.md#format-column-values) can be used to format values the way you want
* The method `html` has been removed
  * [New column formatting feature](../../README.md#format-column-values) can be used to format values the way you want

## Result API changes

* The method `html` has been removed
  * Result formatting is [now handled differently](../../README.md#declare-results-on-tables)
* The method `classes` has been removed

## Table displaying changes

You do not have to instantiate your configuration in your controller anymore.

You should search for `$table =` or `->setup()` in your controller and remove all these instantiations.

Then, replace all the `{{ $table }}` calls from your views by the Livewire dedicated component with you table configuration passed in parameter, [as explained here](../../README.md#display-tables-in-views):

```blade
<livewire:table :config="App\Tables\UsersTable::class"/>
```

If you wish to pass data to your table configuration, [do it this way](../../README.md#pass-external-data-to-your-tables).

## See all changes

See all change with the [comparison tool](https://github.com/Okipa/laravel-table/compare/3.0.0...4.0.0).

## Undocumented changes

If you see any forgotten and undocumented change, please submit a PR to add them to this upgrade guide.
