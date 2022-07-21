<?php

namespace Tests\Unit;

use ErrorException;
use Illuminate\Foundation\Auth\User;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Table;
use Tests\TestCase;

class TableConfigurationTest extends TestCase
{
    /** @test */
    public function it_cant_generate_table_with_wrong_configuration(): void
    {
        $config = new class {
            //
        };
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The given ' . $this->config
            . ' table config should extend ' . AbstractTableConfiguration::class . '.');
        Livewire::test(Table::class, ['config' => $config::class])->call('init');
    }

    /** @test */
    public function it_can_trigger_event_on_initialization(): void
    {
        $config = new class extends AbstractTableConfiguration {
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
                    Column::make('Id'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->assertEmitted('simple:test:event')
            ->assertEmitted('test:event:with:params', ['my', 'test', 'event', 'params']);
    }
}
