<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Support\Arr;
use Okipa\LaravelTable\Exceptions\TableDataSourceAlreadyDefined;
use Okipa\LaravelTable\Exceptions\TableDataSourceNotDefined;

trait HasDataSource
{
    protected array $dataSourceConfig = [['id' => 1, 'key' => 'model'], ['id' => 2, 'key' => 'collection']];

    protected int|null $currentDataSourceId = null;

    /** @throws \Okipa\LaravelTable\Exceptions\TableDataSourceAlreadyDefined */
    protected function checkDataSourceHasNotAlreadyBeenDefined(): void
    {
        if ($this->currentDataSourceId) {
            throw new TableDataSourceAlreadyDefined('Table data source has already been defined to "'
                . $this->getDataSourceConfigFromId($this->currentDataSourceId)['key'] . '".');
        }
    }

    protected function getDataSourceConfigFromId(int $currentDataSourceId): array
    {
        return Arr::first($this->dataSourceConfig, fn(array $buildMode) => $buildMode['id'] === $currentDataSourceId);
    }

    protected function setDataSource(string $buildModeKey): void
    {
        $this->currentDataSourceId = $this->getDataSourceConfigFromKey($buildModeKey)['id'];
    }

    protected function getDataSourceConfigFromKey(string $buildModeKey): array
    {
        return Arr::first($this->dataSourceConfig, fn(array $buildMode) => $buildMode['key'] === $buildModeKey);
    }

    /** @throws \Okipa\LaravelTable\Exceptions\TableDataSourceNotDefined */
    protected function checkDataSourceIsDefined(): void
    {
        if (! $this->hasDataSource('model') && ! $this->hasDataSource('collection')) {
            throw new TableDataSourceNotDefined('The table has no defined build source. '
                . 'Please defined a build source by calling the `model()` or `collection()` method.');
        }
    }

    public function hasDataSource(string $buildModeKey): bool
    {
        return $this->currentDataSourceId === $this->getDataSourceConfigFromKey($buildModeKey)['id'];
    }
}
