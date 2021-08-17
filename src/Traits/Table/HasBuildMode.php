<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Support\Arr;
use Okipa\LaravelTable\Exceptions\TableBuildModeAlreadyDefined;

trait HasBuildMode
{
    protected array $buildModes = [['id' => 1, 'key' => 'model'], ['id' => 2, 'key' => 'collection']];

    protected int|null $buildModeId = null;

    /** @throws \Okipa\LaravelTable\Exceptions\TableBuildModeAlreadyDefined */
    protected function checkNoBuildModeIsAlreadyDefined(): void
    {
        if ($this->buildModeId) {
            throw new TableBuildModeAlreadyDefined('Table mode has already been defined to "'
                . $this->getBuildModeFromId($this->buildModeId)['key'] . '".');
        }
    }

    protected function getBuildModeFromId(int $buildModeId): array
    {
        return Arr::first($this->buildModes, fn(array $buildMode) => $buildMode['id'] === $buildModeId);
    }

    protected function setBuildMode(string $buildModeKey): void
    {
        $this->buildModeId = $this->getBuildModeFromKey($buildModeKey)['id'];
    }

    protected function getBuildModeFromKey(string $buildModeKey): array
    {
        return Arr::first($this->buildModes, fn(array $buildMode) => $buildMode['key'] === $buildModeKey);
    }
}
