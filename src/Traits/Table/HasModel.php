<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Exceptions\TableModelNotFound;
use Okipa\LaravelTable\Table;

trait HasModel
{
    protected ?Model $model = null;

    /**
     * @param string $tableModelNamespaceClass
     *
     * @return \Okipa\LaravelTable\Table
     * @throws \Okipa\LaravelTable\Exceptions\TableBuildModeAlreadyDefined
     */
    public function model(string $tableModelNamespaceClass): Table
    {
        $this->checkNoBuildModeIsAlreadyDefined();
        $this->setBuildMode('model');
        $this->model = app($tableModelNamespaceClass);

        return $this;
    }

    /** @throws \Okipa\LaravelTable\Exceptions\TableModelNotFound */
    protected function checkModelIsDefined(): void
    {
        if ($this->buildModeId === $this->getBuildModeFromKey('model')['id'] && ! $this->getModel()) {
            throw new TableModelNotFound('The table is in "model" build mode but none has been defined.');
        }
    }

    public function getModel(): ?Model
    {
        return $this->model;
    }
}
