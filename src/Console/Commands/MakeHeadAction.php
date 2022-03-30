<?php

namespace Okipa\LaravelTable\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeHeadAction extends GeneratorCommand
{
    /** @var string */
    protected $type = 'AbstractHeadAction';

    /** @var string */
    protected $name = 'make:table:head:action';

    /** @var string */
    protected $description = 'Create a new table head action.';

    protected function getStub(): string
    {
        return __DIR__ . '/stubs/head.action.stub';
    }

    /** @param string $rootNamespace */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Tables\HeadActions';
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
