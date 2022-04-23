<?php

namespace Okipa\LaravelTable\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeBulkAction extends GeneratorCommand
{
    /** @var string */
    protected $type = 'AbstractBulkAction';

    /** @var string */
    protected $name = 'make:table:bulk:action';

    /** @var string */
    protected $description = 'Create a new table bulk action.';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/bulk.action.stub';
    }

    /** @param string $rootNamespace */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Tables\BulkActions';
    }

    /** @throws \Illuminate\Contracts\Filesystem\FileNotFoundException */
    protected function buildClass($name): string
    {
        $replace = [];

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }
}
