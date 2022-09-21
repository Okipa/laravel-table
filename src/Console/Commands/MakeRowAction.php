<?php

namespace Okipa\LaravelTable\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeRowAction extends GeneratorCommand
{
    /** @var string */
    protected $type = 'AbstractRowAction';

    /** @var string */
    protected $name = 'make:table:row:action';

    /** @var string */
    protected $description = 'Create a new table row action.';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/row.action.stub';
    }

    /** @param string $rootNamespace */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Tables\RowActions';
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
