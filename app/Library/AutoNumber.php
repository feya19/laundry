<?php 
namespace App\Library;

use Illuminate\Support\Facades\DB;

class AutoNumber {
    
    public static function generate($model = '', $field = '', $format) {
        $parse = explode(':', $format);
        $prefix = str_replace(array('{Y}', '{m}', '{d}'), array(date('Y'), date('m'), date('d')), $parse[0]);
        $digit = str_repeat('0', $parse[1]);
        $lastId = DB::table($model)->select(DB::raw("(select max(`id`) from ".$model.") AS last_id"))->first();
        if ($lastId) {
            $counter = substr($lastId->last_id, -strlen($digit)) + 1;
            return $prefix.substr($digit.$counter, -strlen($digit));
        } else {
            return $prefix.substr($digit.'1', -strlen($digit));
        }
    }
}
?>