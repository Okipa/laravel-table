# Changelog

All notable changes to this package will be documented in this file.

## [5.3.0](https://github.com/Okipa/laravel-table/compare/5.2.2...5.3.0)

2023-01-21

* Added PHP 8.2 support and locked PHP versions to 8.1 and 8.2
* Dropped Laravel 8 support
* Added Laravel 10 support

## [5.2.2](https://github.com/Okipa/laravel-table/compare/5.2.1...5.2.2)

2023-01-01

* Fixed filter reset button missing margin-top class for Bootstrap 4 & 5 templates

## [5.2.1](https://github.com/Okipa/laravel-table/compare/5.2.0...5.2.1)

2022-11-14

* Added the ability to redefine the entire table positioning when reordering instead of just reversing some possibly incorrect positions

## [5.2.0](https://github.com/Okipa/laravel-table/compare/5.1.2...5.2.0)

2022-10-28

* Added a new built-in `RedirectRowAction`, that is now used to render the pre-configured `ShowRowAction`
* Added an optional second argument `bool $openInNewWindow = false` to the `ShowRowAction`
* Added a new pre-configured `AddHeadAction`, that is using the built-in `RedirectHeadAction`
  * Added a new `Add` translation for it that you'll have to add to [your own translations](/README.md#translations)
  * Added a new `config('laravel-table.icon.add')` config for it with the `<i class="fa-solid fa-circle-plus fa-fw"></i>` default value that you'll also have to add to [your published configuration file](/README.md#configuration)

## [5.1.2](https://github.com/Okipa/laravel-table/compare/5.1.1...5.1.2)

2022-10-27

* Improved CI by @szepeviktor in https://github.com/Okipa/laravel-table/pull/110
* Improved PHPStan config by @szepeviktor (first contribution) in https://github.com/Okipa/laravel-table/pull/109

## [5.1.1](https://github.com/Okipa/laravel-table/compare/5.1.0...5.1.1)

2022-10-26

* Fixed [reordering feature](/README.md#allow-columns-to-be-reordered-from-drag-and-drop-action) that was not compatible with packages like [spatie/eloquent-sortable](https://github.com/spatie/eloquent-sortable) (which authorize several model entries to have the same position when the sorting does group models from a query => [example](https://github.com/spatie/eloquent-sortable#grouping))

## [5.1.0](https://github.com/Okipa/laravel-table/compare/5.0.2...5.1.0)

2022-10-25
 
* Added ability to chain a `->when(bool $condition)` method to an instantiated head action, in order to enable it conditionally
* Added a new built-in `RedirectHeadAction`, that is now used to render the pre-configured `CreateHeadAction`
* Added an optional second argument `bool $openInNewWindow = false` to the `CreateHeadAction`
* Added a new [JavaScript snippet](/README.md#set-up-a-few-lines-of-javascript) to handle head action link opening in tab: you'll have to add it if you want to benefit from this new ability

## [5.0.2](https://github.com/Okipa/laravel-table/compare/5.0.1...5.0.2)

2022-10-07

* Fixed wrong `form-select` class uses for Bootstrap 4 template selects: replaced them by `custom-select`
* Fixed Column action still displays original column value with ->when(false) : #103

## [5.0.1](https://github.com/Okipa/laravel-table/compare/5.0.0...5.0.1)

2022-09-26

* Fixed `make:table <TableName> --model=<ModelNamespace>` command

## [5.0.0](https://github.com/Okipa/laravel-table/compare/4.0.4...5.0.0)

2022-09-21

* Added support for Laravel 9
* Dropped support for Laravel 7 and earlier versions
* Added support for PHP 8.1
* Dropped support for PHP 8.0 and earlier versions
* Added `livewire/livewire` dependency as the package is now based on it
* Removed `okipa/laravel-html-helper` dependency
* Replaced `phpcs/phpcbf` by `laravel/pint`
* Added several features :
  * New SPA behaviour
  * New filters ability
  * Head actions (replacing routes declarations)
  * New bulk actions ability
  * Row actions (replacing routes declarations)
  * New ability to search from closure
  * New ability to sort from closure
  * New column actions ability
  * New ability to use built-in column formatters and to create custom ones
  * New drag-and-drop reordering ability

:point_right: [See the upgrade guide](/docs/upgrade-guides/from-v4-to-v5.md)

## [4.0.7](https://github.com/Okipa/laravel-table/compare/4.0.6...4.0.7)

2022-05-17

* Fixed search wrong behaviour when rows number is defined to `null`

## [4.0.6](https://github.com/Okipa/laravel-table/compare/4.0.5...4.0.6)

2022-03-10

* Added ability to set a timezone when formatting a date/time column with the `dateTimeFormat` method:
    * Updated column `dateTimeFormat` method signature to `dateTimeFormat(string $dateTimeFormat, string $timezone = null): \Okipa\LaravelTable\Column`
    * If no timezone is set, the default one, defined in `config('app.timezone')` is used

## [4.0.5](https://github.com/Okipa/laravel-table/compare/4.0.4...4.0.5)

2022-03-01

* Fixed error when appending array data to table with the `appendData` method

## [4.0.4](https://github.com/Okipa/laravel-table/compare/4.0.3...4.0.4)

2021-08-17

* Added `$type` property to `src/Console/Commands/MakeTable` in order to display correct messages:
  * `Table already exists!`
  * `Table created successfully.`

## [4.0.3](https://github.com/Okipa/laravel-table/compare/4.0.2...4.0.3)

2021-04-11

* Updated table generation stub when called with `--model` option

## [4.0.2](https://github.com/Okipa/laravel-table/compare/4.0.1...4.0.2)

2021-01-10

* Fixed #66 : Bugfix to allow the `->rowsConditionalClasses()` method to be called several times on a table. In addition, the second `$rowClasses` argument of this method now accepts array or closure (which let you manipulate a `\Illuminate\Database\Eloquent\Model $model` attribute)
* Fixed #68 : Removed useless treatment when data is appended to paginator with `->appendData()` method, which led to `+` character addition when values were containing spaces

## [4.0.1](https://github.com/Okipa/laravel-table/compare/4.0.0...4.0.1)

2020-09-14

* Fixed wrong `okipa/laravel-html-helper` version in composer.json

## [4.0.0](https://github.com/Okipa/laravel-table/compare/3.1.3...4.0.0)

2020-09-14

* Added PHP 8 support
* Removed Scrutinizer analysis
* Updated PHPCS checker and fixer norm to PSR-12
* Upgraded https://github.com/Okipa/laravel-html-helper to v2

:point_right: [See the upgrade guide](/docs/upgrade-guides/from-v3-to-v4.md)

## [3.1.3](https://github.com/Okipa/laravel-table/compare/3.1.2...3.1.3)

2020-09-10

* Fixed wrong sensitive case searching which was involuntarily executed for JSON database fields instead of insensitive case searching as normal

## [3.1.2](https://github.com/Okipa/laravel-table/compare/3.1.1...3.1.2)

2020-08-24

* Fixed doc js snippet given in [destroyConfirmationHtmlAttributes](./README.md#table-destroyConfirmationHtmlAttributes)

## [3.1.1](https://github.com/Okipa/laravel-table/compare/3.1.0...3.1.1)

2020-08-24

* Fixed column cell value not escaped when using `$column->value()` method (https://github.com/Okipa/laravel-table/issues/54)

## [3.1.0](https://github.com/Okipa/laravel-table/compare/3.0.1...3.1.0)

2020-08-24

* Reverted the previous change (3.0.1) as the `button` method without arguments has no visual effect: added instructions in V2 to v3 the upgrade-guide to take care of this new behaviour
* Fixed an issue where create action button was not displayed when searching and rows number definition were disabled
* Fixed a v3 regression where `rows number definition` was wrongly named `rows number selection` at different places (config, templates, methods, ...): this is an unfortunately breaking change if you published config or templates but I take advantage of the early release of the V3 and from the fact that Laravel 8 is not released to do it
* Show and edit actions are now triggered by a simple link rather than a form, which was useless as these routes are called with a `GET` http request
* Minor default templates changes in order to give laravel-table a prettier look
* Minor default config value changes in order to give laravel-table a prettier look

## [3.0.1](https://github.com/Okipa/laravel-table/compare/3.0.1...3.0.1)

2020-08-24

* Fixed Column `button` method behaviour which is supposed to allow usage without arguments

## [3.0.0](https://github.com/Okipa/laravel-table/compare/2.0.0...3.0.0)

2020-08-09

* Added Laravel 8 support
* Dropped Laravel 6 support
* Added template customization methods for `Table` instances :
  * `rowsSearchingTemplate`
  * `rowsNumberDefinitionTemplate`
  * `createActionTemplate`
  * `columnTitlesTemplate`
  * `navigationStatusTemplate`
  * `paginationTemplate`
* Updated templates

:point_right: [See the upgrade guide](/docs/upgrade-guides/from-v2-to-v3.md)

## [2.0.0](https://github.com/Okipa/laravel-table/compare/1.5.0...2.0.0)

2020-04-30

* Dropped support for PHP versions under 7.4
* Dropped support for Laravel versions under 6.0
* Restructured configuration file
* Removed translation files
* Updated templates
* Removed the deprecated methods
* Refactored the whole architecture to facilitate code comprehension and maintenance
* New architecture and usage
  
:point_right: [See the upgrade guide](/docs/upgrade-guides/from-v1-to-v2.md)

## [1.5.0](https://github.com/Okipa/laravel-table/compare/1.4.0...1.5.0)

2020-04-26

* Deprecated the `->icon()` method
* Added the `->prepend()` method to the table columns (which will replace the previous `->icon()` one) to prepend HTML to a column value
* Added the `->append()` method to the table columns to append HTML to a column value

## [1.4.0](https://github.com/Okipa/laravel-table/compare/1.3.0...1.4.0)

2020-04-26

* Added more granularity in the template customization possibilities : the `show`, `edit` and `destroy` actions are now defined in their own component. This way, it becomes easier to customize tiny parts of the table without touching to the others
  * Added `config('laravel-table.template.show')`, `config('laravel-table.template.edit')` and `config('laravel-table.template.destroy')` configs to set each new default component path
  * Added `->showTemplate()`, `->editTemplate()` and `->destroyTemplate()` to give the ability to customize these templates on the fly
* Added fallback path for each template if the config value is not defined, in order to prevent any update breaking change

## [1.3.0](https://github.com/Okipa/laravel-table/compare/1.2.7...1.3.0)

2020-04-25

* Tests have been migrated from Travis to Github actions
* Added PHP7.4 support
* Added Laravel 7 support
* Dropped Laravel support before 5.8 version
* Dropped PHP support before 7.2 version
* Reworked the documentation

## [1.2.7](https://github.com/Okipa/laravel-table/compare/1.2.6...1.2.7)

2020-04-03

* Fixed missing column when the `show` action is the only one defined

## [1.2.6](https://github.com/Okipa/laravel-table/compare/1.2.5...1.2.6)

2020-01-05

* Replaced hard-coded `info` action icon by config value

## [1.2.5](https://github.com/Okipa/laravel-table/compare/1.2.4...1.2.5)

2019-10-15

* Fixed the translations publication and overriding as specified on the Laravel documentation: https://laravel.com/docs/packages#translations
* Changed the command to publish the translations to: `php artisan vendor:publish --tag=laravel-table:translations`
* Changed the command to publish the configuration to: `php artisan vendor:publish --tag=laravel-table:config`
* Changed the command to publish the views to: `php artisan vendor:publish --tag=laravel-table:views`
* Improved testing with Travis CI (added some tests with `--prefer-lowest` composer tag to check the package compatibility with the lowest dependencies versions)

## [1.2.4](https://github.com/Okipa/laravel-table/compare/1.2.3...1.2.4)

2019-10-09

* Transferred PhpUnit builds tasks from Scrutinizer to Travis CI
* Transferred code coverage storage from Scrutinizer to Coveralls
* Re-authorized PHP7.1 as minimal version

## [1.2.3](https://github.com/Okipa/laravel-table/compare/1.2.2...1.2.3)

2019-09-13

* The model is now directly passed to the route during the table `show`, `edit` and `destroy` routes generation instead of its id
```php
// Assuming your declared your edit route like this:
(new Table())->model(User::class)->routes([
    // ...
    'edit'    => ['name'=> 'user.edit', 'params' => ['foo' => 'bar']],
    //...
]);
// The route will be generated like this during the table instantiation:
route('user.edit', [$user, 'foo' => 'bar']);
// Instead of this way:
route('user.edit', [$user->id, 'foo' => 'bar']);
```

## [1.2.2](https://github.com/Okipa/laravel-table/compare/1.2.1...1.2.2)

2019-09-13

* Fixed params order when generating the table routes. The table model id was not positioned at first when declaring other parameters
```php
// With a route declared like this:
Route::get('user/edit/{user}/{foo}', 'UsersController@edit')->name('user.edit');
// And a table routes declaration like this:
(new Table())->model(User::class)->routes([
    // ...
    'edit'    => ['name'=> 'user.edit', 'params' => ['bar']],
    //...
]);
// The route is now correctly generated and gives: /user/edit/1/bar instead of /user/edit/bar/1
```

## [1.2.1](https://github.com/Okipa/laravel-table/compare/1.2.0...1.2.1)

2019-09-13

* Fixed the `show`, `edit` and `destroy` route generation, since Laravel 6 does handle differently the key given in the `route()` helper:
```php
// Assuming your declared your edit route like this:
(new Table())->model(User::class)->routes([
    // ...
    'edit'    => ['name'=> 'user.edit', 'params' => ['foo' => 'bar']],
    //...
]);
// The route will be generated like this during the table instantiation:
route('user.edit', [$user->id, 'foo' => 'bar']);
// Instead of this way
route('user.edit', ['id' => $user->id, 'foo' => 'bar']);
```

## [1.2.0](https://github.com/Okipa/laravel-table/compare/1.1.0...1.2.0)

2019-09-04

* Added compatibility for Laravel 6

## [1.1.0](https://github.com/Okipa/laravel-table/compare/1.0.13...1.1.0)

2019-08-02

* Added the possibility to add an identifier to a table with `->identifier('your identifier')`. This identifier will be used for several things:
  * It will be added as an id (formatted as a slug string) to the table itself
  * It will be used to automatically customize the following interaction fields sent to the table, in order to be able to interact with a specific table if you have several of them on a single view: `rows`, `search`, `sort_by`, `sort_dir`
* :warning: if you have published the views, you will have to re-publish them

## [1.0.13](https://github.com/Okipa/laravel-table/compare/1.0.12...1.0.13)

2019-05-14

* Fixed a use case when no sortable columns are defined and an empty `orderBy` is called in the SQL request, causing an exception with MySQL

## [1.0.12](https://github.com/Okipa/laravel-table/compare/1.0.11...1.0.12)

2019-05-09

* Locked project compatibility to Laravel 5.5+ and PHP7.2+ to avoid issues
* Improved code phpdoc for better maintainability

## [1.0.11](https://github.com/Okipa/laravel-table/compare/1.0.10...1.0.11)

2019-05-06

* Added `show` to the list of available routes. - _[@Dranthos](https://github.com/Dranthos)_
* Added Spanish translation. - _[@Dranthos](https://github.com/Dranthos)_
* Wrapped sortable columns titles in order to avoid line jump between the sort icon and the column title (Issue #14)
* Improved rows number selection and search bar template to fix wrong display the rows number is disabled for example (Issue #15)
* Added possibility to show all the models contained in database with `->rowsNumber(null)` chained on the `Table` instance (Issue #16)

## [1.0.10](https://github.com/Okipa/laravel-table/compare/1.0.9...1.0.10)

2019-02-21

* Fixed a templating problem when disabling a line (one `td` html tag was missing)

## [1.0.9](https://github.com/Okipa/laravel-table/compare/1.0.8...1.0.9)

2019-02-21

* Updated design in order to respect the bootstrap basics
* Updated config architecture to improve the logic
* The `edit` and `destroy` buttons are now hidden when a line is disabled
* Improved compatibility with `postgres` for the searching action, using `ILIKE` instead of `LIKE` operator for case-insensitive searching

## [1.0.8](https://github.com/Okipa/laravel-table/compare/1.0.7...1.0.8)

2019-02-21

* Updated the result displaying in one and only `td` html tag: the title is displayed on the left and the result html on the right
* Also fixed the result classes rendering location, which is now on the `tr` html tag and no more on the `td` html tags

## [1.0.7](https://github.com/Okipa/laravel-table/compare/1.0.6...1.0.7)

2019-02-19

* **Possible breaking change:** reverted last tag features => removed the capacity to add some result outputs with the `->result()` method the Column objects
* **Possible breaking change:** reverted last tag features => removed the capacity to override default classes (config) for the results cells with the Table `->resultClasses()` method
* Added the capacity to append some results objects to the table with the `->result()` method with the following methods:
  * `->title()`: Set the result row title
  * `->html()`: Display a HTML output for the result row. The closure let you manipulate the following attributes: `\Illuminate\Support\Collection $paginatedRows`
  * `->classes()`: Override the default results classes and apply the given classes only on this result row. The default result classes are managed by the `config('laravel-table.classes.results')` value
* Added the capacity to manage a custom results template path in the config and with the `->resultsTemplatePath()` method

## [1.0.6](https://github.com/Okipa/laravel-table/compare/1.0.5...1.0.6)

2019-02-19

* Added the capacity to add some result outputs with the `->result()` method the Column objects
* Added the capacity to override default classes (config) for the results cells with the Table `->resultClasses()` method
* Improved accessibility by adding `scope` attributes to correct td html tags

## [1.0.5](https://github.com/Okipa/laravel-table/compare/1.0.4...1.0.5)

2019-02-18

* Updated `thead` and `tfoot` components in order to improve the responsive behavior

## [1.0.4](https://github.com/Okipa/laravel-table/compare/1.0.3...1.0.4)

2019-02-17

* `->appends()` does now also add appended attributes to search canceling and sorting actions

## [1.0.3](https://github.com/Okipa/laravel-table/compare/1.0.2...1.0.3)

2019-02-15

* `->appends()` method does now add appended key values to rows number selection form and to searching form as hidden fields

## [1.0.2](https://github.com/Okipa/laravel-table/compare/1.0.1...1.0.2)

2019-02-15

* Fixed searching queries process on regular table columns fields when aliased tables are declared in the `->query()` table method

## [1.0.1](https://github.com/Okipa/laravel-table/releases/tag/1.0.1)

2019-02-13

* Merged pull request https://github.com/Okipa/laravel-table/pull/2: wrapped searching queries into a `->where()` clause to avoid wrong behaviours when searching values. Thanks to https://github.com/costeirs for the PR
