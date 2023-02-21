<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PelangganRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'telepon' => '+'.(string)$this->telepon
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|max:64|unique:pelanggan,nama,'.$this->pelanggan,
            'alamat' => 'required|min:15|max:255',
            'jenis_kelamin' => 'required|in:L,P',
            'telepon' => 'required|string|min:11|max:16|unique:pelanggan,telepon,'.$this->pelanggan
        ];
    }

    public function messages(): array
    {
        return [
            'telepon.min' => 'Telepon minimal berisi 10 karakter.',
            'telepon.max' => 'Telepon maksimal berisi 15 karakter.'
        ];
    }
}
