<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use Okipa\LaravelTable\Console\Commands\MakeBulkAction;
use Okipa\LaravelTable\Console\Commands\MakeColumnAction;
use Okipa\LaravelTable\Console\Commands\MakeFormatter;
use Okipa\LaravelTable\Console\Commands\MakeHeadAction;
use Okipa\LaravelTable\Console\Commands\MakeRowAction;
use Okipa\LaravelTable\Console\Commands\MakeTable;
use Tests\TestCase;

class MakeTest extends TestCase
{
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
            '// The table declaration.',
            '}',
            'protected function columns(): array',
            '{',
            '// The table columns declaration.',
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
            'use Okipa\LaravelTable\Formatters\Date;',
            'use Okipa\LaravelTable\RowActions\Destroy;',
            'use Okipa\LaravelTable\RowActions\Edit;',
            'use Okipa\LaravelTable\Table;',
            'class UsersTable extends AbstractTableConfiguration',
            '{',
            'protected function table(): Table',
            '{',
            'return Table::make()->model(User::class)',
            '->rowActions(fn(User $user) => [',
            'new Edit(route(\'user.edit\', $user)),',
            'new Destroy(),',
            ']);',
            '}',
            'protected function columns(): array',
            '{',
            'return [',
            'Column::make(\'Id\')->sortable();',
            'Column::make(\'Created at\')->format(new Datetime(\'d/m/Y H:i\'))->sortable();',
            'Column::make(\'Updated at\')->format(new Datetime(\'d/m/Y H:i\'))->sortable()->sortByDefault(\'desc\');',
            '];',
            '}',
            '}',
        ]);
    }

    /** @test */
    public function it_can_make_head_action(): void
    {
        $this->artisan(MakeHeadAction::class, ['name' => 'Configure']);
        self::assertFileExists(base_path('app/Tables/HeadActions/Configure.php'));
        $fileContent = File::get(base_path('app/Tables/HeadActions/Configure.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables\HeadActions;',
            'use Livewire\Component;',
            'use Okipa\LaravelTable\Abstracts\AbstractHeadAction;',
            'class Configure extends AbstractHeadAction',
            '{',
            'protected function class(): string',
            '{',
            '// The CSS class that will be applied to the head action button.',
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
            '// The treatment that will be executed on click on the the head action button.',
            '// Use the `$livewire` param to interact with the Livewire table component and emit events for example.',
            '}',
            '}',
        ]);
    }

    /** @test */
    public function it_can_make_bulk_action(): void
    {
        $this->artisan(MakeBulkAction::class, ['name' => 'Activate']);
        self::assertFileExists(base_path('app/Tables/BulkActions/Activate.php'));
        $fileContent = File::get(base_path('app/Tables/BulkActions/Activate.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables\BulkActions;',
            'use Illuminate\Support\Collection;',
            'use Livewire\Component;',
            'use Okipa\LaravelTable\Abstracts\AbstractBulkAction;',
            'class Activate extends AbstractBulkAction',
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
            '// Set `null` if you do not want any confirmation question to be asked by default.',
            '}',
            'protected function defaultFeedbackMessage(array $allowedModelKeys, array $disallowedModelKeys): string|null',
            '{',
            '// The default bulk action feedback message that will be triggered on execution.',
            '// Set `null` if you do not want any feedback message to be triggered by default.',
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
        $this->artisan(MakeRowAction::class, ['name' => 'ToggleActivation']);
        self::assertFileExists(base_path('app/Tables/RowActions/ToggleActivation.php'));
        $fileContent = File::get(base_path('app/Tables/RowActions/ToggleActivation.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables\RowActions;',
            'use Illuminate\Database\Eloquent\Model;',
            'use Livewire\Component;',
            'use Okipa\LaravelTable\Abstracts\AbstractRowAction;',
            'class ToggleActivation extends AbstractRowAction',
            '{',
            'protected function identifier(): string',
            '{',
            '// The unique identifier that is required to retrieve the row action.',
            '}',
            'protected function class(): string',
            '{',
            '// The CSS class that will be applies to the row action link.',
            '}',
            'protected function title(): string',
            '{',
            '// The title that will be set to the row action link.',
            '}',
            'protected function icon(): string',
            '{',
            '// The icon that will appear in the row action link.',
            '}',
            'protected function defaultConfirmationQuestion(Model $model): string|null',
            '{',
            '// The default row action confirmation question that will be asked before execution.',
            '// Set `null` if you do not want any confirmation question to be asked by default.',
            '}',
            'protected function defaultFeedbackMessage(Model $model): string|null',
            '{',
            '// The default row action feedback message that will be triggered on execution.',
            '// Set `null` if you do not want any feedback message to be triggered by default.',
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
        $this->artisan(MakeFormatter::class, ['name' => 'BooleanFormatter']);
        self::assertFileExists(base_path('app/Tables/Formatters/BooleanFormatter.php'));
        $fileContent = File::get(base_path('app/Tables/Formatters/BooleanFormatter.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables\Formatters;',
            'use Illuminate\Database\Eloquent\Model;',
            'use Okipa\LaravelTable\Abstracts\AbstractFormatter;',
            'class BooleanFormatter extends AbstractFormatter',
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
        $this->artisan(MakeColumnAction::class, ['name' => 'ColumnToggleBoolean']);
        self::assertFileExists(base_path('app/Tables/ColumnActions/ColumnToggleBoolean.php'));
        $fileContent = File::get(base_path('app/Tables/ColumnActions/ColumnToggleBoolean.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables\ColumnActions;',
            'use Illuminate\Database\Eloquent\Model;',
            'use Livewire\Component;',
            'use Okipa\LaravelTable\Abstracts\AbstractColumnAction;',
            'class ColumnToggleBoolean extends AbstractColumnAction',
            '{',
            'protected function class(Model $model, string $attribute): string',
            '{',
            '// The CSS class that will be applies to the column action link.',
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
            '// Set `null` if you do not want any confirmation question to be asked by default.',
            '}',
            'protected function defaultFeedbackMessage(Model $model, string $attribute): string|null',
            '{',
            '// The default column action feedback message that will be triggered on execution.',
            '// Set `null` if you do not want any feedback message to be triggered by default.',
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
