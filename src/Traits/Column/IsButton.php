<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait IsButton
{
    protected array $buttonClasses = [];

    /**
     * Display the column as a button with the given classes.
     *
     * @param array $buttonClasses
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function button(array $buttonClasses = []): Column
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
