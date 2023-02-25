<?php

namespace App\Http\Requests;

use App\Models\Outlet;
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
        $outlet = Outlet::pluck('id')->implode(',');
        $rules = [
            'username' => 'required|max:64|string|unique:users,username,'.$this->users,
            'nama' => 'required|string|max:255',
            'role' => 'required|in:admin,kasir,owner',
            'file' => 'max:2048|mimes:jpg,jpeg,png',
            'user_outlet.*' => 'in:'.$outlet,
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

    public function attributes(): array
    {
        return [
            'username' => 'Username',
            'role' => 'Role',
            'password' => 'Password',
            'konfirmasi_password' => 'Konfirmasi Password',
        ];
    }
}
