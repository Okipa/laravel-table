<?php

namespace Tests\Unit\Bootstrap5;

use ErrorException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class TableConfigurationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_cant_generate_table_with_wrong_configuration(): void
    {
        $config = new class
        {
            //
        };
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The given ' . $config::class
            . ' table config should extend ' . AbstractTableConfiguration::class . '.');
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])->call('init');
    }

    /** @test */
    public function it_can_trigger_event_on_initialization(): void
    {
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()
                    ->model(User::class)
                    ->emitEventsOnLoad([
                        'simple:test:event',
                        'test:event:with:params' => ['my', 'test', 'event', 'params'],
                    ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->assertEmitted('simple:test:event')
            ->assertEmitted('test:event:with:params', ['my', 'test', 'event', 'params']);
    }

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
                '<div class="spinner-border text-dark me-3" role="status">',
                '<span class="visually-hidden">Loading in progress...</span>',
                '</div>',
                'Loading in progress...',
                '</div>',
            ]);
    }

    /** @test */
    public function it_can_refresh_table(): void
    {
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration
        {
            public int|null $userIdToExclude = null;

            protected function table(): Table
            {
                return Table::make()
                    ->model(User::class)
                    ->query(fn (Builder $query) => $query->when(
                        $this->userIdToExclude,
                        fn ($subWhenQuery) => $subWhenQuery->where('id', '!=', $this->userIdToExclude)
                    ));
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
            // No config targeting
            ->emit('laraveltable:refresh', ['userIdToExclude' => $users->first()->id])
            ->assertSet('configParams', ['userIdToExclude' => $users->first()->id])
            ->assertSeeHtmlInOrder([
                '<th wire:key="cell-name-' . $users->last()->id . '" class="align-middle" scope="row">',
                $users->last()->name,
                '</th>',
            ])
            ->assertDontSeeHtml([
                '<th wire:key="cell-name-' . $users->first()->id . '" class="align-middle" scope="row">',
            ])
            // With not existing config targeting
            ->set('configParams', [])
            ->emit('laraveltable:refresh', ['userIdToExclude' => $users->first()->id], ['NotExistingNamespace'])
            ->assertSet('configParams', [])
            ->assertSeeHtmlInOrder([
                '<th wire:key="cell-name-' . $users->first()->id . '" class="align-middle" scope="row">',
                $users->first()->name,
                '</th>',
                '<th wire:key="cell-name-' . $users->last()->id . '" class="align-middle" scope="row">',
                $users->last()->name,
                '</th>',
            ])
            // With existing config targeting
            ->set('configParams', [])
            ->emit('laraveltable:refresh', ['userIdToExclude' => $users->first()->id], [$config::class])
            ->assertSet('configParams', ['userIdToExclude' => $users->first()->id])
            ->assertSeeHtmlInOrder([
                '<th wire:key="cell-name-' . $users->last()->id . '" class="align-middle" scope="row">',
                $users->last()->name,
                '</th>',
            ])
            ->assertDontSeeHtml([
                '<th wire:key="cell-name-' . $users->first()->id . '" class="align-middle" scope="row">',
            ]);
    }
}
