<?php

namespace Tests\Unit;

use App\Http\Services\ProductService;
use App\Http\Repositories\Interfaces\ProductRepoInterface;
use App\Http\DTOs\Requests\ProductCreateData;
use App\Models\Product;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Mockery;

class ProductServiceTest extends TestCase
{
    protected $productRepo;
    protected ProductService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock ProductRepoInterface
        $this->productRepo = Mockery::mock(ProductRepoInterface::class);
        $this->service = new ProductService($this->productRepo);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_products()
    {
        $mockCollection = collect(['mock-product-1', 'mock-product-2']);
        $this->productRepo->shouldReceive('findAll')->once()->andReturn($mockCollection);

        $result = $this->service->getAll();
        $this->assertEquals($mockCollection, $result);
    }

    public function test_create_product()
    {
        $dto = new ProductCreateData(
            name: 'Tên SP',
            category_id: 'cat-id',
            description: 'Mô tả SP',
            unit: 'Cái',
            quantity: 10,
            image: 'hinh.jpg',
            price: 20000
        );

        $mockProduct = new Product(['name' => 'Tên SP']);

        $this->productRepo
            ->shouldReceive('create')
            ->once()
            ->with($dto->toArray())
            ->andReturn($mockProduct);

        $result = $this->service->create($dto);

        $this->assertInstanceOf(Product::class, $result);
        $this->assertEquals('Tên SP', $result->name);
    }

    public function test_update_product()
    {
        $dto = new ProductCreateData(
            name: 'Tên cập nhật',
            category_id: 'cat-id',
            description: 'Mô tả mới',
            unit: 'Hộp',
            quantity: 5,
            image: 'hinh2.jpg',
            price: 15000
        );

        $this->productRepo
            ->shouldReceive('update')
            ->once()
            ->with('product-id', $dto->toArray())
            ->andReturn(true);

        $result = $this->service->update('product-id', $dto);
        $this->assertTrue($result);
    }

    public function test_delete_product()
    {
        $this->productRepo
            ->shouldReceive('delete')
            ->once()
            ->with('product-id')
            ->andReturn(true);

        $result = $this->service->delete('product-id');
        $this->assertTrue($result);
    }

    public function test_get_product_detail()
    {
        $mockProduct = new Product(['name' => 'SP Chi tiết']);

        $this->productRepo
            ->shouldReceive('find')
            ->once()
            ->with('product-id')
            ->andReturn($mockProduct);

        $result = $this->service->getDetail('product-id');
        $this->assertEquals('SP Chi tiết', $result->name);
    }

    public function test_search_products()
    {
        $mockResult = collect(['Kết quả 1', 'Kết quả 2']);

        $this->productRepo
            ->shouldReceive('search')
            ->once()
            ->with('tên sản phẩm')
            ->andReturn($mockResult);

        $result = $this->service->search('tên sản phẩm');
        $this->assertEquals($mockResult, $result);
    }
}
