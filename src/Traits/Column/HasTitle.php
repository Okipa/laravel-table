<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait HasTitle
{
    protected ?string $title;

    public function title(?string $title): Column
    {
        $this->title = $title;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    protected function initializeTitle(): void
    {
        $this->title = $this->getDbField() ? (string) __('validation.attributes.' . $this->getDbField()) : null;
    }

    abstract public function getDbField(): ?string;
}
