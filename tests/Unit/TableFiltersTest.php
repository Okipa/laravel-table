<?php

namespace Tests\Unit;

use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Date;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Filters\ActiveFilter;
use Okipa\LaravelTable\Filters\EmailVerifiedFilter;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class TableFiltersTest extends TestCase
{
    /** @test */
    public function it_can_set_filters(): void
    {
        $users = User::factory()->count(2)->state(new Sequence(
            ['email_verified_at' => Date::now(), 'active' => true],
            ['email_verified_at' => null, 'active' => false]
        ))->create();
        $config = new class extends AbstractTableConfiguration {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->filters([
                    new EmailVerifiedFilter('email_verified_at'),
                    new ActiveFilter('active'),
                ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('Id'),
                    Column::make('Name'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr>',
                '<td class="px-0" colspan="2">',
                '<select wire:change="filter(\'email_verified\', $event.target.value)"',
                'class="form-select"',
                'aria-label="Email Verified">',
                '<option selected>Email Verified</option>',
                '<option value="1">Yes</option>',
                '<option value="0">No</option>',
                '</select>',
                '<select wire:change="filter(\'active\', $event.target.value)"',
                'class="form-select"',
                'aria-label="Active">',
                '<option selected>Active</option>',
                '<option value="1">Yes</option>',
                '<option value="0">No</option>',
                '</select>',
                '</td>',
                '</tr>',
                '</thead>',
            ])
            ->call('filter', 'email_verified', false)
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->last()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $users->first()->name,
            ])
            ->call('filter', 'active', true)
            ->assertDontSeeHtml([
                $users->first()->name,
                $users->last()->name,
            ])
            ->call('filter', 'email_verified', null)
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $users->last()->name,
            ]);
    }
}
