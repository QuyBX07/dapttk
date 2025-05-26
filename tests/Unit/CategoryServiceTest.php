<?php

namespace Tests\Unit;

use App\Http\Services\CategoryService;
use App\Http\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    protected $categoryRepo;
    protected $categoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryRepo = Mockery::mock(CategoryRepositoryInterface::class);
        $this->categoryService = new CategoryService($this->categoryRepo);
    }

    /** @test */
    public function it_can_create_a_category()
    {
        $data = ['name' => 'Category A'];

        $this->categoryRepo
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn((object)$data);

        $result = $this->categoryService->create($data);

        $this->assertEquals('Category A', $result->name);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        $id = 'uuid-123';
        $data = ['name' => 'Updated Category'];

        $this->categoryRepo
            ->shouldReceive('update')
            ->once()
            ->with($id, $data)
            ->andReturn(true);

        $result = $this->categoryService->update($id, $data);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        $id = 'uuid-123';

        $this->categoryRepo
            ->shouldReceive('delete')
            ->once()
            ->with($id)
            ->andReturn(true);

        $result = $this->categoryService->delete($id);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_find_a_category_by_id()
    {
        $id = 'uuid-123';
        $category = (object)[
            'category_id' => $id,
            'name' => 'Category X'
        ];

        $this->categoryRepo
            ->shouldReceive('find')
            ->once()
            ->with($id)
            ->andReturn($category);

        $result = $this->categoryService->find($id);

        $this->assertEquals($category, $result);
    }

    /** @test */
    public function it_can_get_all_categories()
    {
        $categories = collect([
            ['category_id' => 1, 'name' => 'Cat 1'],
            ['category_id' => 2, 'name' => 'Cat 2'],
        ]);

        $this->categoryRepo
            ->shouldReceive('findAll')
            ->once()
            ->andReturn($categories);

        $result = $this->categoryService->getAll();

        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_can_paginate_categories()
    {
        $perPage = 10;
        $items = new Collection([
            ['category_id' => 1, 'name' => 'Cat A'],
            ['category_id' => 2, 'name' => 'Cat B'],
        ]);

        $paginator = new LengthAwarePaginator($items, 2, $perPage, 1, ['path' => '/']);

        $this->categoryRepo
            ->shouldReceive('paginate')
            ->once()
            ->with($perPage)
            ->andReturn($paginator);

        $result = $this->categoryService->paginate($perPage);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    /** @test */
    public function it_can_search_categories()
    {
        $query = 'search-keyword';
        $results = collect([
            ['category_id' => 1, 'name' => 'Search Result 1'],
            ['category_id' => 2, 'name' => 'Search Result 2'],
        ]);

        $this->categoryRepo
            ->shouldReceive('search')
            ->once()
            ->with($query)
            ->andReturn($results);

        $result = $this->categoryService->search($query);

        $this->assertCount(2, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
