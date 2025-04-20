<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Http\Services\ImportService;
use App\Http\DTOs\Requests\ImportCreateData;
use Illuminate\Http\JsonResponse;

class ImportController extends Controller
{
    protected ImportService $importService;

    public function __construct(ImportService $importService)
    {
        $this->importService = $importService;
    }

    /**
     * Get all imports.
     */
    public function getAll(): JsonResponse
    {
        $imports = $this->importService->getAll();

        return response()->json([
            'success' => true,
            'data'    => $imports,
        ]);
    }

    /**
     * Get import detail by ID.
     */
    public function getDetail(string $id): JsonResponse
    {
        $import = $this->importService->getDetail($id);

        return response()->json([
            'success' => true,
            'data'    => $import,
        ]);
    }

    /**
     * Create new import.
     */
    public function create(ImportRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        $dto = ImportCreateData::fromArray($validatedData);

        $import = $this->importService->create($dto);

        return response()->json([
            'success' => true,
            'data'    => $import,
        ], 201);
    }

    /**
     * Update import by ID.
     */

    /**
     * Delete import by ID.
     */
    public function delete(string $id): JsonResponse
    {
        $deleted = $this->importService->delete($id);

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Xóa phiếu nhập thành công.' : 'Không thể xóa phiếu nhập.'
        ]);
    }
}
