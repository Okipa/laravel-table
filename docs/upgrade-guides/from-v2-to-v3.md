# Upgrade from v2 to v3

Follow the steps below to upgrade the package.

## Config changes

Some config changes have been made. If you customized it, you should [re-publish it](../../README.md#configuration) and reapply your changes.

## Template changes

Some template changes have been made. If you customized them, you should [re-publish them](../../README.md#templates) and reapply your changes.

## API changes

There are small changes in the API you will have to report in your code:

* Search and replace each `Table` use of `->showTemplate(` by `->showActionTemplate(`.
* Search and replace each `Table` use of `->editTemplate(` by `->editActionTemplate(`.
* Search and replace each `Table` use of `->destroyTemplate(` by `->destroyActionTemplate(`.
* The use of the `button` method on `Column` class should not be used without argument and will now trigger an error if no array is given.

## See all changes

See all change with the [comparison tool](https://github.com/Okipa/laravel-table/compare/2.0.0...3.0.0).

## Undocumented changes

If you see any forgotten and undocumented change, please submit a PR to add them to this upgrade guide.
