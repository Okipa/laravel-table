<?php

namespace Okipa\LaravelTable\Testing;

use Illuminate\Contracts\Auth\Authenticatable;
use Livewire\LivewireManager;

class Manager
{
    public function actingAs(Authenticatable $user, string|null $driver = null): self
    {
        app(LivewireManager::class)->actingAs($user, $driver);

        return $this;
    }

    public function test(string $config, array $configParams = []): Assert
    {
        return new Assert($config, $configParams);
    }
}
