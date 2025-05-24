<?php

namespace App\Http\Controllers;

use App\Http\Services\ImportService;
use Illuminate\Http\Request;
use App\Http\Services\ExportService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StatisticsController extends Controller
{


    public function __construct(protected ImportService $importService, protected ExportService $exportService) {}
    public function index(Request $request)
    {
        $year = $request->input('year', now()->year);
        $month = $request->input('month', now()->month);

        // Lấy dữ liệu tương tự

        $totalRevenue = $this->exportService->getTotalRevenueByYear($year);
        $totalImportCost = $this->importService->getTotalImportCostByYear($year);

        $monthlyRevenue = [];
        $monthlyImport = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthlyRevenue[$m] = $this->exportService->getTotalRevenueByMonth($year, $m) ?? 0;
            $monthlyImport[$m] = $this->importService->getTotalImportByMonth($year, $m) ?? 0;
        }

        return view('layout.statistics.content', compact(
            'year',
            'month',
            'totalRevenue',
            'totalImportCost',
            'monthlyRevenue',
            'monthlyImport'
        ));
    }

    // Hàm API trả về dữ liệu thống kê dạng JSON
    public function getStatistics(Request $request)
    {
        $year = $request->input('year', now()->year);

        $totalRevenue = $this->exportService->getTotalRevenueByYear($year) ?? 0;
        $totalImportCost = $this->importService->getTotalImportCostByYear($year);

        $monthlyRevenue = [];
        $monthlyImport = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthlyRevenue[$m] = $this->exportService->getTotalRevenueByMonth($year, $m) ?? 0;
            $monthlyImport[$m] = $this->importService->getTotalImportByMonth($year, $m) ?? 0;
        }

        return response()->json([
            'year' => $year,
            'totalRevenue' => $totalRevenue,
            'totalImportCost' => $totalImportCost,
            'monthlyRevenue' => $monthlyRevenue,
            'monthlyImport' => $monthlyImport,
        ]);
    }

    public function revenueByCategory(Request $request)
    {
        $month = $request->query('month');

        try {
            $result = $this->exportService->revenueByCategory($month);
            return response()->json($result);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function importCostByCategory(Request $request)
    {
        $month = $request->input('month');

        try {
            $result = $this->importService->importCostByCategory($month);
            return response()->json($result);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getDailyRevenue(Request $request)
    {
        $month = (int) $request->query('month', date('m'));
        $year = (int) $request->query('year', date('Y'));

        try {
            $result = $this->exportService->getDailyRevenue($month, $year);
            return response()->json($result);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
