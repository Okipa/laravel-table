<?php

namespace Tests\Unit\Bootstrap4;

use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use Tests\Models\UserCategory;

class TableReorderableTest extends \Tests\Unit\Bootstrap5\TableReorderableTest
{
    /** @test */
    public function it_can_set_livewire_sortable_markup_and_prepend_order_column_when_table_is_reorderable(): void
    {
        Config::set('laravel-table.icon.sort_desc', 'icon-sort-desc');
        Config::set('laravel-table.icon.drag_drop', 'icon-drag-drop');
        $categories = UserCategory::factory()->count(2)->create()->sortBy('position');
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(UserCategory::class)->reorderable('position');
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                    Column::make('name'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<div class="alert alert-info" role="alert">',
                'You can rearrange the order of the items in this list using a drag and drop action.',
                '</div>',
                '<div class="table-responsive">',
                '<thead>',
                '<tr',
                '<th wire:key="column-position" class="align-middle" scope="col">',
                '<a wire:click.prevent="sortBy(\'position\')"',
                ' class="d-flex align-items-center"',
                ' href=""',
                ' title="Sort descending"',
                'icon-sort-desc',
                '<span class="ml-2">validation.attributes.position</span>',
                '</th>',
                '<th wire:key="column-id" class="align-middle" scope="col">',
                'validation.attributes.id',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                'validation.attributes.name',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody wire:sortable="reorder">',
                '<tr wire:key="row-' . $categories->first()->id . '" wire:sortable.item="' . $categories->first()->id
                . '" class="border-bottom">',
                '<th wire:key="cell-position-' . $categories->first()->id
                . '" wire:sortable.handle style="cursor: move;" class="align-middle" scope="row">',
                '<span class="mr-2">icon-drag-drop</span>' . $categories->first()->position,
                '</th>',
                '<td wire:key="cell-id-' . $categories->first()->id . '" class="align-middle">',
                $categories->first()->id,
                '</td>',
                '<td wire:key="cell-name-' . $categories->first()->id . '" class="align-middle">',
                $categories->first()->name,
                '</td>',
                '</tr>',
                '<tr wire:key="row-' . $categories->last()->id . '" wire:sortable.item="' . $categories->last()->id
                . '" class="border-bottom">',
                '<th wire:key="cell-position-' . $categories->last()->id
                . '" wire:sortable.handle style="cursor: move;" class="align-middle" scope="row">',
                '<span class="mr-2">icon-drag-drop</span>' . $categories->last()->position,
                '</th>',
                '<td wire:key="cell-id-' . $categories->last()->id . '" class="align-middle">',
                $categories->last()->id,
                '</td>',
                '<td wire:key="cell-name-' . $categories->last()->id . '" class="align-middle">',
                $categories->last()->name,
                '</td>',
                '</tr>',
                '</tbody>',
            ]);
    }
}
