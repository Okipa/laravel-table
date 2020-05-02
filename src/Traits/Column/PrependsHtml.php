<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait PrependsHtml
{
    protected ?string $prependedHtml = null;

    protected bool $forcePrependedHtmlDisplay;

    /**
     * Prepend HTML to the displayed value.
     * Set the second param as true if you want the prepended HTML to be displayed even if the column has no value.
     *
     * @param string $prependedHtml
     * @param bool $forcePrependedHtmlDisplay
     *
     * @return \Okipa\LaravelTable\Column
     */
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
