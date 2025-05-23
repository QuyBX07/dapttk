<?php

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\ExportRepoInterface;
use App\Http\DTOs\Requests\ImportCreateData;
use App\Http\DTOs\Responses\ImportResponse;
use App\Http\Resources\ImportResource;

class ExportService
{

    public function __construct(protected ExportRepoInterface $exportRepo)
    {
    }

    

    public function getTotalRevenueByYear($year){
        return $this->exportRepo->getTotalRevenueByYear($year);
    }
    public function getTotalRevenueByMonth($year, $month){
        return $this->exportRepo->getTotalRevenueByMonth($year, $month);
    }
}
