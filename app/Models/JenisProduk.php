<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisProduk extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = ['jenis', 'created_by', 'updated_by'];
}
