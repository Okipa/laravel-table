<?php

namespace Tests\Unit\Bootstrap5;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class TableNumberOfRowsPerPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cant_set_number_of_rows_per_page_options_when_feature_is_globally_disabled(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', false);
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertDontSeeHtml('<select wire:change="changeNumberOfRowsPerPage($event.target.value)"');
    }

    /** @test */
    public function it_cant_set_number_of_rows_per_page_options_when_feature_is_disabled_from_table(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', true);
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->enableNumberOfRowsPerPageChoice(false);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertDontSeeHtml('<select wire:change="changeNumberOfRowsPerPage($event.target.value)"');
    }

    /** @test */
    public function it_can_set_number_of_rows_per_page_options_when_feature_is_globally_enabled(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', true);
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<select wire:change="changeNumberOfRowsPerPage($event.target.value)"',
            ]);
    }

    /** @test */
    public function it_can_set_number_of_rows_per_page_options_when_feature_is_enabled_from_table(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', false);
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->enableNumberOfRowsPerPageChoice(true);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<select wire:change="changeNumberOfRowsPerPage($event.target.value)"',
            ]);
    }

    /** @test */
    public function it_can_set_global_default_number_of_rows_per_page_options_from_config(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', true);
        Config::set('laravel-table.icon.rows_number', 'rows-number-icon');
        Config::set('laravel-table.number_of_rows_per_page_default_options', [1, 2, 3, 4, 5]);
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('numberOfRowsPerPage', 1)
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<div wire:ignore class="ps-xl-3 py-1">',
                '<div class="input-group">',
                '<span id="rows-number-per-page-icon" class="input-group-text text-secondary">',
                'rows-number-icon',
                '</span>',
                '<select wire:change="changeNumberOfRowsPerPage($event.target.value)" class="form-select" placeholder="Number of rows per page" aria-label="Number of rows per page" aria-describedby="rows-number-per-page-icon">',
                '<option wire:key="rows-number-per-page-option-placeholder" value="" disabled>Number of rows per page</option>',
                '<option wire:key="rows-number-per-page-option-1" value="1" selected>',
                '1',
                '</option>',
                '<option wire:key="rows-number-per-page-option-2" value="2">',
                '2',
                '</option>',
                '<option wire:key="rows-number-per-page-option-3" value="3">',
                '3',
                '</option>',
                '<option wire:key="rows-number-per-page-option-4" value="4">',
                '4',
                '</option>',
                '<option wire:key="rows-number-per-page-option-5" value="5">',
                '5',
                '</option>',
                '</select>',
                '</div>',
                '</div>',
                '</thead>',
            ]);
    }

    /** @test */
    public function it_can_set_specific_number_of_rows_per_page_options_from_table(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', true);
        Config::set('laravel-table.icon.rows_number', 'rows-number-icon');
        Config::set('laravel-table.number_of_rows_per_page_default_options', [10, 25, 50, 75, 100]);
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->numberOfRowsPerPageOptions([1, 2, 3, 4, 5]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('numberOfRowsPerPage', 1)
            ->assertSeeHtmlInOrder([
                '<select wire:change="changeNumberOfRowsPerPage($event.target.value)" class="form-select" placeholder="Number of rows per page" aria-label="Number of rows per page" aria-describedby="rows-number-per-page-icon">',
                '<option wire:key="rows-number-per-page-option-placeholder" value="" disabled>Number of rows per page</option>',
                '<option wire:key="rows-number-per-page-option-1" value="1" selected>',
                '1',
                '</option>',
                '<option wire:key="rows-number-per-page-option-2" value="2">',
                '2',
                '</option>',
                '<option wire:key="rows-number-per-page-option-3" value="3">',
                '3',
                '</option>',
                '<option wire:key="rows-number-per-page-option-4" value="4">',
                '4',
                '</option>',
                '<option wire:key="rows-number-per-page-option-5" value="5">',
                '5',
                '</option>',
                '</select>',
            ]);
    }

    /** @test */
    public function it_can_set_default_number_of_rows_from_first_option(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', true);
        Config::set('laravel-table.icon.rows_number', 'rows-number-icon');
        Config::set('laravel-table.number_of_rows_per_page_default_options', [1, 2, 3, 4, 5]);
        $users = User::factory()->count(5)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                ];
            }
        };
        $displayedHtml = [];
        $notDisplayedHtml = [];
        foreach ($users as $user) {
            if ($user->id === $users->first()->id) {
                $displayedHtml[] = '<th wire:key="cell-id-' . $user->id . '" class="align-middle" scope="row">';
                $displayedHtml[] = $user->id;
                $displayedHtml[] = '</th>';
            } else {
                $notDisplayedHtml[] = '<th wire:key="cell-id-' . $user->id . '" class="align-middle" scope="row">';
            }
        }
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('numberOfRowsPerPage', 1)
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<select wire:change="changeNumberOfRowsPerPage($event.target.value)" class="form-select" placeholder="Number of rows per page" aria-label="Number of rows per page" aria-describedby="rows-number-per-page-icon">',
                '<option wire:key="rows-number-per-page-option-placeholder" value="" disabled>Number of rows per page</option>',
                '<option wire:key="rows-number-per-page-option-1" value="1" selected>',
                '1',
                '</option>',
                '<option wire:key="rows-number-per-page-option-2" value="2">',
                '2',
                '</option>',
                '<option wire:key="rows-number-per-page-option-3" value="3">',
                '3',
                '</option>',
                '<option wire:key="rows-number-per-page-option-4" value="4">',
                '4',
                '</option>',
                '<option wire:key="rows-number-per-page-option-5" value="5">',
                '5',
                '</option>',
                '</select>',
                '</thead>',
                '<tbody',
                ...$displayedHtml,
                '</tbody',
            ])
            ->assertDontSeeHtml($notDisplayedHtml);
    }

    /** @test */
    public function it_can_change_number_of_rows_per_page_from_select(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', true);
        Config::set('laravel-table.icon.rows_number', 'rows-number-icon');
        Config::set('laravel-table.number_of_rows_per_page_default_options', [1, 2, 3, 4, 5]);
        $users = User::factory()->count(5)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                ];
            }
        };
        $values = [];
        foreach ($users as $user) {
            $values[] = '<th wire:key="cell-id-' . $user->id . '" class="align-middle" scope="row">';
            $values[] = $user->id;
            $values[] = '</th>';
        }
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->call('changeNumberOfRowsPerPage', 5)
            ->assertSet('numberOfRowsPerPage', 5)
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<select wire:change="changeNumberOfRowsPerPage($event.target.value)" class="form-select" placeholder="Number of rows per page" aria-label="Number of rows per page" aria-describedby="rows-number-per-page-icon">',
                '<option wire:key="rows-number-per-page-option-placeholder" value="" disabled>Number of rows per page</option>',
                '<option wire:key="rows-number-per-page-option-1" value="1">',
                '1',
                '</option>',
                '<option wire:key="rows-number-per-page-option-2" value="2">',
                '2',
                '</option>',
                '<option wire:key="rows-number-per-page-option-3" value="3">',
                '3',
                '</option>',
                '<option wire:key="rows-number-per-page-option-4" value="4">',
                '4',
                '</option>',
                '<option wire:key="rows-number-per-page-option-5" value="5" selected>',
                '5',
                '</option>',
                '</select>',
                '</thead>',
                '<tbody',
                ...$values,
                '</tbody',
            ]);
    }

    /** @test */
    public function it_can_set_data_attribute_on_number_of_rows_per_page_selector(): void
    {
        Config::set('laravel-table.html_select_components_attributes', ['data-selector' => true]);
        Config::set('laravel-table.icon.rows_number', 'rows-number-icon');
        User::factory()->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<select wire:change="changeNumberOfRowsPerPage($event.target.value)" class="form-select" placeholder="Number of rows per page" aria-label="Number of rows per page" aria-describedby="rows-number-per-page-icon" data-selector="data-selector">',
                '</thead>',
            ]);
    }
}
