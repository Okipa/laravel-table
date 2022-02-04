<?php

namespace Okipa\LaravelTable\Tests\Unit\Bootstrap5;

use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Tests\Models\User;
use Okipa\LaravelTable\Tests\TestCase;

class ColumnDeclarationTest extends TestCase
{
    /** @test */
    public function it_can_declare_column(): void
    {
        $users = User::factory()->count(2)->create();
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
            ->assertSeeInOrder([
                'validation.attributes.id',
                'validation.attributes.name',
                $users->first()->id,
                $users->first()->name,
                $users->last()->id,
                $users->last()->name,
            ]);
    }
}
