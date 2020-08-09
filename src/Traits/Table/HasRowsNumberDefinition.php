<?php

namespace Okipa\LaravelTable\Traits\Table;

use Okipa\LaravelTable\Table;

trait HasRowsNumberDefinition
{
    protected string $rowsNumberField = 'rows';

    protected ?int $rowsNumberValue = null;

    protected bool $rowsNumberDefinitionActivated;

    /**
     * Override the config default number of rows displayed on the table.
     * The default number of displayed rows is defined in the config('laravel-table.behavior.rows_number') config value.
     *
     * @param int|null $rowsNumber
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function rowsNumber(?int $rowsNumber): Table
    {
        $this->rowsNumberValue = $rowsNumber;

        /** @var \Okipa\LaravelTable\Table $this */
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

    public function getRowsNumberValue(): ?int
    {
        return $this->rowsNumberValue;
    }

    /**
     * Override the default rows number selection activation status.
     * Calling this method displays a rows number input that enable the user to choose how much rows to show.
     * The default rows number selection activation status is defined in the
     * config('laravel-table.behavior.activate_rows_number_selection') config value.
     *
     * @param bool $activate
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function activateRowsNumberDefinition(bool $activate = true): Table
    {
        $this->rowsNumberDefinitionActivated = $activate;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getRowsNumberSelectionActivation(): bool
    {
        return $this->rowsNumberDefinitionActivated;
    }

    protected function initializeRowsNumberDefinition()
    {
        $this->rowsNumberValue = config('laravel-table.behavior.rows_number');
        $this->rowsNumberDefinitionActivated = (bool) config('laravel-table.behavior.activate_rows_number_selection');
    }
}
