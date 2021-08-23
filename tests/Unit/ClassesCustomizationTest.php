<?php

namespace Okipa\LaravelTable\Tests\Unit;

use Okipa\LaravelTable\Table;
use Okipa\LaravelTable\Test\LaravelTableTestCase;
use Okipa\LaravelTable\Test\Models\User;

class ClassesCustomizationTest extends LaravelTableTestCase
{
    /** @test */
    public function it_can_set_container_classes(): void
    {
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->containerClasses(['test-custom-class']);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(['test-custom-class'], $table->getContainerClasses());
        self::assertStringContainsString('<div class="table-container test-custom-class">', $html);
    }

    /** @test */
    public function it_can_set_table_classes(): void
    {
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tableClasses(['test-custom-class']);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(['test-custom-class'], $table->getTableClasses());
        self::assertStringContainsString('<table class="table test-custom-class">', $html);
    }

    /** @test */
    public function it_can_set_tr_classes(): void
    {
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->trClasses(['test-custom-class']);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(['test-custom-class'], $table->getTrClasses());
        self::assertEquals(substr_count($html, '<tr '), substr_count($html, 'test-custom-class'));
    }

    /** @test */
    public function it_can_set_th_classes(): void
    {
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->thClasses(['test-custom-class']);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(['test-custom-class'], $table->getThClasses());
        self::assertEquals(substr_count($html, '<th '), substr_count($html, 'test-custom-class'));
    }

    /** @test */
    public function it_can_set_td_classes(): void
    {
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)
            ->routes(['index' => ['name' => 'users.index']])
            ->tdClasses(['test-custom-class']);
        $table->column('name');
        $table->configure();
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(['test-custom-class'], $table->getTdClasses());
        self::assertEquals(substr_count($html, '<td '), substr_count($html, 'test-custom-class'));
    }

    /** @test */
    public function it_can_set_column_classes(): void
    {
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name')->classes(['test-custom-class']);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(['test-custom-class'], $table->getColumns()->first()->getClasses());
        self::assertEquals(2, substr_count($html, 'test-custom-class'));
    }

    /** @test */
    public function it_can_set_result_classes(): void
    {
        $this->createMultipleUsers(2);
        $this->routes(['users'], ['index']);
        $table = (new Table())->fromModel(User::class)->routes(['index' => ['name' => 'users.index']]);
        $table->column('name');
        $table->column('email');
        $table->result()->classes(['test-custom-class']);
        $table->configure();
        $html = view('laravel-table::' . $table->getTbodyTemplatePath(), compact('table'))->toHtml();
        self::assertEquals(['test-custom-class'], $table->getResults()->first()->getClasses());
        self::assertEquals(1, substr_count($html, 'test-custom-class'));
    }

    /** @test */
    public function it_can_set_row_conditional_classes(): void
    {
        $this->routes(['users'], ['index', 'create', 'edit', 'destroy']);
        $this->createMultipleUsers(5);
        $table = (new Table())->fromModel(User::class)
            ->routes([
                'index' => ['name' => 'users.index'],
                'create' => ['name' => 'users.create'],
                'edit' => ['name' => 'users.edit'],
                'destroy' => ['name' => 'users.destroy'],
            ])
            ->rowsConditionalClasses(
                fn(array $row) => $row['id'] === 1,
                fn(array $row) => ['custom', 'first-class', $row['id']]
            )
            ->rowsConditionalClasses(fn(array $row) => $row['id'] === 1, ['custom', 'second-class', '1'])
            ->rowsConditionalClasses(fn(array $row) => $row['id'] === 2, ['custom', 'class', '2']);
        $table->column('name')->title('Name');
        $table->column('email')->title('Email');
        $table->configure();
        foreach ($table->getPaginator()->getCollection() as $row) {
            if (! in_array($row['id'], [1, 2], true)) {
                self::assertNotContains('conditional_classes', $row);
            }
            if ($row['id'] === 1) {
                self::assertEquals(
                    ['custom', 'first-class', 1, 'custom', 'second-class', '1'],
                    $row['conditional_classes']
                );
            }
            if ($row['id'] === 2) {
                self::assertEquals(['custom', 'class', '2'], $row['conditional_classes']);
            }
        }
        $html = view('laravel-table::' . $table->getTableTemplatePath(), compact('table'))->toHtml();
        self::assertStringContainsString('custom first-class 1 custom second-class 1', $html);
        self::assertStringContainsString('custom class 2', $html);
    }
}
