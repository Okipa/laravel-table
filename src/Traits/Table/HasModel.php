<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Table;

trait HasModel
{
    protected Model|null $model = null;

    /**
     * @param string $tableModelNamespaceClass
     *
     * @return \Okipa\LaravelTable\Table
     * @throws \Okipa\LaravelTable\Exceptions\TableDataSourceAlreadyDefined
     */
    public function fromModel(string $tableModelNamespaceClass): Table
    {
        $this->checkDataSourceHasNotAlreadyBeenDefined();
        $this->setDataSource('model');
        $this->model = app($tableModelNamespaceClass);

        return $this;
    }

    public function getModel(): Model|null
    {
        return $this->model;
    }
}
