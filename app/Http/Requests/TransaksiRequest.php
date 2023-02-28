<?php

namespace App\Http\Requests;

use App\Library\Locale;
use Illuminate\Foundation\Http\FormRequest;

class TransaksiRequest extends FormRequest
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
        $merge = [
            'subtotal' => Locale::numberValue($this->subtotal),
            'diskon' => Locale::numberValue($this->diskon),
            'potongan' => Locale::numberValue($this->potongan),
            'biaya_tambahan' => Locale::numberValue($this->biaya_tambahan),
            'total' => Locale::numberValue($this->total),
            'bayar' => Locale::numberValue($this->bayar),
            'kembali' => Locale::numberValue($this->kembali)
        ];
        if($produk = $this->produk){
            foreach ($produk as $key => $data):
                if($data['produks_id']):
                    $merge['produk'][$key]['produks_id'] = $data['produks_id'];
                    $merge['produk'][$key]['nama'] = $data['nama'];
                    $merge['produk'][$key]['harga'] = Locale::numberValue($data['harga']);
                    $merge['produk'][$key]['jumlah'] = Locale::numberValue($data['jumlah']);
                    $merge['produk'][$key]['total'] = Locale::numberValue($data['total']);
                endif;
            endforeach;
        }
        $this->merge($merge);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'pelanggan_id' => 'required',
            'status' => 'required',
            'batas_waktu' => 'required|date_format:Y-m-d H:i'.($this->transaksi ? '' : '|after_or_equal:'.now()),
            'produk.*.produks_id' => 'required',
            'produk.*.harga' => 'required|numeric|gte:0',
            'produk.*.jumlah' => 'required|numeric|gte:1',
            'produk.*.total' => 'required|numeric|gte:0',
            'subtotal' => 'required|numeric|gte:0',
            'diskon' => 'numeric|gte:0|max:100',
            'potongan' => 'numeric|gte:0|max:'.$this->subtotal,
            'biaya_tambahan' => 'numeric|gte:0',
            'total' => 'numeric|gte:0',
            'bayar' => 'numeric|gte:'.($this->status == 'taken' ? $this->total : 0),
            'kembali' => 'numeric|gte:0',
        ];
    }

    public function attributes(): array
    {
        $attr = ['pelanggan_id' => 'Pelanggan', 'note' => 'Catatan'];
        if($this->produk):
            foreach($this->produk as $key => $val):
                $attr['produk.'.$key.'.produks_id'] = 'Produk '. ($val['nama'] ?? 'Ke-'.$key+1);
                $attr['produk.'.$key.'.harga'] = 'Produk '. ($val['nama'] ?? 'Ke-'.$key+1) .' Harga';
                $attr['produk.'.$key.'.jumlah'] = 'Produk '. ($val['nama'] ?? 'Ke-'.$key+1) .' Jumlah';
                $attr['produk.'.$key.'.total'] = 'Produk '. ($val['nama'] ?? 'Ke-'.$key+1) .' Total';
            endforeach;
        endif;
        return $attr;
    }

    public function messages()
    {
        return [
            'batas_waktu.after_or_equal' => 'Batas waktu harus berisi tanggal dan waktu setelah atau sama dengan saat ini.'
        ];
    }
}
