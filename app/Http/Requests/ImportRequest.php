<?php

namespace App\Http\Requests;

use App\Http\DTOs\Requests\ImportCreateData;
use Illuminate\Foundation\Http\FormRequest;
use App\Http\DTOs\Requests\ImportDetailData;

class ImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id'  => 'required|uuid|exists:suppliers,supplier_id',
            'total_amount' => 'required|numeric|min:0',
            'account_id'   => 'required|string|max:255', 
            'note'         => 'nullable|string|max:1000',
            'details'      => 'required|array',
            'details.*.product_id' => 'required|uuid|exists:products,product_id',
            'details.*.quantity'   => 'required|integer|min:1',
            'details.*.price'      => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'supplier_id.required'  => 'Vui lòng chọn nhà cung cấp.',
            'supplier_id.uuid'      => 'ID nhà cung cấp không hợp lệ.',
            'supplier_id.exists'    => 'Nhà cung cấp không tồn tại.',
            'total_amount.required' => 'Tổng tiền là bắt buộc.',
            'total_amount.numeric'  => 'Tổng tiền phải là số.',
            'total_amount.min'      => 'Tổng tiền không được âm.',
            'account_id.required'   => 'Người lập phiếu là bắt buộc.',
            'account_id.string'     => 'Người lập phiếu phải là chuỗi.',
            'account_id.max'        => 'Tên người lập phiếu không được vượt quá 255 ký tự.',
            'note.max'              => 'Ghi chú không được vượt quá 1000 ký tự.',
            'details.required'      => 'Chi tiết nhập khẩu là bắt buộc.',
            'details.array'         => 'Chi tiết nhập khẩu phải là một mảng.',
            'details.*.product_id.required' => 'ID sản phẩm là bắt buộc.',
            'details.*.product_id.uuid'     => 'ID sản phẩm không hợp lệ.',
            'details.*.product_id.exists'   => 'Sản phẩm không tồn tại.',
            'details.*.quantity.required'   => 'Số lượng sản phẩm là bắt buộc.',
            'details.*.quantity.integer'    => 'Số lượng sản phẩm phải là số nguyên.',
            'details.*.quantity.min'        => 'Số lượng sản phẩm không được âm.',
            'details.*.price.required'      => 'Giá nhập là bắt buộc.',
            'details.*.price.numeric'       => 'Giá nhập phải là số.',
            'details.*.price.min'           => 'Giá nhập không được âm.',
        ];
    }

    public function toDTO(): ImportCreateData
    {
        $data = $this->validated();

        return new ImportCreateData(
            supplier_id: $data['supplier_id'],
            total_amount: $data['total_amount'],
            note: $data['note'] ?? null,
            account_id: $data['account_id'],
            is_delete: 0,
            details: array_map(
                fn($item) => ImportDetailData::fromArray($item),
                $data['details']
            )
        );
    }
}
