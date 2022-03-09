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
        $this->artisan(MakeFormatter::class, ['name' => 'BooleanFormatter']);
        self::assertFileExists(base_path('app/Tables/Formatters/BooleanFormatter.php'));
        $fileContent = File::get(base_path('app/Tables/Formatters/BooleanFormatter.php'));
        self::assertStringContainsString('namespace App\Tables\Formatters;', $fileContent);
        self::assertStringContainsString('use Illuminate\Database\Eloquent\Model;', $fileContent);
        self::assertStringContainsString('use Okipa\LaravelTable\Abstracts\AbstractFormatter;', $fileContent);
        self::assertStringContainsString('class BooleanFormatter', $fileContent);
        self::assertStringContainsString('public function format(Model $model, string $key): string', $fileContent);
    }

    protected function setUp(): void
    {
        parent::setUp();
        File::deleteDirectory(base_path('app/Tables/Formatters'));
    }
}
