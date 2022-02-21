<?php

namespace Tests\Unit\Bootstrap5;

use Illuminate\View\ViewException;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Table;
use Tests\Models\Company;
use Tests\Models\User;
use Tests\TestCase;

class TableColumnsTest extends TestCase
{
    /** @test */
    public function it_cant_generate_table_without_columns(): void
    {
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                //
            }
        };
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('No columns are declared for ' . User::class . ' table.');
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])->call('init');
    }
}
