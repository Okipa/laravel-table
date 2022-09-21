<?php

namespace Okipa\LaravelTable\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputOption;

class MakeTable extends GeneratorCommand
{
    /** @var string */
    protected $type = 'Table';

    /** @var string */
    protected $name = 'make:table';

    /** @var string */
    protected $description = 'Create a new table configuration.';

    protected function getStub(): string
    {
        if ($this->option('model')) {
            $stub = '/stubs/table.model.stub';
        }
        $stub = $stub ?? '/stubs/table.stub';

        return __DIR__ . $stub;
    }

    /** @param string $rootNamespace */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Tables';
    }

    /** @throws \Illuminate\Contracts\Filesystem\FileNotFoundException */
    protected function buildClass($name): string
    {
        $replace = [];
        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    protected function buildModelReplacements(array $replace): array
    {
        $modelClass = $this->parseModel($this->option('model'));

        return array_merge($replace, [
            'DummyFullModelClass' => $modelClass,
            'DummyModelClass' => class_basename($modelClass),
            '$dummyModel' => '$' . Str::camel(class_basename($modelClass)),
            'dummyRoute' => Str::camel(class_basename($modelClass)),
        ]);
    }

    protected function parseModel(string $model): string
    {
        $result = preg_match('([^A-Za-z0-9_/\\\\])', $model);
        if ($result) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }
        $model = trim(str_replace('/', '\\', $model), '\\');
        $rootNamespace = $this->laravel->getNamespace();
        if (! Str::startsWith($model, $rootNamespace)) {
            $model = $rootNamespace . $model;
        }

        return $model;
    }

    protected function getOptions(): array
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Generate a table configuration for the given model.'],
        ];
    }
}
