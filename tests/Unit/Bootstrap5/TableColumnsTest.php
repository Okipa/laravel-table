<?php

namespace Tests\Unit\Bootstrap5;

use Illuminate\View\ViewException;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Table;
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

    /** @test */
    public function it_can_set_column_titles(): void
    {
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('Id');
                $table->column('Name');
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th class="align-middle" scope="col">',
                'Id',
                '</th>',
                '<th class="align-middle" scope="col">',
                'Name',
                '</th>',
                '</tr>',
                '</thead>',
            ]);
    }

    /** @test */
    public function it_can_set_custom_column_keys(): void
    {
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('Id', 'custom_id');
                $table->column('Name', 'custom_name');
            }
        };
        $component = Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th class="align-middle" scope="col">',
                'Id',
                '</th>',
                '<th class="align-middle" scope="col">',
                'Name',
                '</th>',
                '</tr>',
                '</thead>',
            ]);
        dd($component->table->getColumns());
    }

    /** @test */
    public function it_can_display_column_values(): void
    {
        $users = User::factory()->count(2)->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('Id');
                $table->column('Name');
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<tbody>',
                '<th class="align-middle" scope="row">' . $users->first()->id . '</th>',
                '<td class="align-middle">' . $users->first()->name . '</td>',
                '<th class="align-middle" scope="row">' . $users->last()->id . '</th>',
                '<td class="align-middle">' . $users->last()->name . '</td>',
                '</tbody>',
            ]);
    }
}
