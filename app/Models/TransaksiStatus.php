<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiStatus extends Model
{
    use HasFactory;
    protected $table = 'transaksi_status';
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}
