<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait PrependsHtml
{
    protected ?string $prependedHtml = null;

    protected bool $forcePrependedHtmlDisplay;

    public function prependHtml(string $prependedHtml, bool $forcePrependedHtmlDisplay = false): Column
    {
        $this->prependedHtml = $prependedHtml;
        $this->forcePrependedHtmlDisplay = $forcePrependedHtmlDisplay;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getPrependedHtml(): ?string
    {
        return $this->prependedHtml;
    }

    public function shouldForcePrependedHtmlDisplay(): bool
    {
        return $this->forcePrependedHtmlDisplay;
    }
}
