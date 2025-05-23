<?php

namespace App\Http\Repositories\Eloquent;


use App\Http\Repositories\Interfaces\ExportRepoInterface;
use Illuminate\Support\Facades\DB;


class ExportRepository implements ExportRepoInterface
{
    public function getTotalRevenueByYear($year)
    {
        return DB::table('exports')
            ->whereYear('created_at', $year)
            ->selectRaw('SUM(total_amount) as total_revenue')
            ->value('total_revenue');
    }

    public function getTotalRevenueByMonth($year, $month)
    {
        return DB::table('exports')
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->selectRaw('SUM(total_amount) as total_revenue')
            ->value('total_revenue');
    }
}
