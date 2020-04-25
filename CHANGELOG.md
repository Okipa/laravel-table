# Changelog

## [1.3.0](https://github.com/Okipa/laravel-table/compare/1.2.7...1.3.0)

2020-04-25

* Tests have been migrated from Travis to Github actions.
* Added PHP7.4 support.
* Added Laravel 7 support.
* Dropped Laravel support before 5.8 version.
* Dropped PHP support before 7.2 version.
* Reworked documentation.

## [1.2.7](https://github.com/Okipa/laravel-table/compare/1.2.6...1.2.7)

2020-04-03

* Fixed missing column when the `show` action is the only one defined.

## [1.2.6](https://github.com/Okipa/laravel-table/compare/1.2.5...1.2.6)

2020-01-05

* Replaced hard-coded `info` action icon by config value.

## [1.2.5](https://github.com/Okipa/laravel-table/compare/1.2.4...1.2.5)

2019-10-15

* Fixed the translations publication and overriding as specified on the Laravel documentation : https://laravel.com/docs/packages#translations.
* Changed the command to publish the translations to : `php artisan vendor:publish --tag=laravel-table:translations`
* Changed the command to publish the configuration to : `php artisan vendor:publish --tag=laravel-table:config`
* Changed the command to publish the views to : `php artisan vendor:publish --tag=laravel-table:views`
* Improved testing with Travis CI (added some tests with `--prefer-lowest` composer tag to check the package compatibility with the lowest dependencies versions).

## [1.2.4](https://github.com/Okipa/laravel-table/compare/1.2.3...1.2.4)

2019-10-09

* Transferred PhpUnit builds tasks from Scrutinizer to Travis CI.
* Transferred code coverage storage from Scrutinizer to Coveralls.
* Re-authorized PHP7.1 as minimal version.

## [1.2.3](https://github.com/Okipa/laravel-table/compare/1.2.2...1.2.3)

2019-09-13

* The model is now directly passed to the route during the table `show`, `edit` and `destroy` routes generation instead of its id.
```php
// assuming your declared your edit route like this :
(new Table)->model(User::class)->routes([
    // ...
    'edit'    => ['name'=> 'user.edit', 'params' => ['foo' => 'bar']],
    //...
])
// the route will be generated like this during the table instantiation :
route('user.edit, [$user, 'foo' => 'bar']);
// instead of this way
route('user.edit, [$user->id, 'foo' => 'bar']);
```

## [1.2.2](https://github.com/Okipa/laravel-table/compare/1.2.1...1.2.2)

2019-09-13

* Fixed params order when generating the table routes. The table model id was not positioned at first when declaring other parameters.
```php
// with a route declared like this :
Route::get('user/edit/{user}/{foo}', 'UsersController@edit')->name('user.edit');
// and a table routes declaration like this :
(new Table)->model(User::class)->routes([
    // ...
    'edit'    => ['name'=> 'user.edit', 'params' => ['bar']],
    //...
])
// the route is now correctly generated and gives : /user/edit/1/bar instead of /user/edit/bar/1
```

## [1.2.1](https://github.com/Okipa/laravel-table/compare/1.2.0...1.2.1)

2019-09-13

* Fixed the `show`, `edit` and `destroy` route generation, since Laravel 6 does handle differently the key given in the `route()` helper call :
```php
// assuming your declared your edit route like this :
(new Table)->model(User::class)->routes([
    // ...
    'edit'    => ['name'=> 'user.edit', 'params' => ['foo' => 'bar']],
    //...
])
// the route will be generated like this during the table instantiation :
route('user.edit, [$user->id, 'foo' => 'bar']);
// instead of this way
route('user.edit, ['id' => $user->id, 'foo' => 'bar']);
```

## [1.2.0](https://github.com/Okipa/laravel-table/compare/1.1.0...1.2.0)

2019-09-04

* Added compatibility for Laravel 6.

## [1.1.0](https://github.com/Okipa/laravel-table/compare/1.0.13...1.1.0)

2019-08-02

* Added the possibility to add an identifier to a table with `->identifier('your identifier')`. This identifier will be used for several things :
  * It will be added as an id (formatted as a slug string) to the table itself.
  * It will be used to automatically customize the following interaction fields sent to the table, in order to be able to interact with a specific table if you have several of them on a single view : `rows`, `search`, `sort_by`, `sort_dir`.
* :warning: if you have published the views, you will have to re-publish them.  

## [1.0.13](https://github.com/Okipa/laravel-table/compare/1.0.12...1.0.13)

2019-05-14

* Fixed a use case when no sortable columns are defined and an empty `orderBy` is called in the SQL request, causing an exception with MySQL.

## [1.0.12](https://github.com/Okipa/laravel-table/compare/1.0.11...1.0.12)

2019-05-09

* Locked project compatibility to Laravel 5.5+ and PHP7.2+ to avoid issues.
* Improved code phpdoc for better maintainability.

## [1.0.11](https://github.com/Okipa/laravel-table/compare/1.0.10...1.0.11)

2019-05-06

* Added `show` to the list of available routes. - _[@Dranthos](https://github.com/Dranthos)_
* Added Spanish translation. - _[@Dranthos](https://github.com/Dranthos)_
* Wrapped sortable columns titles in order to avoid line jump between the sort icon and the column title (Issue #14).
* Improved rows number selection and search bar template to fix wrong display the rows number is disabled for example (Issue #15).
* Added possibility to show all the models contained in database with `->rowsNumber(null)` chained on the `Table` instance (Issue #16).

## [1.0.10](https://github.com/Okipa/laravel-table/compare/1.0.9...1.0.10)

2019-02-21

* Fixed a templating problem when disabling a line (one `td` html tag was missing).

## [1.0.9](https://github.com/Okipa/laravel-table/compare/1.0.8...1.0.9)

2019-02-21

* Updated design in order to respect the bootstrap basics.
* Updated config architecture to improve the logic.
* The `edit` and `destroy` buttons are now hidden when a line is disabled.
* Improved compatibility with `postgres` for the searching action, using `ILIKE` instead of `LIKE` operator for case-insensitive searching.

## [1.0.8](https://github.com/Okipa/laravel-table/compare/1.0.7...1.0.8)

2019-02-21

* Updated the result displaying in one and only `td` html tag : the title is displayed on the left and the result html on the right.
* Also fixed the result classes rendering location, which is now on the `tr` html tag and no more on the `td` html tags.

## [1.0.7](https://github.com/Okipa/laravel-table/compare/1.0.6...1.0.7)

2019-02-19

* **Possible breaking change :** reverted last tag features => removed the capacity to add some result outputs with the `->result()` method the Column objects.
* **Possible breaking change :** reverted last tag features => removed the capacity to override default classes (config) for the results cells with the Table `->resultClasses()` method.
* Added the capacity to append some results objects to the table with the `->result()` method with the following methods :
  * `->title()` : Set the result row title.
  * `->html()` : Display a HTML output for the result row. The closure let you manipulate the following attributes : `\Illuminate\Support\Collection $displayedList`.
  * `->classes()` : Override the default results classes and apply the given classes only on this result row. The default result classes are managed by the `config('laravel-table.classes.results')` value.
* Added the capacity to manage a custom results template path in the config and with the `->$resultsComponentPath()` method.

## [1.0.6](https://github.com/Okipa/laravel-table/compare/1.0.5...1.0.6)

2019-02-19

* Added the capacity to add some result outputs with the `->result()` method the Column objects.
* Added the capacity to override default classes (config) for the results cells with the Table `->resultClasses()` method.
* Improved accessibility by adding `scope` attributes to correct td html tags.

## [1.0.5](https://github.com/Okipa/laravel-table/compare/1.0.4...1.0.5)

2019-02-18

* Updated `thead` and `tfoot` components in order to improve the responsive behavior.

## [1.0.4](https://github.com/Okipa/laravel-table/compare/1.0.3...1.0.4)

2019-02-17

* `->appends()` does now also add appended attributes to search canceling and sorting actions.

## [1.0.3](https://github.com/Okipa/laravel-table/compare/1.0.2...1.0.3)

2019-02-15

* `->appends()` method does now add appended key values to rows number selection form and to searching form as hidden fields.

## [1.0.2](https://github.com/Okipa/laravel-table/compare/1.0.1...1.0.2)

2019-02-15

* Fixed searching queries process on regular table columns fields when aliased tables are declared in the the `->query()` table method.

## [1.0.1](https://github.com/Okipa/laravel-table/releases/tag/1.0.1)

2019-02-13

* Merged pull request https://github.com/Okipa/laravel-table/pull/2 : wrapped searching queries into a `->where()` clause to avoid wrong behaviours when searching values. Thanks to https://github.com/costeirs for the PR.
