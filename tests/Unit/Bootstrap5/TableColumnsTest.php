<?php

namespace Tests\Unit\Bootstrap5;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\View\ViewException;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class TableColumnsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cant_generate_table_without_columns(): void
    {
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [];
            }
        };
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('No columns are declared for ' . User::class . ' table.');
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])->call('init');
    }

    /** @test */
    public function it_can_set_column_titles(): void
    {
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
                    Column::make('name'),
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
            ]);
    }

    /** @test */
    public function it_can_display_column_values(): void
    {
        $users = User::factory()->count(2)->create();
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
                    Column::make('name'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<th wire:key="cell-id-' . $users->first()->id . '" class="align-middle" scope="row">',
                $users->first()->id,
                '</th>',
                '<td wire:key="cell-name-' . $users->first()->id . '" class="align-middle">',
                $users->first()->name,
                '</td>',
                '<th wire:key="cell-id-' . $users->last()->id . '" class="align-middle" scope="row">',
                $users->last()->id,
                '</th>',
                '<td wire:key="cell-name-' . $users->last()->id . '" class="align-middle">',
                $users->last()->name,
                '</td>',
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_display_no_results_mention(): void
    {
        Config::set('laravel-table.icon.info', 'info-icon');
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
                    Column::make('name'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<th class="fw-normal text-center align-middle p-3" scope="row" colspan="2">',
                '<span class="text-info">',
                'info-icon',
                '</span>',
                'No results were found.',
                '</th>',
                '</tbody>',
            ]);
    }
}
