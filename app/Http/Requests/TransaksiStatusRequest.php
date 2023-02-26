<?php

namespace App\Http\Requests;

use App\Library\Locale;
use Illuminate\Foundation\Http\FormRequest;

class TransaksiStatusRequest extends FormRequest
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
        if ($this->status == 'taken') {
            $this->merge([
                'subtotal' => Locale::numberValue($this->subtotal),
                'diskon' => Locale::numberValue($this->diskon),
                'potongan' => Locale::numberValue($this->potongan),
                'biaya_tambahan' => Locale::numberValue($this->biaya_tambahan),
                'total' => Locale::numberValue($this->total),
                'bayar' => Locale::numberValue($this->bayar),
                'kembali' => Locale::numberValue($this->kembali)
            ]);
        }
        
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $rules = ['status' => 'not_in:'.$this->lastStatus];
        if ($this->status == 'taken') {
            $rules = array_merge($rules, [
                'subtotal' => 'required|numeric|gte:0',
                'diskon' => 'numeric|gte:0|max:100',
                'potongan' => 'numeric|gte:0|max:'.$this->subtotal,
                'biaya_tambahan' => 'numeric|gte:0',
                'total' => 'numeric|gte:0',
                'bayar' => 'numeric|gte:'.$this->total,
                'kembali' => 'numeric|gte:0'
            ]);
        }
        return $rules;
    }

    public function attributes(): array
    {
        return [
            'total' => 'Total Biaya',
        ];
    }
}
