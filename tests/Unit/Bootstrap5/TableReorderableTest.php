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
        $categories = UserCategory::factory()->count(9)->create()->sortBy('position')->values();
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
        // Simulate pagination of 3 items on page 1
        $paginatedCategories = $categories->take(3);
        // Move last category from page 1 at first position
        $lastCategory = $paginatedCategories->pop();
        $paginatedCategories->prepend($lastCategory);
        // Simulate array returned by Livewire sortable
        $reorderedList = $paginatedCategories->values()->map(fn (UserCategory $category, int $index) => [
            'order' => $index + 1,
            'value' => $category->id,
        ])->toArray();
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->call('reorder', $reorderedList)
            ->assertEmitted('laraveltable:action:feedback', 'The list has been reordered.');
        $reorderedCategories = UserCategory::orderBy('position')->get();
        $page1CategoryIds = $reorderedCategories->pluck('id')->take(3)->toArray();
        // Searched categories from page 1 have been reordered
        $this->assertEquals($paginatedCategories->pluck('id')->toArray(), $page1CategoryIds);
        // All other categories have not been reordered
        foreach ($reorderedCategories as $reorderedCategory) {
            if (in_array($reorderedCategory->id, $page1CategoryIds, true)) {
                continue;
            }
            $this->assertEquals(
                $categories->firstWhere('id', $reorderedCategory->id)->position,
                $reorderedCategory->position
            );
        }
    }

    /** @test */
    public function it_can_reorder_table_sorted_descending(): void
    {
        $categories = UserCategory::factory()->count(9)->create()->sortByDesc('position')->values();
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
        // Simulate pagination of 3 items on page 2
        $paginatedCategories = $categories->slice(3)->take(3);
        // Move last category from page 2 at first position
        $lastCategory = $paginatedCategories->pop();
        $paginatedCategories->prepend($lastCategory);
        // Simulate array returned by Livewire sortable
        $reorderedList = $paginatedCategories->values()->map(fn (UserCategory $category, int $index) => [
            'order' => $index + 1,
            'value' => $category->id,
        ])->toArray();
        Livewire::withQueryParams(['page' => 2])
            ->test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->call('reorder', $reorderedList)
            ->assertEmitted('laraveltable:action:feedback', 'The list has been reordered.');
        $reorderedCategories = UserCategory::orderBy('position', 'desc')->get();
        $page2CategoryIds = $reorderedCategories->pluck('id')->slice(3)->take(3)->values()->toArray();
        // Searched categories from page 2 have been reordered
        $this->assertEquals($paginatedCategories->pluck('id')->toArray(), $page2CategoryIds);
        // All other categories have not been reordered
        foreach ($reorderedCategories as $reorderedCategory) {
            if (in_array($reorderedCategory->id, $page2CategoryIds, true)) {
                continue;
            }
            $this->assertEquals(
                $categories->firstWhere('id', $reorderedCategory->id)->position,
                $reorderedCategory->position
            );
        }
    }

    /** @test */
    public function it_can_reorder_table_when_applying_query(): void
    {
        $categories = UserCategory::factory()
            ->count(18)
            ->state(new Sequence(
                ['name' => 'Name test 1'],
                ['name' => 'Name test 2'],
                ['name' => 'Name test 3'],
            ))
            ->create()
            ->sortBy('position')
            ->values();
        // Categories are gathered by name and positioned
        // which means that several categories will have the same position
        // (replicates the spatie/eloquent-sortable package behaviour)
        $name1Categories = $categories->where('name', 'Name test 1')->values();
        foreach ($name1Categories as $position => $category) {
            $category->position = $position + 1;
            $category->save();
        }
        $name2Categories = $categories->where('name', 'Name test 2')->values();
        foreach ($name2Categories as $position => $category) {
            $category->position = $position + 1;
            $category->save();
        }
        $name3Categories = $categories->where('name', 'Name test 3')->values();
        foreach ($name3Categories as $position => $category) {
            $category->position = $position + 1;
            $category->save();
        }
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
        // Simulate applying query on `Name test 2` with pagination of 3 items on page 1
        $categories->fresh();
        $paginatedCategories = $categories->where('name', 'Name test 2')->take(3);
        // Move last category from page 1 at first position
        $lastCategory = $paginatedCategories->pop();
        $paginatedCategories->prepend($lastCategory);
        // Simulate array returned by Livewire sortable
        $reorderedList = $paginatedCategories->values()->map(fn (UserCategory $category, int $index) => [
            'order' => $index + 1,
            'value' => $category->id,
        ])->toArray();
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->call('reorder', $reorderedList)
            ->assertEmitted('laraveltable:action:feedback', 'The list has been reordered.');
        $reorderedCategories = UserCategory::orderBy('position')->get();
        $page1QueryCategoryIds = $reorderedCategories->where('name', 'Name test 2')->pluck('id')->take(3)->toArray();
        // Searched categories from page 1 have been reordered
        $this->assertEquals($paginatedCategories->pluck('id')->toArray(), $page1QueryCategoryIds);
        // All other categories have not been reordered
        foreach ($reorderedCategories as $index => $reorderedCategory) {
            if (in_array($reorderedCategory->id, $page1QueryCategoryIds, true)) {
                continue;
            }
            $this->assertEquals(
                $categories->firstWhere('id', $reorderedCategory->id)->position,
                $reorderedCategory->position
            );
        }
    }

    /** @test */
    public function it_can_reorder_table_when_searching(): void
    {
        $categories = UserCategory::factory()
            ->count(18)
            ->state(new Sequence(
                ['name' => 'Name test 1'],
                ['name' => 'Name test 2'],
                ['name' => 'Name test 3'],
            ))
            ->create()
            ->sortBy('position')
            ->values();
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
        // Simulate searching on `Name test 2` with pagination of 3 items on page 1
        $paginatedCategories = $categories->where('name', 'Name test 2')->take(3);
        // Move last category from page 1 at first position
        $lastCategory = $paginatedCategories->pop();
        $paginatedCategories->prepend($lastCategory);
        // Simulate array returned by Livewire sortable
        $reorderedList = $paginatedCategories->values()->map(fn (UserCategory $category, int $index) => [
            'order' => $index + 1,
            'value' => $category->id,
        ])->toArray();

        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->set('searchBy', 'Name test 2')
            ->call('reorder', $reorderedList)
            ->assertEmitted('laraveltable:action:feedback', 'The list has been reordered.');
        $reorderedCategories = UserCategory::orderBy('position')->get();
        $page1SearchedCategoryIds = $reorderedCategories->where('name', 'Name test 2')->pluck('id')->take(3)->toArray();
        // Searched categories from page 1 have been reordered
        $this->assertEquals($paginatedCategories->pluck('id')->toArray(), $page1SearchedCategoryIds);
        // All other categories have not been reordered
        foreach ($reorderedCategories as $index => $reorderedCategory) {
            if (in_array($reorderedCategory->id, $page1SearchedCategoryIds, true)) {
                continue;
            }
            $this->assertEquals(
                $categories->firstWhere('id', $reorderedCategory->id)->position,
                $reorderedCategory->position
            );
        }
    }

    /** @test */
    public function it_can_reorder_table_when_filtering(): void
    {
        $user1 = User::factory()->create();
        $companiesUser1 = Company::factory()->withOwner($user1)->count(9)->create();
        $user2 = User::factory()->create();
        $companiesUser2 = Company::factory()->withOwner($user2)->count(9)->create()->sortBy('position');
        $user3 = User::factory()->create();
        $companiesUser3 = Company::factory()->withOwner($user3)->count(9)->create();
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
        // Simulate filtering on user 2 with pagination of 3 items on page 2
        $paginatedCompanies = $companiesUser2->slice(3)->take(3);
        // Move last company from page 2 at first position
        $lastCompany = $paginatedCompanies->pop();
        $paginatedCompanies->prepend($lastCompany);
        // Simulate array returned by Livewire sortable
        $reorderedList = $paginatedCompanies->values()->map(fn (Company $company, int $index) => [
            'order' => $index + 1,
            'value' => $company->id,
        ])->toArray();
        Livewire::withQueryParams(['page' => 2])
            ->test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->set('selectedFilters', [
                'filter_relationship_owner' => $user2->id,
            ])
            ->call('reorder', $reorderedList)
            ->assertEmitted('laraveltable:action:feedback', 'The list has been reordered.');
        $reorderedCompanies = Company::orderBy('position')->get();
        $page2Filtered3CompanyIds = $reorderedCompanies->where('owner_id', $user2->id)
            ->pluck('id')
            ->slice(3)
            ->take(3)
            ->values()
            ->toArray();
        // Filtered companies from page 2 have been reordered
        $this->assertEquals($paginatedCompanies->pluck('id')->toArray(), $page2Filtered3CompanyIds);
        // All other occurrences have not been reordered
        $beforeReorderCompanies = $companiesUser1->merge($companiesUser2)
            ->merge($companiesUser3)
            ->sortBy('position')
            ->values();
        // All other categories have not been reordered
        foreach ($reorderedCompanies as $reorderedCompany) {
            if (in_array($reorderedCompany->id, $page2Filtered3CompanyIds, true)) {
                continue;
            }
            $this->assertEquals(
                $beforeReorderCompanies->firstWhere('id', $reorderedCompany->id)->position,
                $reorderedCompany->position
            );
        }
    }
}
