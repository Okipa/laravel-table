<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use Okipa\LaravelTable\Console\Commands\MakeFormatter;
use Okipa\LaravelTable\Console\Commands\MakeHeadAction;
use Okipa\LaravelTable\Console\Commands\MakeRowAction;
use Okipa\LaravelTable\Console\Commands\MakeTable;
use Tests\TestCase;

class MakeTest extends TestCase
{
    public function testMakeTable(): void
    {
        $this->artisan(MakeTable::class, ['name' => 'UsersTable']);
        self::assertFileExists(base_path('app/Tables/UsersTable.php'));
        $fileContent = File::get(base_path('app/Tables/UsersTable.php'));
        self::assertStringContainsString('namespace App\Tables;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\Table;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;', $fileContent);
        self::assertStringContainsString('class UsersTable extends AbstractTableConfiguration', $fileContent);
        self::assertStringContainsString('protected function table(): Table', $fileContent);
        self::assertStringContainsString('protected function columns(): array', $fileContent);
    }

    public function testMakeModelTable(): void
    {
        $this->artisan(MakeTable::class, ['name' => 'UsersTable', '--model' => 'App\Models\User']);
        self::assertFileExists(base_path('app/Tables/UsersTable.php'));
        $fileContent = File::get(base_path('app/Tables/UsersTable.php'));
        self::assertStringContainsString('namespace App\Tables;', $fileContent);
        self::assertStringContainsString('use App\Models\User;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\Table;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\Formatters\Date;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\RowActions\Edit;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\RowActions\Destroy;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;', $fileContent);
        self::assertStringContainsString('class UsersTable extends AbstractTableConfiguration', $fileContent);
        self::assertStringContainsString('protected function table(): Table', $fileContent);
        self::assertStringContainsString('Table::make()->model(User::class)', $fileContent);
        self::assertStringContainsString('->rowActions(fn(User $user) => [', $fileContent);
        self::assertStringContainsString('new Edit(route(\'user.edit\', $user)),', $fileContent);
        self::assertStringContainsString('new Destroy(),', $fileContent);
        self::assertStringContainsString('protected function columns(): array', $fileContent);
        self::assertStringContainsString('Column::make(\'Id\')->sortable();', $fileContent);
        self::assertStringContainsString(
            'Column::make(\'Created at\')->format(new Datetime(\'d/m/Y H:i\'))->sortable();',
            $fileContent
        );
        self::assertStringContainsString(
            'Column::make(\'Updated at\')->format(new Datetime(\'d/m/Y H:i\'))->sortable()->sortByDefault(\'desc\');',
            $fileContent
        );
    }

    public function testMakeFormatter(): void
    {
        $this->artisan(MakeFormatter::class, ['name' => 'Boolean']);
        self::assertFileExists(base_path('app/Tables/Formatters/Boolean.php'));
        $fileContent = File::get(base_path('app/Tables/Formatters/Boolean.php'));
        self::assertStringContainsString('namespace App\Tables\Formatters;', $fileContent);
        self::assertStringContainsString('use Illuminate\Database\Eloquent\Model;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\Abstracts\AbstractFormatter;', $fileContent);
        self::assertStringContainsString('class Boolean extends AbstractFormatter', $fileContent);
        self::assertStringContainsString('public function format(Model $model, string $key): string', $fileContent);
    }

    public function testMakeHeadAction(): void
    {
        $this->artisan(MakeHeadAction::class, ['name' => 'Configure']);
        self::assertFileExists(base_path('app/Tables/HeadActions/Configure.php'));
        $fileContent = File::get(base_path('app/Tables/HeadActions/Configure.php'));
        self::assertStringContainsString('namespace App\Tables\HeadActions;', $fileContent);
        self::assertStringContainsString('use Livewire\Component;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\Abstracts\AbstractHeadAction;', $fileContent);
        self::assertStringContainsString('class Configure extends AbstractHeadAction', $fileContent);
        self::assertStringContainsString('protected function class(): string', $fileContent);
        self::assertStringContainsString('protected function title(): string', $fileContent);
        self::assertStringContainsString('protected function icon(): string', $fileContent);
        self::assertStringContainsString('/** @return mixed|void */', $fileContent);
        self::assertStringContainsString('public function action(Component $livewire)', $fileContent);
    }

    public function testMakeRowAction(): void
    {
        $this->artisan(MakeRowAction::class, ['name' => 'Deactivate']);
        self::assertFileExists(base_path('app/Tables/RowActions/Deactivate.php'));
        $fileContent = File::get(base_path('app/Tables/RowActions/Deactivate.php'));
        self::assertStringContainsString('namespace App\Tables\RowActions;', $fileContent);
        self::assertStringContainsString('use Livewire\Component;', $fileContent);
        self::assertStringContainsString('use Illuminate\Database\Eloquent\Model;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\Abstracts\AbstractRowAction;', $fileContent);
        self::assertStringContainsString('class Deactivate extends AbstractRowAction', $fileContent);
        self::assertStringContainsString('protected function class(): string', $fileContent);
        self::assertStringContainsString('protected function key(): string', $fileContent);
        self::assertStringContainsString('protected function title(): string', $fileContent);
        self::assertStringContainsString('protected function icon(): string', $fileContent);
        self::assertStringContainsString('protected function shouldBeConfirmed(): bool', $fileContent);
        self::assertStringContainsString('/** @return mixed|void */', $fileContent);
        self::assertStringContainsString('public function action(Model $model, Component $livewire)', $fileContent);
    }

    protected function setUp(): void
    {
        parent::setUp();
        File::deleteDirectory(base_path('app/Tables'));
    }
}
