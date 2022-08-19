<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\HeadActions\CreateHeadAction;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class TableHeadActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_set_table_head_action(): void
    {
        app('router')->get('/user/create', ['as' => 'user.create']);
        Config::set('laravel-table.icon.create', 'create-icon');
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)
                    ->headAction(new CreateHeadAction(route('user.create')));
            }

            protected function columns(): array
            {
                return [
                    Column::make('Name'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<a wire:click.prevent="headAction()"',
                'class="btn btn-success"',
                'href=""',
                'title="Create">',
                'create-icon Create',
                '</a>',
            ])
            ->call('headAction')
            ->assertRedirect(route('user.create'));
    }
}
