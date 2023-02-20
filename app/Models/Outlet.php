<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $fillable = ['nama', 'alamat', 'telepon', 'created_by', 'updated_by'];
}
