<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOutlet extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'user_outlet';
    protected $fillable = ['users_id', 'outlets_id'];

    /** Relations */
    public function outlet(){
        return $this->belongsTo(Outlet::class, 'outlets_id', 'id');
    }
}
