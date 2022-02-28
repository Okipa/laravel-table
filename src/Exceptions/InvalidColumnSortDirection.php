<?php

namespace Okipa\LaravelTable\Exceptions;

use Exception;

class InvalidColumnSortDirection extends Exception
{
    public function __construct(string $sortDir)
    {
        parent::__construct('Column sort direction should be either "asc" or "desc". "'
            . $sortDir . '" given.');
    }
}
