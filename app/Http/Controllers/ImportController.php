<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportRequest;
use App\Http\Services\ImportService;
use App\Http\DTOs\Requests\ImportCreateData;
use Illuminate\Http\JsonResponse;
use App\Http\Services\ProductService;

class ImportController extends Controller
{
    protected ImportService $importService;
    protected ProductService $productService;

    public function __construct(ImportService $importService, ProductService $productService)
    {
        $this->productService = $productService;
    
        $this->importService = $importService;
    }

// get all
    public function getAll()
    {
        $imports = $this->importService->getAll();
        $products = $this->productService->getAll();

        // $importData = $imports->response()->getData(true);

        // return response()->json([
        //     'success' => true,
        //     'imports' => $importData['data'],
        //     'meta' => $importData['meta'],
        //     'links' => $importData['links'],
           
        // ]);
        // return view('layout.import.content', [
        //     'imports' => $imports,
        //     'products' => $products
        // ]);
        
        return view('layout.import.content', [
            'imports' => $imports,
            'products' => $products
        ]);
        // return $imports;
    }

// get detail
    public function getDetail(string $id)
    {
        $import = $this->importService->getDetail($id);

        return $import;
    }

//create
    public function create(ImportRequest $request)
    {

        $validatedData = $request->validated();
        $dto = ImportCreateData::fromArray($validatedData);
        $import = $this->importService->create($dto);

        // return response()->json([
        //     'success' => true,
        //     'data'    => $import,
        // ], 201);

        return redirect()->back()->with('success', 'Thêm phiếu nhập thành công.');
    }

// delete
    public function delete(string $id)
    {
        $deleted = $this->importService->delete($id);

        // return response()->json([
        //     'success' => $deleted,
        //     'message' => $deleted ? 'Xóa phiếu nhập thành công.' : 'Không thể xóa phiếu nhập.'
        // ]);
        return redirect()->back()->with('success', 'Xóa phiếu nhập thành công.');
    }


    //post man 
    public function postman()
    {
        $imports = $this->importService->getAll();
        $products = $this->productService->getAll();
        return $imports;
    }
}
