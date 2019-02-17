# Changelog

## [1.0.4](https://github.com/Okipa/laravel-table/releases/tag/1.0.4)
2019-02-17
- `->appends()` does now also merge appended keys values to request attributes, in order to use the appended to the pagination, but also to the rows number selection, searching and sorting actions.
- Replaced `col-[breakpoint]` thead templating by `flex`. 
- Replaced `col-[breakpoint]` tfoot templating by `flex`. 

## [1.0.3](https://github.com/Okipa/laravel-table/releases/tag/1.0.3)
2019-02-15
- `->appends()` method does now add appended key values to rows number selection form and to searching form as hidden fields.

## [1.0.2](https://github.com/Okipa/laravel-table/releases/tag/1.0.2)
2019-02-15
- Fixed searching queries process on regular table columns fields when aliased tables are declared in the the `->query()` table method.

## [1.0.1](https://github.com/Okipa/laravel-table/releases/tag/1.0.1)
2019-02-13
- Merged pull request https://github.com/Okipa/laravel-table/pull/2 : wrapped searching queries into a `->where()` clause to avoid wrong behaviours when searching values. Thanks to https://github.com/costeirs for the PR.
