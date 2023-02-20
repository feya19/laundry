<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OutletRequest extends FormRequest
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
            'nama' => 'required|max:64|unique:outlets,nama,'.$this->outlet,
            'alamat' => 'required|min:15|max:255',
            'telepon' => 'required|unique:outlets,telepon,'.$this->outlet
        ];
    }
}