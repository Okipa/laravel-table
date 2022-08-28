<?php

namespace Okipa\LaravelTable\Exceptions;

use Exception;

class UnrecognizedActionType extends Exception
{
    public function __construct(string $actionType)
    {
        parent::__construct('Action type ' . $actionType . ' has not been recognized.');
    }
}
