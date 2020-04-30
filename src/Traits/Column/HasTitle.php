<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait HasTitle
{
    protected ?string $title;

    abstract public function getDbField(): ?string;

    protected function initializeTitle(): void
    {
        $this->title = $this->getDbField() ? (string) __('validation.attributes.' . $this->getDbField()) : null;
    }

    /**
     * Set a custom column title and override the default `__('validation.attributes.[$database_column])` one.
     *
     * @param string|null $title
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function title(string $title = null): Column
    {
        $this->title = $title;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }
}
