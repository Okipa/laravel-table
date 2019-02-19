# Changelog

## [1.0.7](https://github.com/Okipa/laravel-table/releases/tag/1.0.7)
2019-02-19
- **Breaking change : ** Removed the capacity to add some result outputs with the `->result()` method the Column objects.
- **Breaking change : ** Removed the capacity to override default classes (config) for the results cells with the Table `->resultClasses()` method.
- Added the capacity to append some results objects to the table with the `->result()` method.

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
