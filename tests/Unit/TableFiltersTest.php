<?php

namespace Tests\Unit;

use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Filters\ActiveFilter;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class TableFiltersTest extends TestCase
{
    /** @test */
    public function it_can_set_filters(): void
    {
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->filters([
                    new ActiveFilter('active'),
                ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Id'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([

            ]);
    }
}
