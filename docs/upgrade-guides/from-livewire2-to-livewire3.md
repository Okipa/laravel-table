# Upgrade from livewire 2 to livewire 3

Livewire 3 is a major upgrade from livewire 2 with the removal of emit, the removal of @livescripts and @livewirestyles, and wire:model defaults being changed from live to defer.

Follow the steps below to upgrade the package.

## Template changes

If you've published the previous templates with ```php artisan vendor:publish --tag=laravel-table:views```, you'll need to either re-publish or delete them if you've not made changes, or make the following changes to your modified templates:

* Change all locations with ```wire:model=``` to ```wire:model.live=```
* Change all locations with ```wire:model.defer=``` to ```wire:model=```
* Change ```$rows->links()``` ```to $rows->links(data: ['scrollTo' => false])``` as Livewire3 has a new default scroll behavior that scrolls to the top of the page.
* Change ```@if($sortBy === $column->getAttribute())``` to ```@if($sortedBy === $column->getAttribute())```
* Change ```<a wire:click.prevent="$set('searchBy', ''), $refresh"``` to ```<a wire:click.prevent="$set('searchBy', '')"``` as a previous bug fix handles this internally
* Livewire 3's new bootstrap template includes the ```Showing x to x of y results``` information on the screen, causing duplicate data with this addon. To fix this, you need to publish the livewire pagination templates with ```php artisan livewire:publish --pagination```, and remove the following:

```
<div>
    <p class="small text-muted">
        {!! __('Showing') !!}
        <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
        {!! __('to') !!}
        <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
        {!! __('of') !!}
        <span class="fw-semibold">{{ $paginator->total() }}</span>
        {!! __('results') !!}
    </p>
</div>
```

## Usage changes

* Any use of ```laraveltable:refresh``` needs to be changed from ```$livewire->emit('laraveltable:refresh');``` to ```$livewire->dispatch('laraveltable:refresh');```, as emit was removed from livewire3.


## Laravel Changes

Because of the way views are cached, reminents of livewire2 can be cached and cause issues you'll end up chasing for no reason. Here are the steps you should take after making the changes above to ensure your views aren't stale. You should also reference the [livewire3 upgrade guide](https://livewire.laravel.com/docs/3.x/upgrading) if you're using livewire 3 beyond this addon.

* php artisan view:clear
* restart ```npm run dev``` or rebuild the css and js with ```npm run dev```


## Troubleshooting

* If the published livewire pagination blade templates aren't being used (your changes aren't being displayed), be sure to remove ```'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',``` from your ```tailwind.config.js``` file, and issue an ```npm run build``` or ```npm run dev``` as it tells laravel to use the base vendor templates.