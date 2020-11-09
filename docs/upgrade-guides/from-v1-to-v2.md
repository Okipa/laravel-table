# Upgrade from v1 to v2

The v2 is a major rewrite of a big part of the package.

Follow the steps below to upgrade the package.

## Config changes

Some config changes have been made. If you customized it, you should [re-publish it](../../README.md#configuration) and reapply your changes.

## Translations removed

The translations files have been removed and will not be used anymore.

Translations have now to be handled [this way](../../README.md#translations).

## Template changes

Some template changes have been made. If you customized them, you should [re-publish them](../../README.md#templates) and reapply your changes.

## Architecture and usage changes

The table configurations are now gathered in the `app/Tables` directory.

To follow the package upgrade, you should execute the following steps for each table you declared:

* Create the table configuration boilerplate. Eg: `php artisan make:table UsersTable --model=App/User`.
* Move your table configuration in the created table class.
* Call your table configuration like following (in your controller for example): `$table = (new UsersTable())->setup();` and pass it to the view.
* Display your table in your view as usual: `{{ $table }}`.

## API changes

There are small changes in the API you will have to report in your code:

* Rename each `Table` use of `->resultsComponentPath(` by `->resultsTemplatePath(`.
* Rename each `Table` use of `->icon(` by `->prependHtml(`.
* Rename each `Table` use of `->appends(` by `->appendData(`.
* Rename each `Table` use of `->rowsNumberSelectionActivation(` by `->activateRowsNumberDefinition(`.
* Rename each `Column` use of `->prepend(` by `->prependHtml(`.
* Rename each `Column` use of `->append(` by `->appendsHtml(`.

## Accessing table rows

If for any reason you need to work with the table rows, you now will have to do it this way:

```php
$table = (new UsersTable())->setup();
$rows = $table->getPaginator()->getCollection();
```

Instead of this way in previous versions:

```php
$table = (new Table());
// ... Any table configuration
$table->render();
$table->list->getCollection();
```

As so, you should search for all `$table->list` use in your code and make the necessary replacements.

## See all changes

See all change with the [comparison tool](https://github.com/Okipa/laravel-table/compare/1.5.0...2.0.0).

## Undocumented changes

If you see any forgotten and undocumented change, please submit a PR to add them to this upgrade guide.
