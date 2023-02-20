<?php

namespace App\Http\Requests;

use App\Library\Locale;
use Illuminate\Foundation\Http\FormRequest;

class ProdukRequest extends FormRequest
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


    public function prepareForValidation(): void
    {
        $this->merge([
            'harga' => Locale::numberValue($this->harga) ?? $this->harga
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|max:64|unique:produks,nama,'.$this->produk,
            'jenis_produk' => 'required',
            'produk_outlet' => 'required',
            'harga' => 'required|numeric|gte:0'
        ];
    }
}