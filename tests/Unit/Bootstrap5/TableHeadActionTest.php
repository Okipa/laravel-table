<?php

namespace Tests\Unit\Bootstrap5;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\HeadActions\AddHeadAction;
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
        Config::set('laravel-table.icon.add', 'add-icon');
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)
                    ->headAction(new AddHeadAction(route('user.create')));
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
                '<a wire:click.prevent="headAction()"',
                ' class="btn btn-success"',
                ' href=""',
                ' title="Add">',
                'add-icon Add',
                '</a>',
            ])
            ->call('headAction')
            ->assertRedirect(route('user.create'));
    }

    /** @test */
    public function it_can_allow_head_action_conditionally(): void
    {
        app('router')->get('/user/create', ['as' => 'user.create']);
        Config::set('laravel-table.icon.create', 'create-icon');
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)
                    ->headAction((new CreateHeadAction(route('user.create'), true))->when(false));
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
            ->assertDontSeeHtml([
                '<a wire:click.prevent="headAction()"',
                ' class="btn btn-success"',
                ' href=""',
                ' title="Create">',
                'create-icon Create',
                '</a>',
            ])
            ->call('headAction')
            ->assertNotEmitted('laraveltable:link:open:newtab');
    }
}
