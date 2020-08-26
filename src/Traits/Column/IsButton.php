<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait IsButton
{
    protected array $buttonClasses = [];

    public function button(array $buttonClasses): Column
    {
        $this->buttonClasses = $buttonClasses;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getButtonClasses(): array
    {
        return $this->buttonClasses;
    }
}
