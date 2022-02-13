<?php

namespace Okipa\LaravelTable\Tests\Unit\Bootstrap5;

use ErrorException;
use Livewire\Livewire;
use Okipa\LaravelTable\Abstracts\AbstractTableConfiguration;
use Okipa\LaravelTable\Livewire\Table;
use Okipa\LaravelTable\Tests\TestCase;

class TableConfigurationTest extends TestCase
{
    /** @test */
    public function it_cant_generate_table_with_wrong_configuration(): void
    {
        $config = new class {
            //
        };
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage('The given ' . $this->config
            . ' table config should extend ' . AbstractTableConfiguration::class . '.');
        Livewire::test(Table::class, ['config' => $config::class])->call('init');
    }
}
