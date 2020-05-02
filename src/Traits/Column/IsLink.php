<?php

namespace Okipa\LaravelTable\Traits\Column;

use Closure;
use Okipa\LaravelTable\Column;

trait IsLink
{
    protected ?string $url = null;

    protected ?Closure $urlClosure = null;

    /**
     * Wrap the column value into a <a></a> component.
     * You can declare the link as a string or as a closure which will let you manipulate the following attributes:
     * \Illuminate\Database\Eloquent\Model $model, \Okipa\LaravelTable\Column $column.
     * If no url is declared, the url will be generated using the column value.
     *
     * @param string|Closure $url
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
