# Changelog

## [1.0.10](https://github.com/Okipa/laravel-table/releases/tag/1.0.10)
2019-02-21
- Fixed a templating problem when disabling a line (one `td` html tag was missing).

## [1.0.9](https://github.com/Okipa/laravel-table/releases/tag/1.0.9)
2019-02-21
- Updated design in order to respect the bootstrap basics.
- Updated config architecture to improve the logic.
- The `edit` and `destroy` buttons are now hidden when a line is disabled.
- Improved compatibility with `postgres` for the searching action, using `ILIKE` instead of `LIKE` operator for case-insensitive searching.

## [1.0.8](https://github.com/Okipa/laravel-table/releases/tag/1.0.8)
2019-02-21
- Updated the result displaying in one and only `td` html tag : the title is displayed on the left and the result html on the right.
- Also fixed the result classes rendering location, which is now on the `tr` html tag and no more on the `td` html tags.

## [1.0.7](https://github.com/Okipa/laravel-table/releases/tag/1.0.7)
2019-02-19
- **Breaking change :** reverted last tag features => removed the capacity to add some result outputs with the `->result()` method the Column objects.
- **Breaking change :** reverted last tag features => removed the capacity to override default classes (config) for the results cells with the Table `->resultClasses()` method.
- Added the capacity to append some results objects to the table with the `->result()` method with the following methods :
  - `->title()` : Set the result row title.
  - `->html()` : Display a HTML output for the result row. The closure let you manipulate the following attributes : $displayedList.
  - `->classes()` : Override the default results classes and apply the given classes only on this result row. The default result classes are managed by the config('laravel-table.classes.results') value.
- Added the capacity to manage a custom results template path in the config and with the `->$resultsComponentPath()` method.

## [1.0.6](https://github.com/Okipa/laravel-table/releases/tag/1.0.6)
2019-02-19
- Added the capacity to add some result outputs with the `->result()` method the Column objects.
- Added the capacity to override default classes (config) for the results cells with the Table `->resultClasses()` method.
- Improved accessibility by adding `scope` attributes to correct td html tags.

## [1.0.5](https://github.com/Okipa/laravel-table/releases/tag/1.0.5)
2019-02-18
- Updated `thead` and `tfoot` components in order to improve the responsive behavior.

## [1.0.4](https://github.com/Okipa/laravel-table/releases/tag/1.0.4)
2019-02-17
- `->appends()` does now also add appended attributes to search canceling and sorting actions.

## [1.0.3](https://github.com/Okipa/laravel-table/releases/tag/1.0.3)
2019-02-15
- `->appends()` method does now add appended key values to rows number selection form and to searching form as hidden fields.

## [1.0.2](https://github.com/Okipa/laravel-table/releases/tag/1.0.2)
2019-02-15
- Fixed searching queries process on regular table columns fields when aliased tables are declared in the the `->query()` table method.

## [1.0.1](https://github.com/Okipa/laravel-table/releases/tag/1.0.1)
2019-02-13
- Merged pull request https://github.com/Okipa/laravel-table/pull/2 : wrapped searching queries into a `->where()` clause to avoid wrong behaviours when searching values. Thanks to https://github.com/costeirs for the PR.
