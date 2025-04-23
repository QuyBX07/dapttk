<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
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
            'query'=>'required|string|max:255'
            //
        ];
    }

    public function messages()
    {
        return [
            'query.required' => 'Vui lòng nhập từ khóa tìm kiếm.',
            'query.string' => 'Từ khóa phải là chuỗi ký tự.',
            'query.min' => 'Từ khóa phải có ít nhất 1 ký tự.',
        ];
    }
}
