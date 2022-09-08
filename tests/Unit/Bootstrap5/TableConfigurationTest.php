<?php

namespace Tests\Unit\Bootstrap5;

use ErrorException;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
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
}
