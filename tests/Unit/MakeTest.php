<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
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
    public function it_can_make_formatter(): void
    {
        $this->artisan(MakeFormatter::class, ['name' => 'Boolean']);
        self::assertFileExists(base_path('app/Tables/Formatters/Boolean.php'));
        $fileContent = File::get(base_path('app/Tables/Formatters/Boolean.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables\Formatters;',
            'use Illuminate\Database\Eloquent\Model;',
            'use Okipa\LaravelTable\Abstracts\AbstractFormatter;',
            'class Boolean extends AbstractFormatter',
            '{',
            'public function format(Model $model, string $attribute): string',
            '{',
            '// The formatting that will be displayed in column cells.',
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
            'protected function icon(): string',
            '{',
            '// The icon that will be displayed in the row action link.',
            '}',
            'protected function title(): string',
            '{',
            '// The title that will be set to the row action link.',
            '}',
            'protected function shouldBeConfirmed(): bool',
            '{',
            '// Whether the row action should be confirmed before being executed.',
            '}',
            '/** @return mixed|void */',
            'public function action(Model $model, Component $livewire)',
            '{',
            '// The treatment that will be executed on click on the row action link.',
            '}',
            '}',
        ]);
    }

    /** @test */
    public function it_can_make_column_action(): void
    {
        $this->artisan(MakeColumnAction::class, ['name' => 'Toggle']);
        self::assertFileExists(base_path('app/Tables/ColumnActions/Toggle.php'));
        $fileContent = File::get(base_path('app/Tables/ColumnActions/Toggle.php'));
        $this->assertSeeHtmlInOrder($fileContent, [
            'namespace App\Tables\ColumnActions;',
            'use Illuminate\Database\Eloquent\Model;',
            'use Livewire\Component;',
            'use Okipa\LaravelTable\Abstracts\AbstractColumnAction;',
            'class Toggle extends AbstractColumnAction',
            '{',
            'protected function class(): string',
            '{',
            '// The CSS class that will be applies to the column action link.',
            '}',
            'protected function icon(): string',
            '{',
            '// The icon that will be displayed in the column action link.',
            '}',
            'protected function title(): string',
            '{',
            '// The title that will be set to the column action link.',
            '}',
            'protected function shouldBeConfirmed(): bool',
            '{',
            '// Whether the column action should be confirmed before being executed.',
            '}',
            '/** @return mixed|void */',
            'public function action(Model $model, string $attribute, Component $livewire)',
            '{',
            '// The treatment that will be executed on click on the column action link.',
            '}',
            '}',
        ]);
    }
}
