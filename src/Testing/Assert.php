<?php

namespace Okipa\LaravelTable\Testing;

use Illuminate\Support\Arr;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;
use Okipa\LaravelTable\Livewire\Table;
use PHPUnit\Framework\Assert as PHPUnit;

class Assert
{
    protected TestableLivewire $component;

    public function __construct(protected string $config, array $configParams)
    {
        $this->component = Livewire::test(Table::class, [
            'config' => $config,
            'configParams' => $configParams,
        ])->call('init')->set('selectAll', true);
    }

    public function usesModel(string $assertedModelClass): self
    {
        $config = app($this->config)->setup();
        $configuredModelClass = $config->getModel()->getMorphClass();
        PHPUnit::assertEquals($configuredModelClass, $assertedModelClass);

        return $this;
    }

    public function bulkActionAllowsModels(string $bulkActionClass, array $assertedAllowedModelPrimaries): self
    {
        $configuredBulkAction = Arr::first(
            $this->component->tableBulkActionsArray,
            static fn (array $bulkActionArray) => $bulkActionArray['bulkActionClass'] === $bulkActionClass
        );
        PHPUnit::assertEquals($assertedAllowedModelPrimaries, $configuredBulkAction['allowedModelKeys']);

        return $this;
    }

    public function bulkActionDisallowsModels(string $bulkActionClass, array $assertedDisallowedModelPrimaries): self
    {
        $configuredBulkAction = Arr::first(
            $this->component->tableBulkActionsArray,
            static fn (array $bulkActionArray) => $bulkActionArray['bulkActionClass'] === $bulkActionClass
        );
        PHPUnit::assertEquals($assertedDisallowedModelPrimaries, $configuredBulkAction['disallowedModelKeys']);

        return $this;
    }

    public function rowActionAllowsModels(string $rowActionClass, array $assertedAllowedModelPrimaries): self
    {
        $configuredRowActions = Arr::where(
            $this->component->tableRowActionsArray,
            static fn (array $rowActionArray) => $rowActionArray['rowActionClass'] === $rowActionClass
        );
        $configuredAllowedModelPrimaries = Arr::pluck($configuredRowActions, 'modelKey');
        PHPUnit::assertNotEmpty($configuredAllowedModelPrimaries);
        $assertedAllowedModelPrimaries = array_map('strval', $assertedAllowedModelPrimaries);
        foreach ($configuredAllowedModelPrimaries as $configuredAllowedModelPrimary) {
            PHPUnit::assertContains($configuredAllowedModelPrimary, $assertedAllowedModelPrimaries);
        }

        return $this;
    }

    public function rowActionDisallowsModels(string $rowActionClass, array $assertedDisallowedModelPrimaries): self
    {
        $configuredRowActions = Arr::where(
            $this->component->tableRowActionsArray,
            static fn (array $rowActionArray) => $rowActionArray['rowActionClass'] === $rowActionClass
        );
        $configuredAllowedModelPrimaries = Arr::pluck($configuredRowActions, 'modelKey');
        PHPUnit::assertEmpty(array_intersect($assertedDisallowedModelPrimaries, $configuredAllowedModelPrimaries));

        return $this;
    }

    public function columnActionAllowsModels(string $columnActionClass, array $assertedAllowedModelPrimaries): self
    {
        $configuredColumnActions = Arr::where(
            $this->component->tableColumnActionsArray,
            static fn (array $columnActionArray) => $columnActionArray['columnActionClass'] === $columnActionClass
        );
        $configuredAllowedModelPrimaries = Arr::pluck($configuredColumnActions, 'modelKey');
        PHPUnit::assertNotEmpty($configuredAllowedModelPrimaries);
        $assertedAllowedModelPrimaries = array_map('strval', $assertedAllowedModelPrimaries);
        foreach ($configuredAllowedModelPrimaries as $configuredAllowedModelPrimary) {
            PHPUnit::assertContains($configuredAllowedModelPrimary, $assertedAllowedModelPrimaries);
        }

        return $this;
    }

    public function columnActionDisallowsModels(
        string $columnActionClass,
        array $assertedDisallowedModelPrimaries
    ): self {
        $configuredColumnActions = Arr::where(
            $this->component->tableColumnActionsArray,
            static fn (array $columnActionArray) => $columnActionArray['columnActionClass'] === $columnActionClass
        );
        $configuredAllowedModelPrimaries = Arr::pluck($configuredColumnActions, 'modelKey');
        PHPUnit::assertEmpty(array_intersect($assertedDisallowedModelPrimaries, $configuredAllowedModelPrimaries));

        return $this;
    }
}
