<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Exceptions\TableModeAlreadyDefined;
use Okipa\LaravelTable\Exceptions\TableModelNotFound;
use Okipa\LaravelTable\Table;

trait HasModel
{
    protected ?Model $model = null;

    /**
     * @param string $tableModelNamespaceClass
     *
     * @return \Okipa\LaravelTable\Table
     * @throws \Okipa\LaravelTable\Exceptions\TableModeAlreadyDefined
     */
    public function model(string $tableModelNamespaceClass): Table
    {
        if ($this->mode) {
            throw new TableModeAlreadyDefined('Table mode has already been set to "' . $this->mode . '".');
        }
        $this->mode = self::MODEL_MODE;
        $this->model = app($tableModelNamespaceClass);

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\TableModelNotFound */
    protected function checkModelIsDefined(): void
    {
        if (! $this->getModel()) {
            throw new TableModelNotFound('The table is in model mode but none has been found.');
        }
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }
}
