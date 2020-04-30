<?php

namespace Okipa\LaravelTable\Traits\Table;

use Illuminate\Http\Request;
use Okipa\LaravelTable\Table;

trait HasRequest
{
    protected Request $request;

    public function initializeRequest(): void
    {
        $this->request = request();
    }

    public function request(Request $request): Table
    {
        $this->request = $request;

        /** @var \Okipa\LaravelTable\Table $this */
        return $this;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
