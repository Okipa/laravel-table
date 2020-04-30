<?php

namespace Okipa\LaravelTable\Traits\Column;

use ErrorException;
use InvalidArgumentException;
use Okipa\LaravelTable\Column;

trait IsSortable
{
    protected bool $isSortable = false;

    /**
     * Make the column sortable.
     * You also can choose to set the column sorted by default.
     * If no column is sorted by default, the first one will be automatically sorted.
     *
     * @param bool $sortByDefault
     * @param string $sortDirection
     *
     * @return \Okipa\LaravelTable\Column
     * @throws \ErrorException
     */
    public function sortable(bool $sortByDefault = false, string $sortDirection = 'asc'): Column
    {
        /** @var \Okipa\LaravelTable\Column $this */
        $this->getTable()->addToSortableColumns($this);
        $this->isSortable = true;
        if ($sortByDefault) {
            $this->sortByDefault($sortDirection);
        }

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getIsSortable(): bool
    {
        return $this->isSortable;
    }

    /**
     * Sort the column by default.
     *
     * @param string $sortDirection
     *
     * @throws \ErrorException
     */
    protected function sortByDefault(string $sortDirection = 'asc')
    {
        /** @var \Okipa\LaravelTable\Column $this */
        if ($this->getTable()->getSortByValue() || $this->getTable()->getSortDirValue()) {
            /** @var \Okipa\LaravelTable\Column $this */
            $errorMessage = 'The table is already sorted by the « ' . $this->getTable()->getSortByValue()
                . ' » database column. You only can sort a table column by default once.';
            throw new ErrorException($errorMessage);
        }
        /** @var \Okipa\LaravelTable\Column $this */
        $this->getTable()->defineSortByValue($this->getDbField());
        $acceptedDirections = ['asc', 'desc'];
        $errorMessage = 'Invalid « $sortDirection » second argument for « sortable() » method. Has to be « asc » or '
            . '« desc ». « ' . $sortDirection . ' » given.';
        if (! in_array($sortDirection, $acceptedDirections)) {
            throw new InvalidArgumentException($errorMessage);
        }
        /** @var \Okipa\LaravelTable\Column $this */
        $this->getTable()->definedSortDirValue($sortDirection);
    }
}
