<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Carbon\Carbon;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class DateTimeTest extends LaravelTableTestCase
{
    public function testDateFormatAttribute(): void
    {
        $table = (new Table())->fromModel(User::class);
        $table->column('name')->dateTimeFormat('d/m/Y H:i:s');
        self::assertEquals('d/m/Y H:i:s', $table->getColumns()->first()->getDateTimeFormat());
    }

    public function testDateFormatHtml(): void
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index', 'params' => []]]);
        $table->column('name');
        $table->column('updated_at')->dateTimeFormat('d/m/Y');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(Carbon::parse($user->updated_at)->format('d/m/Y'), $html);
    }

    public function testDateTimeFormatAttribute(): void
    {
        $table = (new Table())->fromModel(User::class);
        $table->column('name')->dateTimeFormat('H:i');
        self::assertEquals('H:i', $table->getColumns()->first()->getDateTimeFormat());
    }

    public function testSetColumnDateTimeToDateFormatHtml(): void
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index', 'params' => []]]);
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
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index', 'params' => []]]);
        $table->column('name');
        $table->column('updated_at')->dateTimeFormat('H\h i\m\i\n');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(Carbon::parse($user->updated_at)->format('H\h i\m\i\n'), $html);
    }

    public function testSetColumnDateTimeToDateTimeFormatHtml(): void
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index', 'params' => []]]);
        $table->column('name');
        $table->column('updated_at')->dateTimeFormat('d/m/Y H:i');
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString(Carbon::parse($user->updated_at)->format('d/m/Y H:i'), $html);
    }
}
