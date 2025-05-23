<?php
namespace App\Http\Repositories\Interfaces;
use Illuminate\Support\Collection;
interface ImportStatisticsRepositoryInterface
{
    public function getTotalImportCostByYear($year);// tông doanh thu theo năm
    public function getTotalImportByMonth($year, $month);// tông doanh thu theo tháng
    
}