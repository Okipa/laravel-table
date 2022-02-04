# Upgrade from v4 to v5

Follow the steps below to upgrade the package.

## Removed okipa/laravel-html-helper dependency

If you do use the `laravel-html-helper` in your projet without having installed through composer, you should add it.

However, please note that this package is now useless for my packages and has been abandoned, si it will not be maintained anymore.

## Added livewire/livewire dependency

In order to remove the high complexity involved by the HTTP requests data handling, and to plug several additional features, I chose to rebuild this package to work with Livewire.

As so, you will have to install it properly before using this package : https://laravel-livewire.com/docs/installation.

## See all changes

See all change with the [comparison tool](https://github.com/Okipa/laravel-table/compare/3.0.0...4.0.0).

## Undocumented changes

If you see any forgotten and undocumented change, please submit a PR to add them to this upgrade guide.
