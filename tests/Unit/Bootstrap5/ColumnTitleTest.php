<?php

namespace Tests\Unit\Bootstrap5;

use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class ColumnTitleTest extends TestCase
{
    /** @test */
    public function it_can_set_default_column_titles(): void
    {
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('id');
                $table->column('name');
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th class="align-middle" scope="col">',
                'validation.attributes.id',
                '</th>',
                '<th class="align-middle" scope="col">',
                'validation.attributes.name',
                '</th>',
                '</tr>',
                '</thead>',
            ]);
    }

    /** @test */
    public function it_can_set_custom_column_titles(): void
    {
        $config = new class extends AbstractTableConfiguration {
            protected function table(Table $table): void
            {
                $table->model(User::class);
            }

            protected function columns(Table $table): void
            {
                $table->column('id')->title('ID title test');
                $table->column('name')->title('Name title test');
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr',
                '<th class="align-middle" scope="col">',
                'ID title test',
                '</th>',
                '<th class="align-middle" scope="col">',
                'Name title test',
                '</th>',
                '</tr>',
                '</thead>',
            ]);
    }
}
