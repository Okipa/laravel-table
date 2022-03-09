<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use Okipa\LaravelTable\Console\Commands\MakeTable;
use Tests\TestCase;

class MakeTableTest extends TestCase
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
            'Column::make(\'Created at\')->format(new Date(\'d/m/Y H:i\'))->sortable();',
            $fileContent
        );
        self::assertStringContainsString(
            'Column::make(\'Updated at\')->format(new Date(\'d/m/Y H:i\'))->sortable()->sortByDefault(\'desc\');',
            $fileContent
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        File::deleteDirectory(base_path('app/Tables'));
    }
}
