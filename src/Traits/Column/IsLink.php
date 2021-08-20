<?php

namespace Okipa\LaravelTable\Traits\Column;

use Closure;
use Okipa\LaravelTable\Column;

trait IsLink
{
    protected string|null $url = null;

    protected Closure|null $urlClosure = null;

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

        return $this;
    }

    public function getUrl(): string|null
    {
        return $this->url;
    }

    public function getUrlClosure(): Closure|null
    {
        return $this->urlClosure;
    }
}
