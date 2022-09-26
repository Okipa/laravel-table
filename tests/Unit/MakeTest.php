<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Okipa\LaravelTable\Console\Commands\MakeBulkAction;
use Okipa\LaravelTable\Console\Commands\MakeColumnAction;
use Okipa\LaravelTable\Console\Commands\MakeFilter;
use Okipa\LaravelTable\Console\Commands\MakeFormatter;
use Okipa\LaravelTable\Console\Commands\MakeHeadAction;
use Okipa\LaravelTable\Console\Commands\MakeRowAction;
use Okipa\LaravelTable\Console\Commands\MakeTable;
use Tests\TestCase;

class MakeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        File::deleteDirectory(base_path('app/Tables'));
    }

    /** @test */
    public function it_can_make_table(): void
    {
        $this->artisan(MakeTable::class, ['name' => 'UsersTable']);
        self::assertFileExists(base_path('app/Tables/UsersTable.php'));
        $fileContent = File::get(base_path('app/Tables/UsersTable.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables;',
            'use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;',
            'use Okipa\LaravelTable\Table;',
            'class UsersTable extends AbstractTableConfiguration',
            '{',
            'protected function table(): Table',
            '{',
            '// The table configuration.',
            '}',
            'protected function columns(): array',
            '{',
            '// The table columns configuration.',
            '}',
            'protected function results(): array',
            '{',
            '// The table results configuration.',
            '// As results are optional on tables, you may delete this method if you do not use it.',
            '}',
            '}',
        ]);
    }

    /** @test */
    public function it_can_make_table_with_model_option(): void
    {
        $this->artisan(MakeTable::class, ['name' => 'UsersTable', '--model' => 'App\Models\User']);
        self::assertFileExists(base_path('app/Tables/UsersTable.php'));
        $fileContent = File::get(base_path('app/Tables/UsersTable.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables;',
            'use App\Models\User;',
            'use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;',
            'use Okipa\LaravelTable\Column;',
            'use Okipa\LaravelTable\Formatters\DateFormatter;',
            'use Okipa\LaravelTable\RowActions\DestroyRowAction;',
            'use Okipa\LaravelTable\RowActions\EditRowAction;',
            'use Okipa\LaravelTable\Table;',
            'class UsersTable extends AbstractTableConfiguration',
            '{',
            'protected function table(): Table',
            '{',
            'return Table::make()->model(User::class)',
            '->rowActions(fn(User $user) => [',
            'new EditRowAction(route(\'user.edit\', $user)),',
            'new DestroyRowAction(),',
            ']);',
            '}',
            'protected function columns(): array',
            '{',
            'return [',
            'Column::make(\'id\')->sortable(),',
            'Column::make(\'created_at\')->format(new DateFormatter(\'d/m/Y H:i\'))->sortable(),',
            'Column::make(\'updated_at\')->format(new DateFormatter(\'d/m/Y H:i\'))->sortable()->sortByDefault(\'desc\'),',
            '];',
            '}',
            'protected function results(): array',
            '{',
            '// The table results configuration.',
            '// As results are optional on tables, you may delete this method if you do not use it.',
            '}',
            '}',
        ]);
    }

    /** @test */
    public function it_can_make_filter(): void
    {
        $this->artisan(MakeFilter::class, ['name' => 'MyNewFilter']);
        self::assertFileExists(base_path('app/Tables/Filters/MyNewFilter.php'));
        $fileContent = File::get(base_path('app/Tables/Filters/MyNewFilter.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables\Filters;',
            'use Illuminate\Database\Eloquent\Builder;',
            'use Okipa\LaravelTable\Abstracts\AbstractFilter;',
            'class MyNewFilter extends AbstractFilter',
            '{',
            'protected function identifier(): string',
            '{',
            '// The unique identifier that is required to retrieve the filter.',
            '}',
            'protected function class(): array',
            '{',
            'return [',
            '// The CSS class that will be merged to the existent ones on the filter select.',
            '// As class are optional on filters, you may delete this method if you don\'t declare any specific class.',
            '// Note: you can use conditional class merging as specified here: https://laravel.com/docs/blade#conditionally-merge-classes',
            '...parent::class(),',
            ']',
            '}',
            'protected function attributes(): array',
            '{',
            'return [',
            '// The HTML attributes that will be merged to the existent ones on the filter select.',
            '// As attributes are optional on filters, you may delete this method if you do declare any specific attributes.',
            '...parent::attributes(),',
            ']',
            '}',
            'protected function label(): string',
            '{',
            '// The label that will appear in the filter select.',
            '}',
            'protected function multiple(): bool',
            '{',
            '// Whether the filter select should allow multiple option to be selected.',
            '}',
            'protected function options(): array',
            '{',
            '// The options that will be available in the filter select.',
            '}',
            'public function filter(Builder $query, mixed $selected): void',
            '{',
            '// The filtering treatment that will be executed on option selection.',
            '// The $selected attribute will provide an array in multiple mode and a value in single mode.',
            '}',
            '}',
        ]);
    }

    /** @test */
    public function it_can_make_head_action(): void
    {
        $this->artisan(MakeHeadAction::class, ['name' => 'MyNewHeadAction']);
        self::assertFileExists(base_path('app/Tables/HeadActions/MyNewHeadAction.php'));
        $fileContent = File::get(base_path('app/Tables/HeadActions/MyNewHeadAction.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables\HeadActions;',
            'use Livewire\Component;',
            'use Okipa\LaravelTable\Abstracts\AbstractHeadAction;',
            'class MyNewHeadAction extends AbstractHeadAction',
            '{',
            'protected function class(): array',
            '{',
            '// The CSS class that will be added to the head action button.',
            '// Note: you can use conditional class merging as specified here: https://laravel.com/docs/blade#conditionally-merge-classes',
            '}',
            'protected function icon(): string',
            '{',
            '// The icon that will be displayed in the head action button.',
            '}',
            'protected function title(): string',
            '{',
            '// The title that will be displayed in the head action button.',
            '}',
            '/** @return mixed|void */',
            'public function action(Component $livewire)',
            '{',
            '// The treatment that will be executed on click on the head action button.',
            '// Use the `$livewire` param to interact with the Livewire table component and emit events for example.',
            '}',
            '}',
        ]);
    }

    /** @test */
    public function it_can_make_bulk_action(): void
    {
        $this->artisan(MakeBulkAction::class, ['name' => 'MyNewBulkAction']);
        self::assertFileExists(base_path('app/Tables/BulkActions/MyNewBulkAction.php'));
        $fileContent = File::get(base_path('app/Tables/BulkActions/MyNewBulkAction.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables\BulkActions;',
            'use Illuminate\Support\Collection;',
            'use Livewire\Component;',
            'use Okipa\LaravelTable\Abstracts\AbstractBulkAction;',
            'class MyNewBulkAction extends AbstractBulkAction',
            '{',
            'protected function identifier(): string',
            '{',
            '// The unique identifier that is required to retrieve the bulk action.',
            '}',
            'protected function label(array $allowedModelKeys): string|null',
            '{',
            '// The label that will appear in the bulk action link.',
            '}',
            'protected function defaultConfirmationQuestion(array $allowedModelKeys, array $disallowedModelKeys): string|null',
            '{',
            '// The default bulk action confirmation question that will be asked before execution.',
            '// Return `null` if you do not want any confirmation question to be asked by default.',
            '}',
            'protected function defaultFeedbackMessage(array $allowedModelKeys, array $disallowedModelKeys): string|null',
            '{',
            '// The default bulk action feedback message that will be triggered on execution.',
            '// Return `null` if you do not want any feedback message to be triggered by default.',
            '}',
            '/** @return mixed|void */',
            'public function action(Collection $models, Component $livewire)',
            '{',
            '// The treatment that will be executed on click on the bulk action link.',
            '// Use the `$livewire` param to interact with the Livewire table component and emit events for example.',
            '}',
            '}',
        ]);
    }

    /** @test */
    public function it_can_make_row_action(): void
    {
        $this->artisan(MakeRowAction::class, ['name' => 'MyNewRowAction']);
        self::assertFileExists(base_path('app/Tables/RowActions/MyNewRowAction.php'));
        $fileContent = File::get(base_path('app/Tables/RowActions/MyNewRowAction.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables\RowActions;',
            'use Illuminate\Database\Eloquent\Model;',
            'use Livewire\Component;',
            'use Okipa\LaravelTable\Abstracts\AbstractRowAction;',
            'class MyNewRowAction extends AbstractRowAction',
            '{',
            'protected function identifier(): string',
            '{',
            '// The unique identifier that is required to retrieve the row action.',
            '}',
            'protected function class(Model $model): array',
            '{',
            '// The CSS class that will be added to the row action link.',
            '// Note: you can use conditional class merging as specified here: https://laravel.com/docs/blade#conditionally-merge-classes',
            '}',
            'protected function title(Model $model): string',
            '{',
            '// The title that will be set to the row action link.',
            '}',
            'protected function icon(Model $model): string',
            '{',
            '// The icon that will appear in the row action link.',
            '}',
            'protected function defaultConfirmationQuestion(Model $model): string|null',
            '{',
            '// The default row action confirmation question that will be asked before execution.',
            '// Return `null` if you do not want any confirmation question to be asked by default.',
            '}',
            'protected function defaultFeedbackMessage(Model $model): string|null',
            '{',
            '// The default row action feedback message that will be triggered on execution.',
            '// Return `null` if you do not want any feedback message to be triggered by default.',
            '}',
            '/** @return mixed|void */',
            'public function action(Model $model, Component $livewire)',
            '{',
            '// The treatment that will be executed on click on the row action link.',
            '// Use the `$livewire` param to interact with the Livewire table component and emit events for example.',
            '}',
            '}',
        ]);
    }

    /** @test */
    public function it_can_make_column_formatter(): void
    {
        $this->artisan(MakeFormatter::class, ['name' => 'MyNewFormatter']);
        self::assertFileExists(base_path('app/Tables/Formatters/MyNewFormatter.php'));
        $fileContent = File::get(base_path('app/Tables/Formatters/MyNewFormatter.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables\Formatters;',
            'use Illuminate\Database\Eloquent\Model;',
            'use Okipa\LaravelTable\Abstracts\AbstractFormatter;',
            'class MyNewFormatter extends AbstractFormatter',
            '{',
            'public function format(Model $model, string $attribute): string',
            '{',
            '// The formatting that will be displayed in column cells.',
            '}',
            '}',
        ]);
    }

    /** @test */
    public function it_can_make_column_action(): void
    {
        $this->artisan(MakeColumnAction::class, ['name' => 'MyNewColumnAction']);
        self::assertFileExists(base_path('app/Tables/ColumnActions/MyNewColumnAction.php'));
        $fileContent = File::get(base_path('app/Tables/ColumnActions/MyNewColumnAction.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables\ColumnActions;',
            'use Illuminate\Database\Eloquent\Model;',
            'use Livewire\Component;',
            'use Okipa\LaravelTable\Abstracts\AbstractColumnAction;',
            'class MyNewColumnAction extends AbstractColumnAction',
            '{',
            'protected function class(Model $model, string $attribute): array',
            '{',
            '// The CSS class that will be added to the column action link.',
            '// Note: you can use conditional class merging as specified here: https://laravel.com/docs/blade#conditionally-merge-classes',
            '}',
            'protected function title(Model $model, string $attribute): string',
            '{',
            '// The title that will be set to the column action link.',
            '}',
            'protected function icon(Model $model, string $attribute): string',
            '{',
            '// The icon that will appear in the column action link.',
            '}',
            'protected function label(Model $model, string $attribute): string|null',
            '{',
            '// The label that will appear in the column action link.',
            '}',
            'protected function defaultConfirmationQuestion(Model $model, string $attribute): string|null',
            '{',
            '// The default column action confirmation question that will be asked before execution.',
            '// Return `null` if you do not want any confirmation question to be asked by default.',
            '}',
            'protected function defaultFeedbackMessage(Model $model, string $attribute): string|null',
            '{',
            '// The default column action feedback message that will be triggered on execution.',
            '// Return `null` if you do not want any feedback message to be triggered by default.',
            '}',
            '/** @return mixed|void */',
            'public function action(Model $model, string $attribute, Component $livewire)',
            '{',
            '// The treatment that will be executed on click on the column action link.',
            '// Use the `$livewire` param to interact with the Livewire table component and emit events for example.',
            '}',
            '}',
        ]);
    }
}
