<?php

namespace Okipa\LaravelTable\Traits\Table;

use Closure;
use Okipa\LaravelTable\Table;

trait HasDestroyConfirmation
{
    protected ?Closure $destroyConfirmationClosure = null;

    /**
     * Define html attributes on the destroy buttons to handle dynamic javascript destroy confirmations.
     * The closure let you manipulate the following attribute: \Illuminate\Database\Eloquent\Model $model.
     * Beware: the management of the destroy confirmation is on you, if you do not setup a javascript treatment to
     * ask a confirmation, the destroy action will be directly executed.
     *
     * @param \Closure $destroyConfirmationClosure
     *
     * @return \Okipa\LaravelTable\Table
     */
    public function destroyConfirmationHtmlAttributes(Closure $destroyConfirmationClosure): Table
    {
        $this->destroyConfirmationClosure = $destroyConfirmationClosure;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getDestroyConfirmationClosure(): ?Closure
    {
        return $this->destroyConfirmationClosure;
    }
}
