<?php

namespace Okipa\LaravelTable;

class Column
{
    protected string|null $title = null;

    public function __construct(protected string $key)
    {
        //
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function title(string $title): void
    {
        $this->title = $title;
    }

    public function getTitle(): string
    {
        return $this->title ?: __('validation.attributes.' . $this->key);
    }
}
