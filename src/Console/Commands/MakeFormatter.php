<?php

namespace Okipa\LaravelTable\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeFormatter extends GeneratorCommand
{
    /** @var string */
    protected $type = 'AbstractFormatter';

    /** @var string */
    protected $name = 'make:table:formatter';

    /** @var string */
    protected $description = 'Create a new table formatter.';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/formatter.stub';
    }

    /** @param string $rootNamespace */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Tables\Formatters';
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
