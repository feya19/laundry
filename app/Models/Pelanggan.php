<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;
    protected $table = 'pelanggan';
    protected $fillable = ['nama', 'alamat', 'jenis_kelamin', 'telepon','created_by', 'updated_by'];

    public static function enumJenisKelamin($data = null){
        $result = [
			'L' => 'Laki-Laki',
			'P' => 'Perempuan',
        ];
        if ($data) {
            return $result[$data];
        } else {
            return $result;
        }
    }
}
