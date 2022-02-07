# Upgrade from v4 to v5

Follow the steps below to upgrade the package.

## Removed okipa/laravel-html-helper dependency

If you do use the `laravel-html-helper` in your projet without having installed through composer, you should add it.

However, please note that this package is now useless for my packages and has been abandoned, si it will not be maintained anymore.

## Added livewire/livewire dependency

In order to remove the high complexity involved by the HTTP requests data handling, and to plug several additional features, I chose to rebuild this package to work with Livewire.

As so, you will have to install it properly before using this package : https://laravel-livewire.com/docs/installation.

## Table configuration changes

The following changes must be reported in your table configurations:
* replace `extend AbstractTable` by `extend AbstractTableConfiguration`
* replace `protected function table(): Table` function signatures by `protected function table(Table $table): void`
* replace `return (new Table())` by `$table->` at the beginning of the `table()` function
* replace `->activateRowsNumberDefinition(` calls by `->numberOfRowsPerPageChoiceEnabled(`
* replace `->rowsNumber(` calls by `->numberOfRowsPerPageOptions(` (do not forget to provide an array of integers instead of an integer)

## Table displaying changes

You do not have to instantiate your configuration in your controller anymore.

You should search for `$table =` or `->setup()` in your controller and remove all these instantiations.

Then, replace all the `{{ $table }}` calls from your views by the Livewire dedicated component with you table configuration passed in parameter:

```blade
<x:livewire.table :config="UsersTable::class"/>
```

## Translation changes

* `Number of rows` becomes `Number of rows per page`
* `Loading in progress...` has been added

## See all changes

See all change with the [comparison tool](https://github.com/Okipa/laravel-table/compare/3.0.0...4.0.0).

## Undocumented changes

If you see any forgotten and undocumented change, please submit a PR to add them to this upgrade guide.
