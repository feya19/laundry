<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukJenis extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'produk_jenis';

    public function jenis(){
        return $this->belongsTo(JenisProduk::class, 'jenis_produks_id','id');
    }
}
