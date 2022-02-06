<?php

namespace Okipa\LaravelTable\Tests\Unit\Bootstrap5;

use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Tests\Models\User;
use Okipa\LaravelTable\Tests\TestCase;

class NumberOfRowsPerPageTest extends TestCase
{
    /** @test */
    public function it_can_display_number_of_rows_per_page_elements(): void
    {
        Config::set('laravel-table.icon.rows_number', 'rows-number-icon');
        Config::set('laravel-table.icon.validate', 'validate-icon');
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('id');
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtml([
                '<form wire:submit.prevent="setNumberOfRowsPerPage">',
                '<input wire:model.defer="number_of_rows_per_page"',
                'rows-number-icon',
                '<button',
                'validate-icon',
            ]);
    }

    /** @test */
    public function it_can_set_a_global_default_number_of_rows_per_page_from_config(): void
    {
        Config::set('laravel-table.number_of_rows_per_page', 1);
        $users = User::factory()->count(5)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('id');
            }
        };
        $component = Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('number_of_rows_per_page', 1);
        foreach ($users as $user) {
            if ($user->id === $users->first()->id) {
                $component->assertSeeHtml('<td>' . $user->id . '</td>');
            } else {
                $component->assertDontSeeHtml('<td>' . $user->id . '</td>');
            }
        }
    }

    /** @test */
    public function it_can_set_a_specific_default_number_of_rows_per_page_from_table(): void
    {
        Config::set('laravel-table.number_of_rows_per_page', 10);
        $users = User::factory()->count(5)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class)->numberOfRowsPerPage(1);
            }

            protected function columns(Table $table): void
            {
                $table->column('id');
            }
        };
        $component = Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('number_of_rows_per_page', 1);
        foreach ($users as $user) {
            if ($user->id === $users->first()->id) {
                $component->assertSeeHtml('<td>' . $user->id . '</td>');
            } else {
                $component->assertDontSeeHtml('<td>' . $user->id . '</td>');
            }
        }
    }
}
