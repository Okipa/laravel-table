<?php

namespace Tests\Unit\Bootstrap5;

use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class ColumnFormatTest extends TestCase
{
    /** @test */
    public function it_can_format_row_from_closure(): void
    {
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('name')->format(fn(User $user) => '<b>Test ' . $user->name . '</b>');
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<b>' . e('Test ' . $users->first()->name) . '</b>',
                '<b>' . e('Test ' . $users->last()->name) . '</b>',
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_format_row_from_formatter(): void
    {
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $formatter = new class extends AbstractFormatter {
                    public function format(mixed $row): string
                    {
                        return '<b>Test ' . $row->name . '</b>';
                    }
                };
                $table->column('name')->format($formatter);
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<b>Test ' . e($users->first()->name) . '</b>',
                '<b>Test ' . e($users->last()->name) . '</b>',
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_format_row_with_html_escaping(): void
    {
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('name')->format(fn(User $user) => '<b>Test ' . $user->name . '</b>', true);
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                e('<b>Test ' . $users->first()->name . '</b>'),
                e('<b>Test ' . $users->last()->name . '</b>'),
                '</tbody>',
            ]);
    }
}
