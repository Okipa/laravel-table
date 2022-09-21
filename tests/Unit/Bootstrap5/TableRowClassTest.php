<?php

namespace Tests\Unit\Bootstrap5;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class TableRowClassTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_set_row_classes(): void
    {
        $userActive = User::factory()->create(['active' => true]);
        $userInactive = User::factory()->create(['active' => false]);
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()
                    ->model(User::class)
                    ->rowClass(fn (User $user) => [
                        'inactive' => ! $user->active,
                        'active' => (bool) $user->active,
                        'always',
                    ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('name'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<tr wire:key="row-' . $userActive->id . '" class="active always border-bottom">',
                $userActive->name,
                '<tr wire:key="row-' . $userInactive->id . '" class="inactive always border-bottom">',
                $userInactive->name,
                '</tbody>',
            ]);
    }
}
