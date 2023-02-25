<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    
    /** Relations */
    
    public function userOutlet(){
        return $this->hasMany(UserOutlet::class, 'users_id', 'id');
    }

    /** Enums */
    public static function enumRole($data = null){
        $result = [
            'admin' => 'Admin',
            'kasir' => 'Kasir',
            'owner' => 'Owner'
        ];
        if ($data) {
            return $result[$data]; 
        } else {
            return $result;
        }
    }
}
