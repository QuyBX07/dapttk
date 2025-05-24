<?php

namespace App\Http\Services;

use App\Http\Repositories\Interfaces\ExportRepoInterface;
use Illuminate\Validation\ValidationException;

class ExportService
{
    public function __construct(protected ExportRepoInterface $exportRepo) {}

    public function getTotalRevenueByYear($year)
    {
        return $this->exportRepo->getTotalRevenueByYear($year);
    }

    public function getTotalRevenueByMonth($year, $month)
    {
        return $this->exportRepo->getTotalRevenueByMonth($year, $month);
    }

    // Thêm hàm lấy chi tiết export theo id
    public function getExportDetails($id)
    {
        $export = $this->exportRepo->findByIdWithRelations($id);

        return [
            'export_id' => $export->export_id,
            'customer' => $export->customer->name,
            'account' => $export->account->name,
            'total_amount' => $export->total_amount,
            'created_at' => $export->created_at->format('Y-m-d H:i:s'),
            'products' => $export->details->map(function ($detail) {
                return [
                    'name' => $detail->product->name,
                    'quantity' => $detail->quantity,
                    'price' => $detail->price,
                    'subtotal' => $detail->quantity * $detail->price,
                ];
            }),
        ];
    }

    public function getExportWithDetails($id)
    {
        $data = $this->exportRepo->getExportWithDetails($id);

        if (!$data) {
            return null; // hoặc xử lý lỗi theo ý bạn
        }

        return $data;
    }

    public function softDeleteExport($id)
    {
        return $this->exportRepo->softDelete($id);
    }

    public function revenueByCategory($month)
    {
        if (!is_numeric($month) || $month < 1 || $month > 12) {
            throw ValidationException::withMessages(['month' => 'Tháng không hợp lệ']);
        }

        $results = $this->exportRepo->revenueByCategory($month);

        $labels = $results->pluck('category_name')->toArray();
        $data = $results->pluck('total_revenue')->map(fn($val) => (float)$val)->toArray();

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    public function getDailyRevenue(int $month, int $year)
    {
        if ($month < 1 || $month > 12) {
            throw ValidationException::withMessages(['month' => 'Tháng không hợp lệ']);
        }
        if ($year < 2000 || $year > (int)date('Y')) {
            throw ValidationException::withMessages(['year' => 'Năm không hợp lệ']);
        }

        $results = $this->exportRepo->getDailyRevenue($month, $year);

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $labels = range(1, $daysInMonth);
        $data = array_fill(0, $daysInMonth, 0);

        foreach ($results as $row) {
            $dayIndex = $row->day - 1;
            $data[$dayIndex] = round($row->total, 2);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
}
