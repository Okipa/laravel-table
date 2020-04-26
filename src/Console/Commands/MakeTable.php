<?php

namespace Okipa\LaravelTable\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeTable extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new table configuration';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->option('model')) {
            $stub = '/stubs/export.model.stub';
        }
        $stub = $stub ?? '/stubs/export.plain.stub';

        return __DIR__ . $stub;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Tables';
    }

    /**
     * Build the class with the given name.
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = [];
        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate an export for the given model.'],
            ['query', '', InputOption::VALUE_NONE, 'Generate an export for a query.'],
        ];
    }
}
