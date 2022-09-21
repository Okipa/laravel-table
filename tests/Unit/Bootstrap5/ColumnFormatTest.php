<?php

namespace Tests\Unit\Bootstrap5;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Formatters\BooleanFormatter;
use Okipa\LaravelTable\Formatters\DateFormatter;
use Okipa\LaravelTable\Formatters\StrLimitFormatter;
use Okipa\LaravelTable\Table;
use Tests\Models\Company;
use Tests\Models\User;
use Tests\TestCase;

class ColumnFormatTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_format_column_from_closure(): void
    {
        $users = User::factory()->count(2)->create();
        Company::factory()->count(6)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('companies')
                        ->format(fn (User $user) => '<b> ' . $user->companies->implode('name', ', ') . '</b>'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<b> ' . $users->first()->companies->implode('name', ', ') . '</b>',
                '<b> ' . $users->last()->companies->implode('name', ', ') . '</b>',
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_format_columns_from_formatters(): void
    {
        Config::set('laravel-table.icon.active', 'active-icon');
        Config::set('laravel-table.icon.inactive', 'inactive-icon');
        app('router')->get('/user/{user}/show', ['as' => 'user.show']);
        $user1 = User::factory()->create(['active' => true]);
        Date::setTestNow(Date::now()->addMinute());
        $user2 = User::factory()->create(['active' => false]);
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('name')->format(new StrLimitFormatter(5)),
                    Column::make('active')->format(new BooleanFormatter()),
                    Column::make('created_at')->format(new DateFormatter('d/m:Y H:i:s', 'Europe/Paris')),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<span title="' . $user1->name . '" data-bs-toggle="tooltip">'
                . Str::limit($user1->name, 5)
                . '</span>',
                '<span class="text-success">active-icon</span>',
                $user1->created_at->timezone('Europe/Paris')->format('d/m:Y H:i:s'),
                '<span title="' . $user2->name . '" data-bs-toggle="tooltip">'
                . Str::limit($user2->name, 5)
                . '</span>',
                '<span class="text-danger">inactive-icon</span>',
                $user2->created_at->timezone('Europe/Paris')->format('d/m:Y H:i:s'),
                '</tbody>',
            ]);
    }

    /** @test */
    public function it_can_format_column_with_html_escaping(): void
    {
        $users = User::factory()->count(2)->create();
        Company::factory()->count(6)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('companies')
                        ->format(fn (User $user) => '<b> ' . $user->companies->implode('name', ', ') . '</b>', true),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                e('<b> ' . $users->first()->companies->implode('name', ', ') . '</b>'),
                e('<b> ' . $users->last()->companies->implode('name', ', ') . '</b>'),
                '</tbody>',
            ]);
    }
}
