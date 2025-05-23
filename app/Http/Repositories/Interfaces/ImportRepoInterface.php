<?php
namespace App\Http\Repositories\Interfaces;
use Illuminate\Support\Collection;
use App\Http\Repositories\Interfaces\BaseRepositoryInterface;
use App\Http\Repositories\Interfaces\SearchRepositoryInterface;
use App\Http\Repositories\Interfaces\OnlyDeleteRepositoryInterface;

interface ImportRepoInterface extends BaseRepositoryInterface, 
                                    SearchRepositoryInterface, 
                                    OnlyDeleteRepositoryInterface,
                                    ImportStatisticsRepositoryInterface

{
    public function findAll();

    public function find(string $id);

    public function create(array $data);

    public function update(string $id, array $data);

    public function delete(string $id);
    public function search(string $query);
    public function getDeleted();

    public function getTotalImportCostByYear($year);
    public function getTotalImportByMonth($year, $month);// tông doanh thu theo tháng
}