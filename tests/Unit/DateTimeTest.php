<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Carbon\Carbon;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class DateTimeTest extends LaravelTableTestCase
{
    public function testDateFormatAttribute(): void
    {
        $table = (new Table())->model(User::class);
        $table->column('name')->dateTimeFormat('d/m/Y H:i:s', 'Europe/Paris');
        self::assertEquals('d/m/Y H:i:s', $table->getColumns()->first()->getDateTimeFormat());
        self::assertEquals('Europe/Paris', $table->getColumns()->first()->getTimezone());
    }

    public function testDateFormatHtml(): void
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index', 'params' => []]]);
        $table->column('name');
        $table->column('updated_at')->dateTimeFormat('d/m/Y', 'Europe/Paris');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString($user->updated_at->timezone('Europe/Paris')->format('d/m/Y'), $html);
    }

    public function testDateTimeFormatAttribute(): void
    {
        $table = (new Table())->model(User::class);
        $table->column('name')->dateTimeFormat('H:i');
        self::assertEquals('H:i', $table->getColumns()->first()->getDateTimeFormat());
        self::assertEquals('UTC', $table->getColumns()->first()->getTimezone());
    }

    public function testSetColumnDateTimeToDateFormatHtml(): void
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index', 'params' => []]]);
        $table->column('name');
        $table->column('updated_at')->dateTimeFormat('d/m');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(Carbon::parse($user->updated_at)->format('d/m'), $html);
    }

    public function testSetColumnDateTimeToTimeFormatHtml(): void
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index', 'params' => []]]);
        $table->column('name');
        $table->column('updated_at')->dateTimeFormat('H\h i\m\i\n', 'Europe/Paris');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(Carbon::parse($user->updated_at)
            ->timezone('Europe/Paris')
            ->format('H\h i\m\i\n'), $html);
    }

    public function testSetColumnDateTimeToDateTimeFormatHtml(): void
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table())->model(User::class)->routes(['index' => ['name' => 'users.index', 'params' => []]]);
        $table->column('name');
        $table->column('updated_at')->dateTimeFormat('d/m/Y H:i', 'Europe/Paris');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(Carbon::parse($user->updated_at)
            ->timezone('Europe/Paris')
            ->format('d/m/Y H:i'), $html);
    }
}
