<?php

namespace Tests\Unit;

use App\Http\Services\CustomerService;
use App\Http\Repositories\Interfaces\CustomerRepoInterface;
use App\Http\DTOs\Requests\CustomerCreateData;
use App\Models\Customer;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Mockery;

class CustomerServiceTest extends TestCase
{
    protected $customerRepo;
    protected CustomerService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customerRepo = Mockery::mock(CustomerRepoInterface::class);
        $this->service = new CustomerService($this->customerRepo);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_customers()
    {
        $mockData = collect(['customer-1', 'customer-2']);

        $this->customerRepo
            ->shouldReceive('findAll')
            ->once()
            ->andReturn($mockData);

        $result = $this->service->getAll();
        $this->assertEquals($mockData, $result);
    }

    public function test_create_customer()
    {
        $dto = new CustomerCreateData(
            name: 'Nguyễn Văn A',
            phone: '0909999999',
            email: 'a@example.com',
            address: '123 Đường ABC'
        );

        $mockCustomer = new Customer(['name' => 'Nguyễn Văn A']);

        $this->customerRepo
            ->shouldReceive('create')
            ->once()
            ->with($dto->toArray())
            ->andReturn($mockCustomer);

        $result = $this->service->create($dto);

        $this->assertInstanceOf(Customer::class, $result);
        $this->assertEquals('Nguyễn Văn A', $result->name);
    }

    public function test_update_customer()
    {
        $dto = new CustomerCreateData(
            name: 'Nguyễn Văn B',
            phone: '0912345678',
            email: 'b@example.com',
            address: '456 Đường XYZ'
        );

        $this->customerRepo
            ->shouldReceive('update')
            ->once()
            ->with('customer-id', $dto->toArray())
            ->andReturn(true);

        $result = $this->service->update('customer-id', $dto);
        $this->assertTrue($result);
    }

    public function test_delete_customer()
    {
        $this->customerRepo
            ->shouldReceive('delete')
            ->once()
            ->with('customer-id')
            ->andReturn(true);

        $result = $this->service->delete('customer-id');
        $this->assertTrue($result);
    }

    public function test_get_customer_detail()
    {
        $mockCustomer = new Customer(['name' => 'Chi tiết A']);

        $this->customerRepo
            ->shouldReceive('find')
            ->once()
            ->with('customer-id')
            ->andReturn($mockCustomer);

        $result = $this->service->getDetail('customer-id');
        $this->assertEquals('Chi tiết A', $result->name);
    }

    public function test_search_customer()
    {
        $mockResult = collect(['Kết quả tìm kiếm']);

        $this->customerRepo
            ->shouldReceive('search')
            ->once()
            ->with('Nguyễn')
            ->andReturn($mockResult);

        $result = $this->service->search('Nguyễn');
        $this->assertEquals($mockResult, $result);
    }
}
