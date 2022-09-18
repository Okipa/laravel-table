<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\BulkActions\ActivateBulkAction;
use Okipa\LaravelTable\BulkActions\DestroyBulkAction;
use Okipa\LaravelTable\Column;
use Okipa\LaravelTable\ColumnActions\ToggleBooleanColumnAction;
use Okipa\LaravelTable\ColumnActions\ToggleEmailVerifiedColumnAction;
use Okipa\LaravelTable\Facades\TestableTable;
use Okipa\LaravelTable\RowActions\DestroyRowAction;
use Okipa\LaravelTable\RowActions\EditRowAction;
use Okipa\LaravelTable\Table;
use Tests\Models\User;
use Tests\TestCase;

class AssertionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_check_related_table_model(): void
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
                    Column::make('name'),
                ];
            }
        };
        TestableTable::test($config::class)->usesModel(User::class);
    }

    /** @test */
    public function it_can_check_whether_bulk_action_allows_models(): void
    {
        $users = User::factory()->count(3)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->bulkActions(fn (User $user) => [
                    (new ActivateBulkAction('active'))->when(Auth::user()->is($user)),
                    (new DestroyBulkAction())->when(Auth::user()->isNot($user)),
                ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('name'),
                ];
            }
        };
        TestableTable::actingAs($users->first())
            ->test($config::class)
            ->bulkActionAllowsModels(ActivateBulkAction::class, [$users->get(0)->id])
            ->bulkActionDisallowsModels(ActivateBulkAction::class, [$users->get(1)->id, $users->get(2)->id])
            ->bulkActionAllowsModels(DestroyBulkAction::class, [$users->get(1)->id, $users->get(2)->id])
            ->bulkActionDisallowsModels(DestroyBulkAction::class, [$users->get(0)->id]);
    }

    /** @test */
    public function it_can_check_whether_row_action_allows_models(): void
    {
        app('router')->get('/user/{user}/edit', ['as' => 'user.edit']);
        $users = User::factory()->count(3)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class)->rowActions(fn (User $user) => [
                    (new EditRowAction(route('user.edit', $user)))->when(Auth::user()->is($user)),
                    (new DestroyRowAction())->when(Auth::user()->isNot($user)),
                ]);
            }

            protected function columns(): array
            {
                return [
                    Column::make('name'),
                ];
            }
        };
        TestableTable::actingAs($users->first())
            ->test($config::class)
            ->rowActionAllowsModels(EditRowAction::class, [$users->get(0)->id])
            ->rowActionDisallowsModels(EditRowAction::class, [$users->get(1)->id, $users->get(2)->id])
            ->rowActionAllowsModels(DestroyRowAction::class, [$users->get(1)->id, $users->get(2)->id])
            ->rowActionDisallowsModels(DestroyRowAction::class, [$users->get(0)->id]);
    }

    /** @test */
    public function it_can_check_whether_column_action_allows_models(): void
    {
        app('router')->get('/user/{user}/edit', ['as' => 'user.edit']);
        $users = User::factory()->count(3)->create();
        $config = new class extends AbstractTableConfiguration
        {
            protected function table(): Table
            {
                return Table::make()->model(User::class);
            }

            protected function columns(): array
            {
                return [
                    Column::make('email_verified_at')
                        ->action(fn (User $user) => (new ToggleEmailVerifiedColumnAction())->when(Auth::user()->is($user))),
                    Column::make('active')
                        ->action(fn (User $user) => (new ToggleBooleanColumnAction())->when(Auth::user()->isNot($user))),
                ];
            }
        };
        TestableTable::actingAs($users->first())
            ->test($config::class)
            ->columnActionAllowsModels(ToggleEmailVerifiedColumnAction::class, [$users->get(0)->id])
            ->columnActionDisallowsModels(ToggleEmailVerifiedColumnAction::class, [$users->get(1)->id, $users->get(2)->id])
            ->columnActionAllowsModels(ToggleBooleanColumnAction::class, [$users->get(1)->id, $users->get(2)->id])
            ->columnActionDisallowsModels(ToggleBooleanColumnAction::class, [$users->get(0)->id]);
    }
}
