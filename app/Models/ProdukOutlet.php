<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdukOutlet extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'produk_outlet';

    public function outlet(){
        return $this->belongsTo(Outlet::class,'outlets_id','id');
    }
}
