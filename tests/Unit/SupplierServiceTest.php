<?php

namespace Tests\Unit;

use App\Http\Services\SupplierService;
use App\Http\Repositories\Interfaces\SupplierRepoInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Mockery;
use Tests\TestCase;

class SupplierServiceTest extends TestCase
{
    protected $supplierRepo;
    protected $supplierService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->supplierRepo = Mockery::mock(SupplierRepoInterface::class);
        $this->supplierService = new SupplierService($this->supplierRepo);
    }

    /** @test */
    public function it_can_create_supplier_with_valid_data()
    {
        $data = [
            'name' => 'ABC Supplier',
            'phone' => '0123456789',
            'email' => 'abc@supplier.com',
            'address' => '123 Street',
        ];

        $this->supplierRepo
            ->shouldReceive('create')
            ->once()
            ->with($data)
            ->andReturn((object)$data);

        $result = $this->supplierService->create($data);

        $this->assertEquals($data['name'], $result->name);
    }

    /** @test */
    public function it_throws_validation_exception_on_invalid_data()
    {
        $this->expectException(ValidationException::class);

        $data = [
            'name' => '',
            'phone' => '',
            'email' => 'invalid-email',
            'address' => '',
        ];

        $this->supplierService->create($data);
    }

    /** @test */
    public function it_can_get_paginated_suppliers()
    {
        $perPage = 5;
        $items = new Collection([
            ['id' => 1, 'name' => 'Supplier A'],
            ['id' => 2, 'name' => 'Supplier B'],
        ]);

        $paginator = new LengthAwarePaginator($items, 2, $perPage, 1, ['path' => '/']);

        $this->supplierRepo
            ->shouldReceive('getAllPaginated')
            ->once()
            ->with($perPage)
            ->andReturn($paginator);

        $result = $this->supplierService->getPaginated($perPage);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    /** @test */
    public function it_can_get_supplier_detail_by_id()
    {
        $id = 1;
        $supplier = (object)[
            'id' => $id,
            'name' => 'Supplier X',
        ];

        $this->supplierRepo
            ->shouldReceive('findById')
            ->once()
            ->with($id)
            ->andReturn($supplier);

        $result = $this->supplierService->getDetail($id);

        $this->assertEquals($supplier, $result);
    }

    /** @test */
    public function it_can_update_supplier()
    {
        $id = 1;
        $data = ['name' => 'Updated Supplier'];

        $this->supplierRepo
            ->shouldReceive('update')
            ->once()
            ->with($id, $data)
            ->andReturn(true);

        $result = $this->supplierService->update($id, $data);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_delete_supplier()
    {
        $id = 1;

        $this->supplierRepo
            ->shouldReceive('delete')
            ->once()
            ->with($id)
            ->andReturn(true);

        $result = $this->supplierService->delete($id);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_can_search_suppliers()
    {
        $query = 'supplier';

        $resultList = collect([
            ['id' => 1, 'name' => 'supplier A'],
            ['id' => 2, 'name' => 'supplier B'],
        ]);

        $this->supplierRepo
            ->shouldReceive('search')
            ->once()
            ->with($query)
            ->andReturn($resultList);

        $result = $this->supplierService->search($query);

        $this->assertCount(2, $result);
    }

    /** @test */
    public function it_can_get_all_suppliers_for_select()
    {
        $selectList = collect([
            ['id' => 1, 'name' => 'Supplier 1'],
            ['id' => 2, 'name' => 'Supplier 2'],
        ]);

        $this->supplierRepo
            ->shouldReceive('getAllForSelect')
            ->once()
            ->andReturn($selectList);

        $result = $this->supplierService->getAllForSelect();

        $this->assertEquals($selectList, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
