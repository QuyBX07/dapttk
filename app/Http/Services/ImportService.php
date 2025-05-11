<?php

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\ImportRepoInterface;
use App\Http\DTOs\Requests\ImportCreateData;
use App\Http\DTOs\Responses\ImportResponse;
use App\Http\Resources\ImportResource;

class ImportService
{

    public function __construct(protected ImportRepoInterface $importRepo)
    {
    }

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
    
}
