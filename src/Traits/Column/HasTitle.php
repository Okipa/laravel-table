<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait HasTitle
{
    protected string|null $title;

    public function title(string|null $title): Column
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string|null
    {
        return $this->title;
    }

    protected function initializeTitle(): void
    {
        $this->title = $this->getDataSourceField()
            ? (string) __('validation.attributes.' . $this->getDataSourceField())
            : null;
    }
}
