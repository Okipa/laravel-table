<?php

namespace Tests\Unit\Bootstrap4;

use Illuminate\Foundation\Auth\User;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;

class TableConfigurationTest extends \Tests\Unit\Bootstrap5\TableConfigurationTest
{
    /** @test */
    public function it_can_display_loader_before_initialization(): void
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
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->assertSeeHtmlInOrder([
                '<div class="d-flex align-items-center py-3">',
                '<div class="spinner-border text-dark mr-3" role="status">',
                '<span class="sr-only">Loading in progress...</span>',
                '</div>',
                'Loading in progress...',
                '</div>',
            ]);
    }
}
