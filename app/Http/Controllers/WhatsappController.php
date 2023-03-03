<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappController extends Controller
{
    protected $pelanggan,$v1,$v2,$token;

    public function checkDevice()
    {
        $this->v1 = env('API_V1');
        $this->v2 = env('API_V2');
        $this->token = env('API_KEY');
        $response = Http::get($this->v1.'/device/info?token='.$this->token);
        $res = json_decode($response->getBody());
        if($res->data->status == 'connected' && $res->data->quota > 0 && $res->data->expired_date >= date('Y-m-d')){
            return ['success' => true];
        }else{
            return ['success' => false, 'message' => 'Device Expired'];
        }
    }


    public function transaksiTextSend($transaksi){
        $check = $this->checkDevice();
        if(!$check['success']) return $check;
        $phone = substr($transaksi->pelanggan->telepon, 1);
        $message = 'Outlet '.$transaksi->outlet->nama.'. Transaksi anda '.$transaksi->no_invoice.' sudah siap diambil.';
        $response = Http::get($this->v1.'/send-message?phone='.$phone.'&message='.$message.'&token='.$this->token);
        return json_decode($response);
    }
}
