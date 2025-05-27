<?php

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\ImportRepoInterface;
use App\Http\DTOs\Requests\ImportCreateData;
use App\Http\DTOs\Responses\ImportResponse;
use App\Http\Resources\ImportResource;
use Illuminate\Validation\ValidationException;

class ImportService
{

    public function __construct(protected ImportRepoInterface $importRepo) {}

    public function getAll()
    {
        // $imports = $this->importRepo->findAll(); // Lấy tất cả dữ liệu từ repository
        // return ImportResource::collection($imports); // Trả về dữ liệu phân trang qua Resource
        // return $this->importRepo->findAll(); // Lấy tất cả dữ liệu từ repository
        $imports = $this->importRepo->findAll(); // Trả về một đối tượng LengthAwarePaginator
        return ImportResource::collection($imports); // Laravel tự giữ phân trang
    }

    public function getDetail(string $id)
    {
        return $this->importRepo->find($id);
    }

    public function create(ImportCreateData $dto)
    {
        // Lấy dữ liệu chính và chi tiết từ DTO
        // $importData = $dto->toArray(); // Chuyển DTO thành mảng
        // $details = $dto->details; // Dữ liệu chi tiết đã được phân tách trong DTO

        // Gọi phương thức trong repository để tạo phiếu nhập với các chi tiết
        return $this->importRepo->create($dto->toArray());
    }




    public function delete(string $id): bool
    {
        return $this->importRepo->delete($id);
    }


    public function getDeleted()
    {
        return $this->importRepo->getDeleted();
    }


    public function getTotalImportCostByYear($year)
    {
        return $this->importRepo->getTotalImportCostByYear($year);
    }

    public function getTotalImportByMonth($year, $month)
    {
        return $this->importRepo->getTotalImportByMonth($year, $month);
    }

    public function importCostByCategory(?int $month, ?int $year)
    {
        if ($month !== null && (!is_numeric($month) || $month < 1 || $month > 12)) {
            throw ValidationException::withMessages(['month' => 'Tháng không hợp lệ']);
        }

        if ($year !== null && (!is_numeric($year) || $year < 2000 || $year > (int)date('Y') + 1)) {
            throw ValidationException::withMessages(['year' => 'Năm không hợp lệ']);
        }

        $results = $this->importRepo->importCostByCategory($month, $year);

        $labels = $results->pluck('label')->toArray();
        $data = $results->pluck('total')->map(fn($val) => (float)$val)->toArray();

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
    public function search($query)
    {
        return $this->importRepo->search($query);
    }
}
