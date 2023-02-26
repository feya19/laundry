<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    protected $fillable = [
        'outlets_id', 'pelanggan_id', 'no_invoice', 'deadline', 'subtotal', 'diskon', 'potongan', 'biaya_tambahan',
        'total', 'bayar', 'kembali', 'payment_date', 'note', 'users_id', 'updated_by'
    ];

    /** Relations */
    public function transaksiDetail(){
        return $this->hasMany(TransaksiDetail::class, 'transaksi_id', 'id');
    }

    public function transaksiStatus(){
        return $this->hasMany(TransaksiStatus::class, 'transaksi_id', 'id')->oldest();
    }

    public function latestStatus(){
        return $this->hasOne(TransaksiStatus::class, 'transaksi_id', 'id')->latestOfMany();
    }

    public function outlet(){
        return $this->belongsTo(Outlet::class, 'outlets_id', 'id');
    }

    public function pelanggan(){
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    /** Enums */

    public static function enumStatus($data = null){
        $result = [
            'queue' => 'Antre',
            'process' => 'Proses', 
            'done' => 'Selesai',
            'taken' => 'Diambil'
        ];
        if ($data) {
            return $result[$data];
        } else {
            return $result;
        }
    }
}
