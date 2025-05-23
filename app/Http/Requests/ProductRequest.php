<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Xác định xem người dùng có quyền gửi yêu cầu này không.
     */
    public function authorize(): bool
    {
        // Nếu bạn không cần kiểm tra quyền, có thể return true để cho phép tất cả người dùng
        return true;
    }

    /**
     * Định nghĩa các quy tắc validation.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',  // Tên sản phẩm là bắt buộc, dạng chuỗi và tối đa 255 ký tự
            'category_id' => 'required|exists:categories,category_id',  // Category ID phải tồn tại trong bảng categories
            'description' => 'nullable|string',  // Mô tả sản phẩm là tùy chọn và có thể là chuỗi
            'unit' => 'required|string|max:1000',  // Đơn vị tính sản phẩm (cái, hộp,...)
            'quantity' => 'required|integer|min:0',  // Số lượng sản phẩm phải là một số nguyên và không nhỏ hơn 0
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif',  // Ảnh sản phẩm nếu có phải là một file ảnh (jpg, jpeg, png, gif)
            'price' => 'required|numeric|min:0',  // Giá sản phẩm phải là một số và không được âm
        ];
    }

    /**
     * Định nghĩa thông báo lỗi tùy chỉnh nếu có.
     */
    public function messages(): array
{
    return [
        'name.required'        => 'Tên sản phẩm là bắt buộc.',
        'name.string'          => 'Tên sản phẩm phải là chuỗi.',
        'name.max'             => 'Tên sản phẩm không được vượt quá 255 ký tự.',

        'category_id.required' => 'Danh mục là bắt buộc.',
        'category_id.exists'   => 'Danh mục không tồn tại.',

        'description.string'   => 'Mô tả phải là chuỗi.',

        'unit.required'        => 'Đơn vị tính là bắt buộc.',
        'unit.string'          => 'Đơn vị tính phải là chuỗi.',
        'unit.max'             => 'Đơn vị tính không được vượt quá 1000 ký tự.',

        'quantity.required'    => 'Số lượng là bắt buộc.',
        'quantity.integer'     => 'Số lượng phải là số nguyên.',
        'quantity.min'         => 'Số lượng không được nhỏ hơn 0.',

        'image.image'          => 'Tệp tải lên phải là ảnh.',
        'image.mimes'          => 'Ảnh phải có định dạng: jpg, jpeg, png, gif.',

        'price.required'       => 'Giá sản phẩm là bắt buộc.',
        'price.numeric'        => 'Giá phải là số.',
        'price.min'            => 'Giá không được nhỏ hơn 0.',
    ];
}

}
