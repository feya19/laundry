<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules = [
            'username' => 'required|max:64|string|unique:users,username,'.$this->users,
            'nama' => 'required|string|max:255',
            'role' => 'required|in:admin,kasir,owner',
            'file' => 'max:2048|mimes:jpg,jpeg,png',
            'password' => 'required|min:6',
            'konfirmasi_password' => 'required|same:password|min:6'
        ];
        if($this->users){
            if($this->password || $this->konfirmasi_password){
                $rules['password'] = 'min:6';
                $rules['konfirmasi_password'] = 'same:password|min:6';
            }else{
                unset($rules['password'], $rules['konfirmasi_password']);
            }
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.max' => 'Username maksimal berisi :max karakter.',
            'username.unique' => 'Username sudah ada sebelumnya.',
            'role.required' => 'Role wajib diisi.',
            'role.in' => 'Role yang dipilih tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password maksimal berisi :min karakter.',
            'konfirmasi_password.same' => 'Konfirmasi Password Dan Password Harus Sama',
        ];
    }
}
