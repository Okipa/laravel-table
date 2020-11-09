<?php

namespace Okipa\LaravelTable\Tests\Unit;

use File;
use Okipa\LaravelTable\Console\Commands\MakeTable;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class MakeTableTest extends LaravelTableTestCase
{
    public function testMakeTable(): void
    {
        $this->artisan(MakeTable::class, ['name' => 'UsersTable']);
        $this->assertFileExists(base_path('app/Tables/UsersTable.php'));
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
        $this->artisan(MakeTable::class, ['name' => 'UsersTable', '--model' => 'App\User']);
        $this->assertFileExists(base_path('app/Tables/UsersTable.php'));
        $fileContent = File::get(base_path('app/Tables/UsersTable.php'));
        self::assertStringContainsString('namespace App\Tables;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\Table;', $fileContent);
        self::assertStringContainsString('use App\User;', $fileContent);
        self::assertStringContainsString('class UsersTable', $fileContent);
        self::assertStringContainsString('protected function table(): Table', $fileContent);
        self::assertStringContainsString('(new Table())->model(User::class)', $fileContent);
        self::assertStringContainsString('fn(User $user)', $fileContent);
        self::assertStringContainsString('[\'name\' => \'users.index\']', $fileContent);
        self::assertStringContainsString('[\'name\' => \'user.create\']', $fileContent);
        self::assertStringContainsString('[\'name\' => \'user.edit\']', $fileContent);
        self::assertStringContainsString('[\'name\' => \'user.destroy\']', $fileContent);
        self::assertStringContainsString('$user->database_attribute', $fileContent);
        self::assertStringContainsString('protected function columns(Table $table): void', $fileContent);
        self::assertStringContainsString(
            '$table->column(\'database_attribute\')->sortable()->searchable();',
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
