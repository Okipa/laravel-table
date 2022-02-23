<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use Okipa\LaravelTable\Console\Commands\MakeFormatter;
use Okipa\LaravelTable\Console\Commands\MakeTable;
use Tests\TestCase;

class MakeFormatterTest extends TestCase
{
    public function testMakeTable(): void
    {
        $this->artisan(MakeFormatter::class, ['name' => 'ActiveFormatter']);
        self::assertFileExists(base_path('app/Tables/Formatters/ActiveFormatter.php'));
        $fileContent = File::get(base_path('app/Tables/Formatters/ActiveFormatter.php'));
        self::assertStringContainsString('namespace App\Tables\Formatters;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\Abstracts\AbstractFormatter;', $fileContent);
        self::assertStringContainsString('class ActiveFormatter', $fileContent);
        self::assertStringContainsString('protected function format(Model $model): mixed', $fileContent);
    }

    protected function setUp(): void
    {
        parent::setUp();
        File::deleteDirectory(base_path('app/Tables/Formatters'));
    }
}
