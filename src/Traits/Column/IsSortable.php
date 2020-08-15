<?php

namespace Okipa\LaravelTable\Traits\Column;

use ErrorException;
use InvalidArgumentException;
use Okipa\LaravelTable\Column;

trait IsSortable
{
    protected bool $isSortable = false;

    /**
     * @param bool $sortByDefault
     * @param string $sortDirection
     *
     * @return \Okipa\LaravelTable\Column
     * @throws \ErrorException
     */
    public function sortable(bool $sortByDefault = false, string $sortDirection = 'asc'): Column
    {
        $this->isSortable = true;
        /** @var \Okipa\LaravelTable\Column $this */
        $this->getTable()->addToSortableColumns($this);
        if ($sortByDefault) {
            $this->sortByDefault($sortDirection);
        }

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    /**
     * @param string $sortDirection
     *
     * @throws \ErrorException
     */
    protected function sortByDefault(string $sortDirection = 'asc'): void
    {
        /** @var \Okipa\LaravelTable\Column $this */
        if ($this->getTable()->getSortByValue() || $this->getTable()->getSortDirValue()) {
            /** @var \Okipa\LaravelTable\Column $this */
            $errorMessage = 'The table is already sorted by the « ' . $this->getTable()->getSortByValue()
                . ' » database column. You only can sort a table column by default once.';
            throw new ErrorException($errorMessage);
        }
        if (! $this->getDbField()) {
            return;
        }
        /** @var \Okipa\LaravelTable\Column $this */
        $this->getTable()->defineSortByValue($this->getDbField());
        $acceptedDirections = ['asc', 'desc'];
        $errorMessage = 'Invalid « $sortDirection » second argument for the « sortable » method. Has to be « asc » or '
            . '« desc ». « ' . $sortDirection . ' » given.';
        if (! in_array($sortDirection, $acceptedDirections)) {
            throw new InvalidArgumentException($errorMessage);
        }
        /** @var \Okipa\LaravelTable\Column $this */
        $this->getTable()->definedSortDirValue($sortDirection);
    }

    public function getIsSortable(): bool
    {
        return $this->isSortable;
    }
}
