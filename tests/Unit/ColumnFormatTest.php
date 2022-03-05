<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractFormatter;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Formatters\Date;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class ColumnFormatTest extends TestCase
{
    /** @test */
    public function it_can_format_column_from_closure(): void
    {
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Name')->format(fn(User $user) => '<b>Test ' . $user->name . '</b>'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<b>Test ' . $users->first()->name . '</b>',
                '<b>Test ' . $users->last()->name . '</b>',
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_format_column_from_formatter(): void
    {
        $user1 = User::factory()->create();
        Date::setTestNow(Date::now()->addMinute());
        $user2 = User::factory()->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Created At')->format(new Date('d/m:Y H:i:s')),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $user1->created_at->format('d/m:Y H:i:s'),
                $user2->created_at->format('d/m:Y H:i:s'),
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_format_column_with_html_escaping(): void
    {
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Name')->format(fn(User $user) => '<b>Test ' . $user->name . '</b>', true),
                ];
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
