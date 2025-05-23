<?php
namespace App\Http\Repositories\Interfaces;
use Illuminate\Support\Collection;
use App\Http\Repositories\Interfaces\ExportStatisticsRepositoryInterface;

interface ExportRepoInterface extends ExportStatisticsRepositoryInterface

{
    public function getTotalRevenueByYear($year);
    public function getTotalRevenueByMonth($year, $month);// tông doanh thu theo tháng
}