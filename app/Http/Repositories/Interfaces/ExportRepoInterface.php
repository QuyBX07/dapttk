<?php

namespace App\Http\Repositories\Interfaces;

use Illuminate\Support\Collection;
use App\Http\Repositories\Interfaces\ExportStatisticsRepositoryInterface;

interface ExportRepoInterface extends ExportStatisticsRepositoryInterface

{
    public function getTotalRevenueByYear($year);
    public function getTotalRevenueByMonth($year, $month); // tông doanh thu theo tháng

    public function findByIdWithRelations($id);

    public function getExportWithDetails($id);

    public function softDelete($id);

    public function createExport(array $exportData, array $details);

    public function revenueByCategory(int $month);

    public function getDailyRevenue(int $month, int $year);
}
