<?php

namespace Okipa\LaravelTable\Tests\Unit;

use File;
use Okipa\LaravelTable\Console\Commands\MakeTable;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class MakeTableTest extends LaravelTableTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        File::deleteDirectory(base_path('app/Tables'));
    }

    public function testMakeTable()
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

    public function testMakeModelTable()
    {
        $this->artisan(MakeTable::class, ['name' => 'UsersTable', '--model' => 'App\Users']);
        $this->assertFileExists(base_path('app/Tables/UsersTable.php'));
        $fileContent = File::get(base_path('app/Tables/UsersTable.php'));
        $this->assertStringContainsString('namespace App\Tables;', $fileContent);
        $this->assertStringContainsString('use Okipa\LaravelTable\Table;', $fileContent);
        $this->assertStringContainsString('use App\Users;', $fileContent);
        $this->assertStringContainsString('class UsersTable', $fileContent);
        $this->assertStringContainsString('protected function table(): Table', $fileContent);
        $this->assertStringContainsString('Table::model(Users::class)', $fileContent);
        $this->assertStringContainsString('function (Users $users)', $fileContent);
        $this->assertStringContainsString('$users->...', $fileContent);
        $this->assertStringContainsString('protected function columns(Table $table): void', $fileContent);
        $this->assertStringContainsString('protected function resultLines(Table $table): void', $fileContent);
    }
}
