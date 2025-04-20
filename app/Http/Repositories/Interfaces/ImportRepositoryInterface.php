<?php
namespace App\Http\Repositories\Interfaces;
use App\Models\Import;
use App\Http\DTOs\Requests\ImportCreateData;
interface ImportRepositoryInterface
{
  public function createWithDetails(ImportCreateData $data): Import;

}