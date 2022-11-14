<?php

namespace Tests\Unit\Bootstrap5;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Filters\RelationshipFilter;
use Okipa\LaravelTable\Table;
use Tests\Models\Company;
use Tests\Models\User;
use Tests\Models\UserCategory;
use Tests\TestCase;

class TableReorderableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_disable_all_columns_sorting_when_table_is_reorderable(): void
    {
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
                    Column::make('id')
                        ->sortable(fn (Builder $query, string $sortDir) => $query->orderBy('id', $sortDir)),
                    Column::make('name')->sortable()->sortByDefault(),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th wire:key="column-id" class="align-middle" scope="col">',
                'validation.attributes.id',
                '</th>',
                '<th wire:key="column-name" class="align-middle" scope="col">',
                'validation.attributes.name',
                '</th>',
                '</tr>',
                '</thead>',
                '<tbody wire:sortable="reorder">',
                $categories->first()->name,
                $categories->last()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                '<a wire:click.prevent="sortBy(\'id\')"',
                '<a wire:click.prevent="sortBy(\'name\')"',
            ]);
    }

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
                '<span class="ms-2">validation.attributes.position</span>',
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
                '<span class="me-2">icon-drag-drop</span>' . $categories->first()->position,
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
                '<span class="me-2">icon-drag-drop</span>' . $categories->last()->position,
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

    /** @test */
    public function it_can_reorder_table_without_filter_nor_searching(): void
    {
        UserCategory::factory()->count(9)->state(new Sequence(
            ['position' => 1],
            ['position' => 2],
            ['position' => 3],
            ['position' => 4],
            ['position' => 5],
            ['position' => 6],
            ['position' => 7],
            ['position' => 8],
            ['position' => 9],
        ))->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()
                    ->model(UserCategory::class)
                    ->numberOfRowsPerPageOptions([3])
                    ->reorderable('position');
            }

            protected function columns(): array
            {
                return [
                    Column::make('name'),
                ];
            }
        };
        // Simulate array returned by Livewire sortable with new order
        $reorderedList = [
            ['order' => 1, 'value' => 3],
            ['order' => 2, 'value' => 1],
            ['order' => 3, 'value' => 2],
        ];
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->call('reorder', $reorderedList)
            ->assertEmitted('laraveltable:action:feedback', 'The list has been reordered.');
        // Categories have been reordered
        $reorderedCategories = UserCategory::orderBy('position')->get();
        $this->assertEquals([
            3 => 1,
            1 => 2,
            2 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
        ], $reorderedCategories->pluck('position', 'id')->toArray());
    }

    /** @test */
    public function it_can_reorder_table_sorted_descending(): void
    {
        UserCategory::factory()->count(9)->state(new Sequence(
            ['position' => 1],
            ['position' => 2],
            ['position' => 3],
            ['position' => 4],
            ['position' => 5],
            ['position' => 6],
            ['position' => 7],
            ['position' => 8],
            ['position' => 9],
        ))->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()
                    ->model(UserCategory::class)
                    ->numberOfRowsPerPageOptions([3])
                    ->reorderable(attribute: 'position', sortDirByDefault: 'desc');
            }

            protected function columns(): array
            {
                return [
                    Column::make('name'),
                ];
            }
        };
        // Simulate array returned by Livewire sortable with new order
        $reorderedList = [
            ['order' => 1, 'value' => 4],
            ['order' => 2, 'value' => 6],
            ['order' => 3, 'value' => 5],
        ];
        Livewire::withQueryParams(['page' => 2])
            ->test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->call('sortBy', 'position')
            ->call('sortBy', 'position')
            ->assertSet('sortDir', 'desc')
            ->call('reorder', $reorderedList)
            ->assertEmitted('laraveltable:action:feedback', 'The list has been reordered.');
        // Categories have been reordered
        $reorderedCategories = UserCategory::orderBy('position')->get();
        $this->assertEquals([
            9 => 9,
            8 => 8,
            7 => 7,
            4 => 6,
            6 => 5,
            5 => 4,
            3 => 3,
            2 => 2,
            1 => 1,
        ], $reorderedCategories->pluck('position', 'id')->toArray());
    }

    /** @test */
    public function it_can_reorder_table_when_applying_query(): void
    {
        // Categories are gathered by name and positioned
        // which means that several categories will have the same position
        // (replicates the spatie/eloquent-sortable package behaviour)
        UserCategory::factory()->count(18)->state(new Sequence(
            ['name' => 'Name test 1', 'position' => 1],
            ['name' => 'Name test 2', 'position' => 1],
            ['name' => 'Name test 3', 'position' => 1],
            ['name' => 'Name test 1', 'position' => 2],
            ['name' => 'Name test 2', 'position' => 2],
            ['name' => 'Name test 3', 'position' => 2],
            ['name' => 'Name test 1', 'position' => 3],
            ['name' => 'Name test 2', 'position' => 3],
            ['name' => 'Name test 3', 'position' => 3],
            ['name' => 'Name test 1', 'position' => 4],
            ['name' => 'Name test 2', 'position' => 4],
            ['name' => 'Name test 3', 'position' => 4],
            ['name' => 'Name test 1', 'position' => 5],
            ['name' => 'Name test 2', 'position' => 5],
            ['name' => 'Name test 3', 'position' => 5],
            ['name' => 'Name test 1', 'position' => 6],
            ['name' => 'Name test 2', 'position' => 6],
            ['name' => 'Name test 3', 'position' => 6],
        ))->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()
                    ->model(UserCategory::class)
                    ->numberOfRowsPerPageOptions([3])
                    // Filtering on `Name test 2` from SQL query
                    ->query(fn (Builder $query) => $query->where('name', 'Name test 2'))
                    ->reorderable('position');
            }

            protected function columns(): array
            {
                return [
                    Column::make('name')->searchable(),
                ];
            }
        };
        // Simulate array returned by Livewire sortable with new order
        $reorderedList = [
            ['order' => 1, 'value' => 8],
            ['order' => 2, 'value' => 2],
            ['order' => 3, 'value' => 5],
        ];
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->call('reorder', $reorderedList)
            ->assertEmitted('laraveltable:action:feedback', 'The list has been reordered.');
        // Categories have been reordered
        $reorderedCategories = UserCategory::orderBy('position')->get();
        $this->assertEquals([
            1 => 1,
            8 => 1,
            3 => 1,
            4 => 2,
            2 => 2,
            6 => 2,
            7 => 3,
            5 => 3,
            9 => 3,
            10 => 4,
            11 => 4,
            12 => 4,
            13 => 5,
            14 => 5,
            15 => 5,
            16 => 6,
            17 => 6,
            18 => 6,
        ], $reorderedCategories->pluck('position', 'id')->toArray());
    }

    /** @test */
    public function it_can_reorder_table_when_searching(): void
    {
        UserCategory::factory()->count(18)->state(new Sequence(
            ['name' => 'Name test 1', 'position' => 1],
            ['name' => 'Name test 2', 'position' => 2],
            ['name' => 'Name test 3', 'position' => 3],
            ['name' => 'Name test 1', 'position' => 4],
            ['name' => 'Name test 2', 'position' => 5],
            ['name' => 'Name test 3', 'position' => 6],
            ['name' => 'Name test 1', 'position' => 7],
            ['name' => 'Name test 2', 'position' => 8],
            ['name' => 'Name test 3', 'position' => 9],
            ['name' => 'Name test 1', 'position' => 10],
            ['name' => 'Name test 2', 'position' => 11],
            ['name' => 'Name test 3', 'position' => 12],
            ['name' => 'Name test 1', 'position' => 13],
            ['name' => 'Name test 2', 'position' => 14],
            ['name' => 'Name test 3', 'position' => 15],
            ['name' => 'Name test 1', 'position' => 16],
            ['name' => 'Name test 2', 'position' => 17],
            ['name' => 'Name test 3', 'position' => 18],
        ))->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()
                    ->model(UserCategory::class)
                    ->numberOfRowsPerPageOptions([3])
                    ->reorderable('position');
            }

            protected function columns(): array
            {
                return [
                    Column::make('name')->searchable(),
                ];
            }
        };
        // Simulate array returned by Livewire sortable with new order
        $reorderedList = [
            ['order' => 1, 'value' => 8],
            ['order' => 2, 'value' => 2],
            ['order' => 3, 'value' => 5],
        ];
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->set('searchBy', 'Name test 2')
            ->call('reorder', $reorderedList)
            ->assertEmitted('laraveltable:action:feedback', 'The list has been reordered.');
        // Categories have been reordered
        $reorderedCategories = UserCategory::orderBy('position')->get();
        $this->assertEquals([
            1 => 1,
            8 => 2,
            3 => 3,
            4 => 4,
            2 => 5,
            6 => 6,
            7 => 7,
            5 => 8,
            9 => 9,
            10 => 10,
            11 => 11,
            12 => 12,
            13 => 13,
            14 => 14,
            15 => 15,
            16 => 16,
            17 => 17,
            18 => 18,
        ], $reorderedCategories->pluck('position', 'id')->toArray());
    }

    /** @test */
    public function it_can_reorder_table_when_filtering(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();
        Company::factory()->count(27)->state(new Sequence(
            ['owner_id' => $user1->id, 'position' => 1],
            ['owner_id' => $user2->id, 'position' => 2],
            ['owner_id' => $user3->id, 'position' => 3],
            ['owner_id' => $user1->id, 'position' => 4],
            ['owner_id' => $user2->id, 'position' => 5],
            ['owner_id' => $user3->id, 'position' => 6],
            ['owner_id' => $user1->id, 'position' => 7],
            ['owner_id' => $user2->id, 'position' => 8],
            ['owner_id' => $user3->id, 'position' => 9],
            ['owner_id' => $user1->id, 'position' => 10],
            ['owner_id' => $user2->id, 'position' => 11],
            ['owner_id' => $user3->id, 'position' => 12],
            ['owner_id' => $user1->id, 'position' => 13],
            ['owner_id' => $user2->id, 'position' => 14],
            ['owner_id' => $user3->id, 'position' => 15],
            ['owner_id' => $user1->id, 'position' => 16],
            ['owner_id' => $user2->id, 'position' => 17],
            ['owner_id' => $user3->id, 'position' => 18],
            ['owner_id' => $user1->id, 'position' => 19],
            ['owner_id' => $user2->id, 'position' => 20],
            ['owner_id' => $user3->id, 'position' => 21],
            ['owner_id' => $user1->id, 'position' => 22],
            ['owner_id' => $user2->id, 'position' => 23],
            ['owner_id' => $user3->id, 'position' => 24],
            ['owner_id' => $user1->id, 'position' => 25],
            ['owner_id' => $user2->id, 'position' => 26],
            ['owner_id' => $user3->id, 'position' => 27],
        ))->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()
                    ->model(Company::class)
                    ->filters([
                        new RelationshipFilter('Owner', 'owner', User::pluck('name', 'id')->toArray(), false),
                    ])
                    ->numberOfRowsPerPageOptions([3])
                    ->reorderable('position');
            }

            protected function columns(): array
            {
                return [
                    Column::make('name'),
                ];
            }
        };
        // Simulate array returned by Livewire sortable with new order
        $reorderedList = [
            ['order' => 1, 'value' => 17],
            ['order' => 2, 'value' => 11],
            ['order' => 3, 'value' => 14],
        ];
        Livewire::withQueryParams(['page' => 2])
            ->test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->set('selectedFilters', [
                'filter_relationship_owner' => $user2->id,
            ])
            ->call('reorder', $reorderedList)
            ->assertEmitted('laraveltable:action:feedback', 'The list has been reordered.');
        // Companies have been reordered
        $reorderedCompanies = Company::orderBy('position')->get();
        $this->assertEquals([
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10 => 10,
            17 => 11,
            12 => 12,
            13 => 13,
            11 => 14,
            15 => 15,
            16 => 16,
            14 => 17,
            18 => 18,
            19 => 19,
            20 => 20,
            21 => 21,
            22 => 22,
            23 => 23,
            24 => 24,
            25 => 25,
            26 => 26,
            27 => 27,
        ], $reorderedCompanies->pluck('position', 'id')->toArray());
    }

    /** @test */
    public function it_can_reorder_table_with_fixing_wrong_order(): void
    {
        UserCategory::factory()->count(9)->state(new Sequence(
            ['position' => 1],
            ['position' => 2],
            ['position' => 3],
            ['position' => 4],
            ['position' => 7],
            ['position' => 9],
            ['position' => 10],
            ['position' => 10],
            ['position' => 10],
        ))->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()
                    ->model(UserCategory::class)
                    ->numberOfRowsPerPageOptions([3])
                    ->reorderable('position');
            }

            protected function columns(): array
            {
                return [
                    Column::make('name'),
                ];
            }
        };
        // Simulate array returned by Livewire sortable with new order
        $reorderedList = [
            ['order' => 1, 'value' => 3],
            ['order' => 2, 'value' => 1],
            ['order' => 3, 'value' => 2],
        ];
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->call('reorder', $reorderedList)
            ->assertEmitted('laraveltable:action:feedback', 'The list has been reordered.');
        // Categories have been reordered and positions have been fixed
        $reorderedCategories = UserCategory::orderBy('position')->get();
        $this->assertEquals([
            3 => 1,
            1 => 2,
            2 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
        ], $reorderedCategories->pluck('position', 'id')->toArray());
    }
}
