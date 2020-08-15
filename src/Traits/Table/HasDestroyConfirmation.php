<?php

namespace Okipa\LaravelTable\Traits\Table;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Table;

trait HasDestroyConfirmation
{
    protected ?Closure $destroyConfirmationClosure = null;

    public function destroyConfirmationHtmlAttributes(Closure $destroyConfirmationClosure): Table
    {
        $this->destroyConfirmationClosure = $destroyConfirmationClosure;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function defineRowConfirmationHtmlAttributes(Model $model): void
    {
        if ($this->getDestroyConfirmationClosure()) {
            $model->destroy_confirmation_attributes = ($this->getDestroyConfirmationClosure())($model);
        }
    }

    public function getDestroyConfirmationClosure(): ?Closure
    {
        return $this->destroyConfirmationClosure;
    }
}
