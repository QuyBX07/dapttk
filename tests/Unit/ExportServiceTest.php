<?php

namespace Tests\Unit;

use App\Http\Services\ExportService;
use App\Http\Repositories\Interfaces\ExportRepoInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

class ExportServiceTest extends TestCase
{
    protected $exportRepoMock;
    protected ExportService $exportService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->exportRepoMock = Mockery::mock(ExportRepoInterface::class);
        $this->exportService = new ExportService($this->exportRepoMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testGetTotalRevenueByYearReturnsCorrectValue()
    {
        $year = 2024;
        $totalRevenue = 25000.75;

        $this->exportRepoMock
            ->shouldReceive('getTotalRevenueByYear')
            ->once()
            ->with($year)
            ->andReturn($totalRevenue);

        $result = $this->exportService->getTotalRevenueByYear($year);

        $this->assertEquals($totalRevenue, $result);
    }

    public function testGetTotalRevenueByMonthReturnsCorrectValue()
    {
        $year = 2024;
        $month = 5;
        $totalRevenue = 2200;

        $this->exportRepoMock
            ->shouldReceive('getTotalRevenueByMonth')
            ->once()
            ->with($year, $month)
            ->andReturn($totalRevenue);

        $result = $this->exportService->getTotalRevenueByMonth($year, $month);

        $this->assertEquals($totalRevenue, $result);
    }
}
