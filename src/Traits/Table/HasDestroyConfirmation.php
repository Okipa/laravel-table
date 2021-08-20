<?php

namespace Okipa\LaravelTable\Traits\Table;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Table;

trait HasDestroyConfirmation
{
    protected Closure|null $destroyConfirmationClosure = null;

    public function destroyConfirmationHtmlAttributes(Closure $destroyConfirmationClosure): Table
    {
        $this->destroyConfirmationClosure = $destroyConfirmationClosure;

        return $this;
    }

    public function defineRowConfirmationHtmlAttributes(array &$row): void
    {
        if ($this->getDestroyConfirmationClosure()) {
            $row['destroy_confirmation_attributes'] = ($this->getDestroyConfirmationClosure())($row);
        }
    }

    public function getDestroyConfirmationClosure(): Closure|null
    {
        return $this->destroyConfirmationClosure;
    }
}
