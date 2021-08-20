<?php

namespace Okipa\LaravelTable\Traits\Table;

use Closure;
use Illuminate\Support\Collection;
use Okipa\LaravelTable\Table;

trait HasClasses
{
    protected array $containerClasses;

    protected array $tableClasses;

    protected array $trClasses;

    protected array $thClasses;

    protected array $tdClasses;

    protected Collection $rowsConditionalClasses;

    public function containerClasses(array $containerClasses): Table
    {
        $this->containerClasses = $containerClasses;

        return $this;
    }

    public function getContainerClasses(): array
    {
        return $this->containerClasses;
    }

    public function tableClasses(array $tableClasses): Table
    {
        $this->tableClasses = $tableClasses;

        return $this;
    }

    public function getTableClasses(): array
    {
        return $this->tableClasses;
    }

    public function trClasses(array $trClasses): Table
    {
        $this->trClasses = $trClasses;

        return $this;
    }

    public function getTrClasses(): array
    {
        return $this->trClasses;
    }

    public function thClasses(array $thClasses): Table
    {
        $this->thClasses = $thClasses;

        return $this;
    }

    public function getThClasses(): array
    {
        return $this->thClasses;
    }

    public function tdClasses(array $tdClasses): Table
    {
        $this->tdClasses = $tdClasses;

        return $this;
    }

    public function getTdClasses(): array
    {
        return $this->tdClasses;
    }

    public function rowsConditionalClasses(Closure $conditions, array|Closure $classes): Table
    {
        $this->rowsConditionalClasses->push(['conditions' => $conditions, 'classes' => $classes]);

        return $this;
    }

    protected function initializeTableDefaultClasses(): void
    {
        $this->containerClasses = config('laravel-table.classes.container');
        $this->tableClasses = config('laravel-table.classes.table');
        $this->trClasses = config('laravel-table.classes.tr');
        $this->thClasses = config('laravel-table.classes.th');
        $this->tdClasses = config('laravel-table.classes.td');
        $this->rowsConditionalClasses = new Collection();
    }

    protected function addClassesToRow(array &$row): void
    {
        foreach ($this->getRowsConditionalClasses() as $conditionalClass) {
            if ($conditionalClass['conditions']($row)) {
                $row['conditional_classes'] = array_merge(
                    $row['conditional_classes'] ?? [],
                    is_callable($conditionalClass['classes'])
                        ? $conditionalClass['classes']($row)
                        : $conditionalClass['classes']
                );
            }
        }
    }

    public function getRowsConditionalClasses(): Collection
    {
        return $this->rowsConditionalClasses;
    }
}
