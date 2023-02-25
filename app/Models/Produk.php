<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'produks';
    protected $fillable = ['nama', 'harga', 'created_by', 'updated_by'];

    /** Relations */
    public function produkJenis(){
        return $this->hasMany(ProdukJenis::class,'produks_id','id');
    }

    public function produkOutlet(){
        return $this->hasMany(ProdukOutlet::class,'produks_id','id');
    }
}
