<?php

namespace Okipa\LaravelTable\Traits\Table;

use ErrorException;
use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Table;

trait HasModel
{
    protected ?Model $model = null;

    public function model(string $tableModelClass): Table
    {
        $this->model = app($tableModelClass);

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * Check column model is defined.
     *
     * @return void
     * @throws \ErrorException
     */
    protected function checkModelIsDefined(): void
    {
        if (! $this->model instanceof Model) {
            $errorMessage = 'The table model has not been defined or is not an instance of « '
                . Model::class . ' ».';
            throw new ErrorException($errorMessage);
        }
    }
}
