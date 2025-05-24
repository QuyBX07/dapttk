<?php
namespace App\Http\Repositories\Interfaces;
use Illuminate\Support\Collection;
interface ExportStatisticsRepositoryInterface
{
    public function getTotalRevenueByYear($year);// tông doanh thu theo năm
    public function getTotalRevenueByMonth($year, $month);// tông doanh thu theo tháng
    
}