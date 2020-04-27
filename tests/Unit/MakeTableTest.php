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
        $this->assertStringContainsString('namespace App\Tables;', $fileContent);
        $this->assertStringContainsString('use Okipa\LaravelTable\Table;', $fileContent);
        $this->assertStringContainsString('class UsersTable', $fileContent);
        $this->assertStringContainsString('protected function table(): Table', $fileContent);
        $this->assertStringContainsString('protected function columns(Table $table): void', $fileContent);
        $this->assertStringContainsString('protected function resultLines(Table $table): void', $fileContent);
    }

    public function testMakeModelTable(): void
    {
        $this->artisan(MakeTable::class, ['name' => 'UsersTable', '--model' => 'App\User']);
        $this->assertFileExists(base_path('app/Tables/UsersTable.php'));
        $fileContent = File::get(base_path('app/Tables/UsersTable.php'));
        $this->assertStringContainsString('namespace App\Tables;', $fileContent);
        $this->assertStringContainsString('use Okipa\LaravelTable\Table;', $fileContent);
        $this->assertStringContainsString('use App\User;', $fileContent);
        $this->assertStringContainsString('class UsersTable', $fileContent);
        $this->assertStringContainsString('protected function table(): Table', $fileContent);
        $this->assertStringContainsString('(new Table)->model(User::class)', $fileContent);
        $this->assertStringContainsString('function (User $user)', $fileContent);
        $this->assertStringContainsString('[\'name\' => \'users.index\']', $fileContent);
        $this->assertStringContainsString('[\'name\' => \'user.create\']', $fileContent);
        $this->assertStringContainsString('[\'name\' => \'user.edit\']', $fileContent);
        $this->assertStringContainsString('[\'name\' => \'user.destroy\']', $fileContent);
        $this->assertStringContainsString('$user->database_attribute', $fileContent);
        $this->assertStringContainsString('protected function columns(Table $table): void', $fileContent);
        $this->assertStringContainsString(
            '$table->column(\'database_attribute\')->sortable()->searchable();',
            $fileContent
        );
        $this->assertStringContainsString('protected function resultLines(Table $table): void', $fileContent);
    }

    protected function setUp(): void
    {
        parent::setUp();
        File::deleteDirectory(base_path('app/Tables'));
    }
}
