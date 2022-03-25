<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\CellActions\Toggle;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\RowActions\Destroy;
use Okipa\LaravelTable\RowActions\Edit;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class ColumnCellActionTest extends TestCase
{
    /** @test */
    public function it_can_set_column_set_action(): void
    {
        Config::set('laravel-table.icon.active', 'active-icon');
        Config::set('laravel-table.icon.inactive', 'inactive-icon');
        $userActive = User::factory()->create(['active' => true]);
        $userInactive = User::factory()->create(['active' => false]);
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Name'),
                    Column::make('Toggle', 'active')->cellAction(fn() => new Toggle()),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<tr wire:key="row-' . $userActive->id . '" class="border-bottom">',
                '<a wire:click.prevent="cellAction(\'toggle\', \'' . $userActive->id . '\', \'active\', 0)"',
                'class="link-danger p-1"',
                'title="Toggle">',
                'inactive-icon',
                '</tr>',
                '<tr wire:key="row-' . $userInactive->id . '" class="border-bottom">',
                '<a wire:click.prevent="cellAction(\'toggle\', \'' . $userInactive->id . '\', \'active\', 0)"',
                'class="link-success p-1"',
                'title="Toggle">',
                'active-icon',
                '</tr>',
                '</tbody>',
            ])
            ->call('cellAction', 'toggle', $userActive->id, false);
        $this->assertFalse($userActive->fresh()->active);
    }
}
