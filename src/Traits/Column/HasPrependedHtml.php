<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait HasPrependedHtml
{
    protected string|null $prependedHtml = null;

    protected bool $forcePrependedHtmlDisplay;

    public function prependHtml(string $html, bool $forcePrependedHtmlDisplay = false): Column
    {
        $this->prependedHtml = $html;
        $this->forcePrependedHtmlDisplay = $forcePrependedHtmlDisplay;

        return $this;
    }

    public function getPrependedHtml(): string|null
    {
        return $this->prependedHtml;
    }

    public function shouldForcePrependedHtmlDisplay(): bool
    {
        return $this->forcePrependedHtmlDisplay;
    }
}
