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
    public function it_cant_set_number_of_rows_per_page_options_when_feature_is_globally_disabled(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', false);
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
            ->assertDontSeeHtml('<select wire:change="changeNumberOfRowsPerPage($event.target.value)"');
    }

    /** @test */
    public function it_cant_set_number_of_rows_per_page_options_when_feature_is_disabled_from_table(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', true);
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class)->enableNumberOfRowsPerPageChoice(false);
            }

            protected function columns(Table $table): void
            {
                $table->column('id');
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
            ->assertSeeHtml('<select wire:change="changeNumberOfRowsPerPage($event.target.value)"');
    }

    /** @test */
    public function it_can_set_number_of_rows_per_page_options_when_feature_is_enabled_from_table(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', false);
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class)->enableNumberOfRowsPerPageChoice(true);
            }

            protected function columns(Table $table): void
            {
                $table->column('id');
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtml('<select wire:change="changeNumberOfRowsPerPage($event.target.value)"');
    }

    /** @test */
    public function it_can_set_global_default_number_of_rows_per_page_options_from_config(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', true);
        Config::set('laravel-table.icon.rows_number', 'rows-number-icon');
        Config::set('laravel-table.number_of_rows_per_page_options', [1, 2, 3, 4, 5]);
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
            ->assertSet('numberOfRowsPerPage', 1)
            ->assertSeeHtmlInOrder([
                'rows-number-icon',
                '<select wire:change="changeNumberOfRowsPerPage($event.target.value)"',
                '<option value="1" selected>',
                '<option value="2">',
                '<option value="3">',
                '<option value="4">',
                '<option value="5">',
            ]);
    }

    /** @test */
    public function it_can_set_specific_number_of_rows_per_page_options_from_table(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', true);
        Config::set('laravel-table.icon.rows_number', 'rows-number-icon');
        Config::set('laravel-table.number_of_rows_per_page_options', [10, 25, 50, 75, 100]);
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class)->numberOfRowsPerPageOptions([1, 2, 3, 4, 5]);
            }

            protected function columns(Table $table): void
            {
                $table->column('id');
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSet('numberOfRowsPerPage', 1)
            ->assertSeeHtmlInOrder([
                'rows-number-icon',
                '<select wire:change="changeNumberOfRowsPerPage($event.target.value)"',
                '<option value="1" selected>',
                '<option value="2">',
                '<option value="3">',
                '<option value="4">',
                '<option value="5">',
            ]);
    }

    /** @test */
    public function it_can_set_default_number_of_rows_from_from_first_option(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', true);
        Config::set('laravel-table.number_of_rows_per_page_options', [1, 2, 3, 4, 5]);
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
            ->assertSet('numberOfRowsPerPage', 1)
            ->assertSeeHtml('<option value="1" selected>');
        foreach ($users as $user) {
            if ($user->id === $users->first()->id) {
                $component->assertSeeHtml('<td>' . $user->id . '</td>');
            } else {
                $component->assertDontSeeHtml('<td>' . $user->id . '</td>');
            }
        }
    }

    /** @test */
    public function it_can_change_number_of_rows_per_page_from_select(): void
    {
        Config::set('laravel-table.enable_number_of_rows_per_page_choice', true);
        Config::set('laravel-table.number_of_rows_per_page_options', [1, 2, 3, 4, 5]);
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
            ->call('changeNumberOfRowsPerPage', 5)
            ->assertSet('numberOfRowsPerPage', 5)
            ->assertSeeHtml('<option value="5" selected>');
        foreach ($users as $user) {
            $component->assertSeeHtml('<td>' . $user->id . '</td>');
        }
    }
}
