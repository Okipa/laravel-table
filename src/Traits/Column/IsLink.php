<?php

namespace Okipa\LaravelTable\Traits\Column;

use Closure;
use Okipa\LaravelTable\Column;

trait IsLink
{
    protected ?string $url = null;

    protected ?Closure $urlClosure = null;

    /**
     * @param string|Closure|null $url
     *
     * @return \Okipa\LaravelTable\Column
     */
    public function link($url = null): Column
    {
        if (is_string($url)) {
            $this->url = $url ?: true;
        } elseif ($url instanceof Closure) {
            $this->urlClosure = $url;
        } else {
            $this->url = '__VALUE__';
        }

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getUrlClosure(): ?Closure
    {
        return $this->urlClosure;
    }
}
