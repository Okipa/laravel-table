<?php

namespace Okipa\LaravelTable\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeFilter extends GeneratorCommand
{
    /** @var string */
    protected $type = 'AbstractFilter';

    /** @var string */
    protected $name = 'make:table:filter';

    /** @var string */
    protected $description = 'Create a new table filter.';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/filter.stub';
    }

    /** @param string $rootNamespace */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Tables\Filters';
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
