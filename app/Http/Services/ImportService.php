<?php

namespace App\Http\Services;

use App\Http\Repositories\Eloquent\ImportRepository;
use App\Http\DTOs\Requests\ImportCreateData;

class ImportService
{
    protected ImportRepository $importRepo;

    public function __construct(ImportRepository $importRepo)
    {
        $this->importRepo = $importRepo;
    }

    public function getAll()
    {
        return $this->importRepo->findAll();
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
        return $this->importRepo->createWithDetails($dto);
    }
    
    


    public function delete(string $id): bool
    {
        return $this->importRepo->delete($id);
    }
    
}
