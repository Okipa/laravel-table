# Upgrade from v1 to v2

## Config changes

Some small config changes have been made. You should [re-publish it](../README.md#configuration).

## Translations

The translations files have been removed and will not be used anymore.

Translations have now to be handled [this way](../README.md#translations).

## Architecture and usage changes

The table configurations are now gathered in the `app/Tables` directory.

To follow the package upgrade, you should execute the following steps for each table you declared:

* Create the table configuration boilerplate. Eg: `php artisan make:table UsersTable --model=App/User`.
* Move your table configuration in the created table class.
* Call your table configuration like following (in your controller for example): `$table = new UsersTable;` and pass it to the view.
* Display your table in your view as following: `$table()` => :warning: do not display it as before just with `$table`, this won't work.

## See all changes

See all change with the [comparison tool](https://github.com/Okipa/laravel-table/compare/1.5.0...2.0.0).

## Undocumented changes

If you see any forgotten and undocumented change, please submit a PR to add them to this upgrade guide.
