<?php
namespace App\Http\Repositories\Interfaces;
use Illuminate\Support\Collection;
interface StatisticsRepositoryInterface
{
    public function getTotalRevenueByYear($year);
}