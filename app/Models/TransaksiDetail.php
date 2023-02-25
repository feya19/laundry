<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;
    protected $table = 'transaksi_detail';

    /** Relations */
    public function produk(){
        return $this->belongsTo(Produk::class, 'produks_id', 'id');
    }
}
