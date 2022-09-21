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
* Replace each declaration of `use Okipa\LaravelTable\Abstracts\AbstractTable` by `use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;`
* Replace each declaration of `extends AbstractTable` by `extends AbstractTableConfiguration`
* Replace each declaration of `return (new Table())->` by `return Table::make()->` at the beginning of the `table()` function
* Replace each declaration of `protected function columns(Table $table): void` function signatures by `protected function columns(): array`
  * An array must now be returned by this method, make sure that all column declarations are now encapsulated into an array
* Replace each declaration of `$table->column(` by `Column::make(` at the beginning of the `columns()` function
  * More details on the [new way to define columns](../../README.md#define-column-actions)
* Replace each declaration of `protected function results(Table $table): void` function signatures by `protected function results(): array`
* Replace each declaration of `$table->result(` by `Result::make()` at the beginning of the `results()` function
  * More details on the [new way to define results](../../README.md#declare-results-on-tables)

## Table API changes

* Remove each `->identifier(` declaration, it is now useless
* Remove each `->request(` declaration, it is now useless
* The method `->routes(` has been removed
  * Remove each `ìndex` route, it is now useless as the whole table is handled with Livewire
  * Create route has now to be configured as [head action](../../README.md#define-table-head-action)
    * Remove create `create` route declarations from the `->routes()` method
    * Declare a head action instead following this example: `->headAction(new CreateHeadAction(route('user.create')))`
  * Show/Edit/Delete routes have now to be configured as [row actions](../../README.md#define-table-row-actions)
    * Declare row actions on your table following this example: `->rowActions(fn(User $user) => [])`
    * Remove create `show` route declarations from the `->routes()` method
    * Declare a `ShowRowAction` instead following this example: `new ShowRowAction(route('user.show', $user))`
    * Do the same for the `edit` route by declaring a `EditRowAction` instead following this example: `new EditRowAction(route('user.edit', $user))`
    * Do the same for the `destroy` route by declaring a `DestroyRowAction` instead following this example: `new DestroyRowAction()`
    * As destroy action will now be handled by the package, `destroy` route declaration may now be useless, you may delete them
* Replace each `->rowsNumber(` declaration by `->numberOfRowsPerPageOptions(` and [update the provided argument to match its new signature](../../README.md#handle-tables-number-of-rows-per-page-pagination-and-navigation-status)
* Replace each `->activateRowsNumberDefinition(` declaration by `->enableNumberOfRowsPerPageChoice(`
* Remove each `->appendData(` declaration, it is now useless
* Remove each `->containerClasses(` declaration
* Remove each `->tableClasses(` declaration
* Remove each `->trClasses(` declaration
* Remove each `->thClasses(` declaration
* Remove each `->tdClasses(` declaration
* Replace each `->rowsConditionalClasses(` declaration by `rowClass` and [update the provided argument to match its new signature](../../README.md#set-conditional-row-class)
* Remove each `->destroyConfirmationHtmlAttributes(` declaration
  * Actions confirmations and feedbacks will now be handled by a [few lines of JavaScript](#javascript-addition)
* Remove each `->disableRows(` declaration
  * You'll have to use the [rowClass](../../README.md#set-conditional-row-class) method to set custom styles to rows
  * You'll have the ability to independently enable/disable [bulk actions](../../README.md#define-table-bulk-actions), [row actions](../../README.md#define-table-row-actions) and [column actions](../../README.md#define-column-actions)
* Remove each `->tableTemplate(` declaration
* Remove each `->theadTemplate(` declaration
* Remove each `->rowsSearchingTemplate(` declaration
* Remove each `->rowsNumberDefinitionTemplate(` declaration
* Remove each `->createActionTemplate(` declaration
* Remove each `->columnTitlesTemplate(` declaration
* Remove each `->tbodyTemplate(` declaration
* Remove each `->showActionTemplate(` declaration
* Remove each `->editActionTemplate(` declaration
* Remove each `->destroyActionTemplate(` declaration
* Remove each `->resultsTemplate(` declaration
* Remove each `->tfootTemplate(` declaration
* Remove each `->navigationStatusTemplate(` declaration
* Remove each `->paginationTemplate(` declaration

## Column API changes

* Remove each `->classes(` declaration
* The method `->sortable(` [has changed its signature](../../README.md#configure-columns-sorting): make sure implementations are compatible with it
  * To sort a column by default, you'll now have to add the following declaration to your column: `->sortByDefault('desc') // 'asc' by default`
* The method `searchable` [has changed its signature](../../README.md#configure-columns-searching)
* Remove each `dateTimeFormat` declaration
  * Column formatting is [now handled differently](../../README.md#format-column-values) and a built-in formatter is available to replace it
  * For example, replace each `->dateTimeFormat('d/m/Y H:i', 'Europe/Paris')` declarations by `->format(new DateFormatter('d/m/Y H:i', 'Europe/Paris'))`
* Each following declarations have to be removed, they'll can be replaced by the [new column formatting feature](../../README.md#format-column-values) can be used to format values the way you want
  * `button`
  * `link`
  * `prependHtml`
  * `appendsHtml`
  * `value`
  * `html`

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

## Javascript addition

You'll have to add [a few lines of JavaScript to your project](../../README.md#set-up-a-few-lines-of-javascript) to handle table action confirmations and feedbacks.

You'll also have to install the [Livewire Sortable Plugin](https://github.com/livewire/sortable) on your project to allow the [built-in columns drag-and-drop-reordering](../../README.md#allow-columns-to-be-reordered-from-drag-and-drop-action) to work properly.

## Take advantage of the new provided features

You have now completed upgrading your tables and all features from v4 should be migrated.

However, new features are now available for you to use.

Make sur you have read the [new provided documentation](../../README.md#how-to) to take advantage of them!

## See all changes

See all change with the [comparison tool](https://github.com/Okipa/laravel-table/compare/4.0.7...5.0.0).

## Undocumented changes

If you see any forgotten and undocumented change, please submit a PR to add them to this upgrade guide.
