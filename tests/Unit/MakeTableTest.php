<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Illuminate\Support\Facades\File;
use Okipa\LaravelTable\Console\Commands\MakeTable;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class MakeTableTest extends LaravelTableTestCase
{
    public function testMakeTable(): void
    {
        $this->artisan(MakeTable::class, ['name' => 'UsersTable']);
        self::assertFileExists(base_path('app/Tables/UsersTable.php'));
        $fileContent = File::get(base_path('app/Tables/UsersTable.php'));
        self::assertStringContainsString('namespace App\Tables;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\Table;', $fileContent);
        self::assertStringContainsString('class UsersTable', $fileContent);
        self::assertStringContainsString('protected function table(): Table', $fileContent);
        self::assertStringContainsString('protected function columns(Table $table): void', $fileContent);
        self::assertStringContainsString('protected function resultLines(Table $table): void', $fileContent);
    }

    public function testMakeModelTable(): void
    {
        $this->artisan(MakeTable::class, ['name' => 'UsersTable', '--model' => 'App\Models\User']);
        self::assertFileExists(base_path('app/Tables/UsersTable.php'));
        $fileContent = File::get(base_path('app/Tables/UsersTable.php'));
        self::assertStringContainsString('namespace App\Tables;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\Table;', $fileContent);
        self::assertStringContainsString('use App\Models\User;', $fileContent);
        self::assertStringContainsString('class UsersTable', $fileContent);
        self::assertStringContainsString('protected function table(): Table', $fileContent);
        self::assertStringContainsString('(new Table())->model(User::class)', $fileContent);
        self::assertStringContainsString('fn(User $user)', $fileContent);
        self::assertStringContainsString('[\'name\' => \'users.index\']', $fileContent);
        self::assertStringContainsString('[\'name\' => \'user.create\']', $fileContent);
        self::assertStringContainsString('[\'name\' => \'user.edit\']', $fileContent);
        self::assertStringContainsString('[\'name\' => \'user.destroy\']', $fileContent);
        self::assertStringContainsString('\'data-confirm\' => __(\'Are you sure you want to delete the entry :entry?\', [', $fileContent);
        self::assertStringContainsString('\'entry\' => $user->database_attribute', $fileContent);
        self::assertStringContainsString('protected function columns(Table $table): void', $fileContent);
        self::assertStringContainsString(
            '$table->column(\'id\')->sortable();',
            $fileContent
        );
        self::assertStringContainsString(
            '$table->column(\'created_at\')->dateTimeFormat(\'d/m/Y H:i\')->sortable();',
            $fileContent
        );
        self::assertStringContainsString(
            '$table->column(\'updated_at\')->dateTimeFormat(\'d/m/Y H:i\')->sortable(true, \'desc\');',
            $fileContent
        );
        self::assertStringContainsString('protected function resultLines(Table $table): void', $fileContent);
    }

    protected function setUp(): void
    {
        parent::setUp();
        File::deleteDirectory(base_path('app/Tables'));
    }
}
