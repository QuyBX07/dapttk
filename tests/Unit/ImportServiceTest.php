<?php

namespace Tests\Unit;

use App\Http\Services\ImportService;
use App\Http\Repositories\Interfaces\ImportRepoInterface;
use App\Http\DTOs\Requests\ImportCreateData;
use App\Http\DTOs\Requests\ImportDetailData;
use App\Http\Resources\ImportResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\TestCase;

class ImportServiceTest extends TestCase
{
    protected $importRepoMock;
    protected ImportService $importService;

    protected function setUp(): void
    {
        parent::setUp();
        // Tạo mock của ImportRepoInterface
        $this->importRepoMock = Mockery::mock(ImportRepoInterface::class);
        // Inject mock vào service
        $this->importService = new ImportService($this->importRepoMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetAllReturnsPaginatedResourceCollection()
    {
        // Giả lập dữ liệu trả về (LengthAwarePaginator)
        $paginator = new LengthAwarePaginator([], 0, 10);

        // Kỳ vọng hàm findAll() repo được gọi 1 lần và trả về $paginator
        $this->importRepoMock
            ->shouldReceive('findAll')
            ->once()
            ->andReturn($paginator);

        // Thực hiện gọi service
        $result = $this->importService->getAll();

        // Kiểm tra kết quả trả về là collection của ImportResource
        $this->assertInstanceOf(\Illuminate\Http\Resources\Json\AnonymousResourceCollection::class, $result);
    }

    public function testGetDetailReturnsImport()
    {
        $importId = 'uuid-import-123';

        // Giả lập dữ liệu trả về
        $importData = ['id' => $importId, 'supplier_id' => 'sup-1'];

        $this->importRepoMock
            ->shouldReceive('find')
            ->once()
            ->with($importId)
            ->andReturn($importData);

        $result = $this->importService->getDetail($importId);

        $this->assertEquals($importData, $result);
    }

    public function testCreateCallsRepoCreateWithCorrectData()
    {
        $detailDto = new ImportDetailData('prod-1', 2, 100);
        $dto = new ImportCreateData(
            supplier_id: 'sup-1',
            total_amount: 200,
            note: 'note here',
            account_id: 'acc-1',
            details: [$detailDto],
            is_delete: 0
        );

        // Kỳ vọng repo->create nhận đúng mảng convert từ DTO
        $this->importRepoMock
            ->shouldReceive('create')
            ->once()
            ->with($dto->toArray())
            ->andReturn(true);

        $result = $this->importService->create($dto);

        $this->assertTrue($result);
    }

    public function testDeleteReturnsBoolean()
    {
        $id = 'uuid-import-123';

        $this->importRepoMock
            ->shouldReceive('delete')
            ->once()
            ->with($id)
            ->andReturn(true);

        $result = $this->importService->delete($id);

        $this->assertTrue($result);
    }

    public function testGetDeletedReturnsDeletedImports()
{
    $deletedImports = [
        ['id' => 'import1', 'is_delete' => 1],
        ['id' => 'import2', 'is_delete' => 1],
    ];

    $this->importRepoMock
        ->shouldReceive('getDeleted')
        ->once()
        ->andReturn($deletedImports);

    $result = $this->importService->getDeleted();

    $this->assertEquals($deletedImports, $result);
}

public function testGetTotalImportCostByYearReturnsCorrectValue()
{
    $year = 2024;
    $totalCost = 15000.50;

    $this->importRepoMock
        ->shouldReceive('getTotalImportCostByYear')
        ->once()
        ->with($year)
        ->andReturn($totalCost);

    $result = $this->importService->getTotalImportCostByYear($year);

    $this->assertEquals($totalCost, $result);
}

public function testGetTotalImportByMonthReturnsCorrectValue()
{
    $year = 2024;
    $month = 5;
    $totalImport = 1200;

    $this->importRepoMock
        ->shouldReceive('getTotalImportByMonth')
        ->once()
        ->with($year, $month)
        ->andReturn($totalImport);

    $result = $this->importService->getTotalImportByMonth($year, $month);

    $this->assertEquals($totalImport, $result);
}

}
