<?php

namespace Tests\Unit\Bootstrap5;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class TableQueryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_set_query(): void
    {
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration
        {
            public int $userIdToExclude;

            protected function table(): Table
            {
                return Table::make()
                    ->model(User::class)
                    ->query(fn (Builder $query) => $query->where('id', '!=', $this->userIdToExclude));
            }

            protected function columns(): array
            {
                return [Column::make('id')];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, [
            'config' => $config::class,
            'configParams' => ['userIdToExclude' => $users->first()->id],
        ])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<th wire:key="cell-id-' . $users->last()->id . '" class="align-middle" scope="row">',
                $users->last()->id,
                '</th>',
            ])
            ->assertDontSeeHtml([
                '<th wire:key="cell-id-' . $users->first()->id . '" class="align-middle" scope="row">',
            ]);
    }
}
