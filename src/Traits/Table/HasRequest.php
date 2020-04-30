<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Http\Request as IlluminateRequest;
use Okipa\LaravelTable\Table;

trait HasRequest
{
    protected IlluminateRequest $request;

    public function initializeRequest(): void
    {
        $this->request = request();
    }

    public function request(IlluminateRequest $request): Table
    {
        $this->request = $request;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getRequest(): IlluminateRequest
    {
        return $this->request;
    }
}
