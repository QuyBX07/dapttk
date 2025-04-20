<?php

namespace App\Http\Requests;

use App\Http\DTOs\Requests\ImportCreateData;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\DTOs\Requests\ImportDetailData;


class ImportRequest extends FormRequest
{
    /**
     * Xác định người dùng có quyền gửi request này không.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Các rules để validate dữ liệu nhập.
     */
    public function rules(): array
    {
        return [
            'supplier_id'  => 'required|uuid|exists:suppliers,supplier_id',
            'total_amount' => 'required|numeric|min:0',
            'import_date'  => 'required|date',
            'details'       => 'required|array',  // Kiểm tra dữ liệu chi tiết
            'details.*.product_id' => 'required|uuid|exists:products,product_id', // Các chi tiết phải chứa product_id hợp lệ
            'details.*.quantity'    => 'required|integer|min:1', // Kiểm tra số lượng
            'details.*.price'      => 'required|numeric|min:0', // Kiểm tra giá nhập
        ];
    }

    /**
     * Thông báo lỗi tùy chỉnh.
     */
    public function messages(): array
    {
        return [
            'supplier_id.required'  => 'Vui lòng chọn nhà cung cấp.',
            'supplier_id.uuid'      => 'ID nhà cung cấp không hợp lệ.',
            'supplier_id.exists'    => 'Nhà cung cấp không tồn tại.',
            'total_amount.required' => 'Tổng tiền là bắt buộc.',
            'total_amount.numeric'  => 'Tổng tiền phải là số.',
            'total_amount.min'      => 'Tổng tiền không được âm.',
            'import_date.required'  => 'Vui lòng nhập ngày nhập hàng.',
            'import_date.date'      => 'Ngày nhập không hợp lệ.',
            'details.required'      => 'Chi tiết nhập khẩu là bắt buộc.',
            'details.array'         => 'Chi tiết nhập khẩu phải là một mảng.',
            'details.*.product_id.required' => 'ID sản phẩm là bắt buộc.',
            'details.*.product_id.uuid'     => 'ID sản phẩm không hợp lệ.',
            'details.*.product_id.exists'   => 'Sản phẩm không tồn tại.',
            'details.*.quantity.required'    => 'Số lượng sản phẩm là bắt buộc.',
            'details.*.quantity.integer'     => 'Số lượng sản phẩm phải là số nguyên.',
            'details.*.quantity.min'         => 'Số lượng sản phẩm không được âm.',
            'details.*.price.required'      => 'Giá nhập là bắt buộc.',
            'details.*.price.numeric'       => 'Giá nhập phải là số.',
            'details.*.price.min'           => 'Giá nhập không được âm.',
        ];
    }

    /**
     * Chuyển đổi dữ liệu từ request thành ImportCreateData.
     */
    public function toDTO(): ImportCreateData
    {
        // Chuyển dữ liệu từ request thành ImportCreateData
        $data = $this->validated();  // Lấy tất cả dữ liệu đã validate

        // Chuyển details thành ImportDetailData
        $details = array_map(
            fn($item) => ImportDetailData::fromArray($item),
            $data['details']
        );

        // Trả về ImportCreateData
        return new ImportCreateData(
            supplier_id: $data['supplier_id'],
            total_amount: $data['total_amount'],
            import_date: $data['import_date'],
            details: $details
        );
    }
}
