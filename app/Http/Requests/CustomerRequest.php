<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'nullable|uuid',
            'name'        => 'required|string|max:255',
            'phone'       => 'nullable|regex:/^\+?[0-9]{8,20}$/',
            'email'       => $this->isMethod('post') // Nếu là POST (create)
                ? 'nullable|email|max:255|unique:customers,email'
                : 'required|email|max:255', // Nếu là PUT (update), chỉ kiểm tra định dạng và không cho null
            'address'     => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên khách hàng là bắt buộc.',
            'email.email'   => 'Email không đúng định dạng.',
            'email.unique'  => 'Email đã tồn tại.',
            'phone.regex' => 'Số điện thoại không hợp lệ. Chỉ cho phép số và tối đa 20 ký tự.',
        ];
    }
}
