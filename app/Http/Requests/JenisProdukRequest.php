<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JenisProdukRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'jenis' => 'required|max:64|unique:jenis_produks,jenis,'.$this->jenis_produk,
        ];
    }
}