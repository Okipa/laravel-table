<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Carbon\Carbon;
use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\Models\User;
use Okipa\LaravelTable\Test\LaravelTableTestCase;

class DateTimeTest extends LaravelTableTestCase
{
    public function testDateFormatAttribute()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->dateTimeFormat('d/m/Y H:i:s');
        $this->assertEquals('d/m/Y H:i:s', $table->getColumns()->first()->dateTimeFormat);
    }

    public function testDateFormatHtml()
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index', 'params' => []]]);
        $table->column('name');
        $table->column('updated_at')->dateTimeFormat('d/m/Y');
        $table->configure();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->toHtml();
        $this->assertStringContainsString(Carbon::parse($user->updated_at)->format('d/m/Y'), $html);
    }

    public function testDateTimeFormatAttribute()
    {
        $table = (new Table)->model(User::class);
        $table->column('name')->dateTimeFormat('H:i');
        $this->assertEquals('H:i', $table->getColumns()->first()->dateTimeFormat);
    }

    public function testSetColumnDateTimeToDateFormatHtml()
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index', 'params' => []]]);
        $table->column('name');
        $table->column('updated_at')->dateTimeFormat('d/m');
        $table->configure();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->toHtml();
        $this->assertStringContainsString(Carbon::parse($user->updated_at)->format('d/m'), $html);
    }

    public function testSetColumnDateTimeToTimeFormatHtml()
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index', 'params' => []]]);
        $table->column('name');
        $table->column('updated_at')->dateTimeFormat('H\h i\m\i\n');
        $table->configure();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->toHtml();
        $this->assertStringContainsString(Carbon::parse($user->updated_at)->format('H\h i\m\i\n'), $html);
    }

    public function testSetColumnDateTimeToDateTimeFormatHtml()
    {
        $this->routes(['users'], ['index']);
        $user = $this->createUniqueUser();
        $table = (new Table)->model(User::class)->routes(['index' => ['name' => 'users.index', 'params' => []]]);
        $table->column('name');
        $table->column('updated_at')->dateTimeFormat('d/m/Y H:i');
        $table->configure();
        $html = view('laravel-table::' . $table->tbodyTemplatePath, compact('table'))->toHtml();
        $this->assertStringContainsString(Carbon::parse($user->updated_at)->format('d/m/Y H:i'), $html);
    }
}
