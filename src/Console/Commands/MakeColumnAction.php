<?php

namespace Okipa\LaravelTable\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeColumnAction extends GeneratorCommand
{
    /** @var string */
    protected $type = 'AbstractColumnAction';

    /** @var string */
    protected $name = 'make:table:column:action';

    /** @var string */
    protected $description = 'Create a new table column action.';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/column.action.stub';
    }

    /** @param string $rootNamespace */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Tables\ColumnActions';
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
