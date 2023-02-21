<?php

namespace App\Http\Requests;

use DragonCode\Support\Facades\Helpers\Arr;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'oldpassword'           => 'required',
            'newpassword'           => 'required',
            'confirmed'             => 'required|same:newpassword'
        ];
    }

    public function messages(): array
    {
        return [
            'oldpassword.required'        => 'Password Lama Harus Diisi',
            'newpassword.required'        => 'Password Baru Harus Diisi',
            'confirmed.required'          => 'Konfirmasi Password Harus Diisi',
            'confirmed.same'              => 'Konfirmasi Password Baru Tidak Sama Dengan Password Baru'
        ];
    }
}
