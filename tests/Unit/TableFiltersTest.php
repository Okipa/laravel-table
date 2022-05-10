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
                '<div wire:key="filter-email-verified">',
                '<select wire:model="selectedFilters.email_verified"',
                'class="form-select"',
                'aria-label="Email Verified">',
                '<option wire:key="filter-option-email-verified-placeholder" selected>Email Verified</option>',
                '<option wire:key="filter-option-email-verified-1" value="1">Yes</option>',
                '<option wire:key="filter-option-email-verified-0" value="0">No</option>',
                '</select>',
                '</div>',
                '<div wire:key="filter-active">',
                '<select wire:model="selectedFilters.active"',
                'class="form-select"',
                'aria-label="Active">',
                '<option wire:key="filter-option-active-placeholder" selected>Active</option>',
                '<option wire:key="filter-option-active-1" value="1">Yes</option>',
                '<option wire:key="filter-option-active-0" value="0">No</option>',
                '</select>',
                '</div>',
                '</td>',
                '</tr>',
                '</thead>',
            ])
            ->set('selectedFilters', [
                'email_verified' => false,
                'active' => null,
            ])
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->last()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $users->first()->name,
            ])
            ->set('selectedFilters', [
                'email_verified' => false,
                'active' => true,
            ])
            ->assertDontSeeHtml([
                $users->first()->name,
                $users->last()->name,
            ])
            ->set('selectedFilters', [
                'email_verified' => null,
                'active' => true,
            ])
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $users->first()->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $users->last()->name,
            ])
        ;
    }
}
