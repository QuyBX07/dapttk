<?php

namespace Tests\Unit;

use App\Http\Services\ImportService;
use App\Http\Repositories\Interfaces\ImportRepoInterface;
use App\Http\DTOs\Requests\ImportCreateData;
use App\Http\DTOs\Requests\ImportDetailData;
use App\Http\Resources\ImportResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Mockery;
use PHPUnit\Framework\TestCase;

class ImportServiceTest extends TestCase
{
    protected $importRepoMock;
    protected ImportService $importService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->importRepoMock = Mockery::mock(ImportRepoInterface::class);
        $this->importService = new ImportService($this->importRepoMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }



    public function test_get_detail_returns_import()
    {
        $importId = 'uuid-import-123';
        $importData = ['id' => $importId, 'supplier_id' => 'sup-1'];

        $this->importRepoMock
            ->shouldReceive('find')
            ->once()
            ->with($importId)
            ->andReturn($importData);

        $result = $this->importService->getDetail($importId);

        $this->assertEquals($importData, $result);
    }

    public function test_create_calls_repo_create_with_correct_data()
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

        $this->importRepoMock
            ->shouldReceive('create')
            ->once()
            ->with($dto->toArray())
            ->andReturn(true);

        $result = $this->importService->create($dto);

        $this->assertTrue($result);
    }

    public function test_delete_returns_boolean()
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

    public function test_get_deleted_returns_deleted_imports()
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

    public function test_get_total_import_cost_by_year_returns_correct_value()
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

    public function test_get_total_import_by_month_returns_correct_value()
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
