<?php

namespace Okipa\LaravelTable\Traits\Table;

use Okipa\LaravelTable\Table;

trait HasRowsNumberDefinition
{
    protected string $rowsNumberField = 'rows';

    protected ?int $rowsNumberValue = null;

    protected bool $rowsNumberDefinitionActivated;

    public function rowsNumber(int|null $rowsNumber): Table
    {
        $this->rowsNumberValue = $rowsNumber;

        return $this;
    }

    protected function reDefineRowsNumberField(string $rowsNumberField): void
    {
        $this->rowsNumberField = $rowsNumberField;
    }

    public function getRowsNumberField(): string
    {
        return $this->rowsNumberField;
    }

    public function getRowsNumberValue(): int|null
    {
        return $this->rowsNumberValue;
    }

    public function activateRowsNumberDefinition(bool $activate = true): Table
    {
        $this->rowsNumberDefinitionActivated = $activate;

        return $this;
    }

    public function getRowsNumberDefinitionActivation(): bool
    {
        return $this->rowsNumberDefinitionActivated;
    }

    protected function initializeRowsNumberDefinition(): void
    {
        $this->rowsNumberValue = config('laravel-table.behavior.rows_number');
        $this->rowsNumberDefinitionActivated = (bool) config('laravel-table.behavior.activate_rows_number_definition');
    }
}
