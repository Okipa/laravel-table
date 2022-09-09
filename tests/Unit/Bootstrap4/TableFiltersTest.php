<?php

namespace Tests\Unit\Bootstrap4;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Date;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\Filters\BooleanFilter;
use Okipa\LaravelTable\Filters\NullFilter;
use Okipa\LaravelTable\Filters\RelationshipFilter;
use Okipa\LaravelTable\Table;
use Tests\Models\Company;
use Tests\Models\User;
use Tests\Models\UserCategory;

class TableFiltersTest extends \Tests\Unit\Bootstrap5\TableFiltersTest
{
    /** @test */
    public function it_can_set_filters(): void
    {
        $user1 = User::factory()->create(['email_verified_at' => null, 'active' => false]);
        $user2 = User::factory()->create(['email_verified_at' => Date::now(), 'active' => false]);
        $user3 = User::factory()->create(['email_verified_at' => null, 'active' => true]);
        $category1 = UserCategory::factory()->withUsers(collect([$user1]))->create();
        $category2 = UserCategory::factory()->withUsers(collect([$user2]))->create();
        $category3 = UserCategory::factory()->withUsers(collect([$user3]))->create();
        $company1 = Company::factory()->withOwner($user1)->create();
        $company2 = Company::factory()->withOwner($user2)->create();
        $company3 = Company::factory()->withOwner($user3)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->filters([
                    new NullFilter('Email Verified', 'email_verified_at'),
                    // HasMany Relationship with single selection
                    new RelationshipFilter('Companies', 'companies', Company::pluck('name', 'id')->toArray(), false),
                    // BelongsToMany Relationship with multiple selection
                    new RelationshipFilter('Categories', 'categories', UserCategory::pluck('name', 'id')->toArray()),
                    new BooleanFilter('Active', 'active'),
                ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('id'),
                    Column::make('name'),
                ];
            }
        };
        Livewire::test(\Okipa\LaravelTable\Livewire\Table::class, ['config' => $config::class])
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr>',
                '<td class="px-0 pb-0" colspan="2">',
                '<div class="d-flex flex-wrap align-items-center justify-content-end">',
                // Email Verified
                '<div wire:ignore>',
                '<div wire:key="filter-null-email-verified-at" class="ml-3">',
                '<select wire:model="selectedFilters.null_email_verified_at"',
                'class="form-select"',
                'placeholder="Email Verified"',
                'aria-label="Email Verified">',
                '<option wire:key="filter-option-null-email-verified-at-placeholder" value="" selected>Email Verified</option>',
                '<option wire:key="filter-option-null-email-verified-at-1" value="1">Yes</option>',
                '<option wire:key="filter-option-null-email-verified-at-0" value="0">No</option>',
                '</select>',
                '</div>',
                '</div>',
                // Companies
                '<div wire:ignore>',
                '<div wire:key="filter-relationship-companies" class="ml-3">',
                '<select wire:model="selectedFilters.relationship_companies"',
                'class="form-select"',
                'placeholder="Companies"',
                'aria-label="Companies">',
                '<option wire:key="filter-option-relationship-companies-placeholder" value="" selected>Companies</option>',
                '<option wire:key="filter-option-relationship-companies-' . $company1->id . '" value="' . $company1->id . '">'
                . e($company1->name)
                . '</option>',
                '<option wire:key="filter-option-relationship-companies-' . $company2->id . '" value="' . $company2->id . '">'
                . e($company2->name)
                . '</option>',
                '<option wire:key="filter-option-relationship-companies-' . $company3->id . '" value="' . $company3->id . '">'
                . e($company3->name)
                . '</option>',
                '</select>',
                '</div>',
                '</div>',
                // Categories
                '<div wire:ignore>',
                '<div wire:key="filter-relationship-categories" class="ml-3">',
                '<select wire:model="selectedFilters.relationship_categories"',
                'class="form-select"',
                'placeholder="Categories"',
                'aria-label="Categories">',
                '<option wire:key="filter-option-relationship-categories-placeholder" value="" selected disabled>Categories</option>',
                '<option wire:key="filter-option-relationship-categories-' . $category1->id . '" value="' . $category1->id . '">'
                . e($category1->name)
                . '</option>',
                '<option wire:key="filter-option-relationship-categories-' . $category2->id . '" value="' . $category2->id . '">'
                . e($category2->name)
                . '</option>',
                '<option wire:key="filter-option-relationship-categories-' . $category3->id . '" value="' . $category3->id . '">'
                . e($category3->name)
                . '</option>',
                '</select>',
                '</div>',
                '</div>',
                // Active
                '<div wire:ignore>',
                '<div wire:key="filter-boolean-active" class="ml-3">',
                '<select wire:model="selectedFilters.boolean_active"',
                'class="form-select"',
                'placeholder="Active"',
                'aria-label="Active">',
                '<option wire:key="filter-option-boolean-active-placeholder" value="" selected>Active</option>',
                '<option wire:key="filter-option-boolean-active-1" value="1">Yes</option>',
                '<option wire:key="filter-option-boolean-active-0" value="0">No</option>',
                '</select>',
                '</div>',
                '</div>',
                '</div>',
                '</td>',
                '</tr>',
                '</thead>',
            ])
            // Single filter : Email verified
            ->set('selectedFilters', [
                'null_email_verified_at' => false,
                'relationship_companies' => '',
                'relationship_categories' => [],
                'boolean_active' => '',
            ])
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $user1->name,
                $user3->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $user2->name,
            ])
            // Single filter : Companies
            ->set('selectedFilters', [
                'null_email_verified_at' => '',
                'relationship_companies' => $company1->id,
                'relationship_categories' => [],
                'boolean_active' => '',
            ])
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $user1->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $user2->name,
                $user3->name,
            ])
            // Single filter : Categories
            ->set('selectedFilters', [
                'null_email_verified_at' => '',
                'relationship_companies' => '',
                'relationship_categories' => [$category2->id],
                'boolean_active' => '',
            ])
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $user2->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $user1->name,
                $user3->name,
            ])
            // Single filter : Active
            ->set('selectedFilters', [
                'null_email_verified_at' => '',
                'relationship_companies' => '',
                'relationship_categories' => [],
                'boolean_active' => true,
            ])
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $user3->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $user1->name,
                $user2->name,
            ])
            // Multiple filters : Email Verified + Active
            ->set('selectedFilters', [
                'null_email_verified_at' => true,
                'relationship_companies' => '',
                'relationship_categories' => [],
                'boolean_active' => false,
            ])
            ->assertSeeHtmlInOrder([
                '<tbody>',
                $user2->name,
                '</tbody>',
            ])
            ->assertDontSeeHtml([
                $user1->name,
                $user3->name,
            ]);
    }

    /** @test */
    public function it_can_reset_filters(): void
    {
        Config::set('laravel-table.icon.reset', 'reset-icon');
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->filters([
                    new BooleanFilter('Active', 'active'),
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
            ->call('init')
            ->assertDontSeeHtml('<a wire:click.prevent="resetFilters()"')
            ->set('selectedFilters', ['boolean_active' => null])
            ->assertDontSeeHtml('<a wire:click.prevent="resetFilters()"')
            ->set('selectedFilters', ['boolean_active' => true])
            ->assertSeeHtmlInOrder([
                '<div wire:ignore>',
                '<div wire:key="filter-boolean-active" class="ml-3">',
                '<a wire:click.prevent="resetFilters()"',
                'class="btn btn-outline-secondary ml-3"',
                'title="Reset filters"',
                'data-toggle="tooltip">',
                'reset-icon',
                '</a>',
            ])
            ->set('selectedFilters', ['boolean_active' => false])
            ->assertSeeHtmlInOrder([
                '<div wire:ignore>',
                '<div wire:key="filter-boolean-active" class="ml-3">',
                '<a wire:click.prevent="resetFilters()"',
                'class="btn btn-outline-secondary ml-3"',
                'title="Reset filters"',
                'data-toggle="tooltip">',
                'reset-icon',
                '</a>',
            ])
            ->set('selectedFilters', ['boolean_active' => 0])
            ->assertSeeHtmlInOrder([
                '<div wire:ignore>',
                '<div wire:key="filter-boolean-active" class="ml-3">',
                '<a wire:click.prevent="resetFilters()"',
                'class="btn btn-outline-secondary ml-3"',
                'title="Reset filters"',
                'data-toggle="tooltip">',
                'reset-icon',
                '</a>',
            ])
            ->call('resetFilters')
            ->assertSet('selectedFilters', [])
            ->assertSet('resetFilters', true)
            ->assertEmitted('table:filters:wire:ignore:cancel')
            ->assertDontSeeHtml([
                '<div wire:ignore>',
                '<a wire:click.prevent="resetFilters()"',
            ])
            ->emit('table:filters:wire:ignore:cancel')
            ->assertSet('resetFilters', false)
            ->assertSeeHtmlInOrder([
                '<div wire:ignore>',
                '<div wire:key="filter-boolean-active" class="ml-3">',
            ])
            ->assertDontSeeHtml(['<a wire:click.prevent="resetFilters()"']);
    }

    /** @test */
    public function it_can_set_data_attribute_on_filters(): void
    {
        Config::set('laravel-table.html_select_components_attributes', ['data-selector' => true]);
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->filters([
                    new BooleanFilter('Active', 'active'),
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
            ->call('init')
            ->assertSeeHtmlInOrder([
                '<thead>',
                '<tr>',
                '<td class="px-0 pb-0">',
                '<div class="d-flex flex-wrap align-items-center justify-content-end">',
                '<div wire:ignore>',
                '<div wire:key="filter-boolean-active" class="ml-3">',
                '<select wire:model="selectedFilters.boolean_active"',
                'class="form-select"',
                'placeholder="Active"',
                'aria-label="Active"',
                'data-selector="data-selector">',
                '</select>',
                '</div>',
                '</div>',
                '</div>',
                '</td>',
                '</tr>',
                '</thead>',
            ]);
    }
}
