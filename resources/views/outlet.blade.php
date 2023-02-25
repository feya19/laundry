@extends('layouts.app')
@php
    $sidebar = false;
    $header = false;
@endphp
@section('content')
    <div class="portlet align-items-center">
        <div class="portlet-header">
            <h2>Pilih Outlet</h2>
        </div>
        <div class="portlet-body">
            @php
            $html = '';
            if(!count($outlets)){
                $html .= '<div class="text-center">
                            <h2>Anda Tidak Memiliki Hak Akses Ke Outlet</h2>
                            <h4>Minta Admin Untuk Menambahkan</h4>
                        </div>';
            }else{
                foreach ($outlets as $key => $outlet){
                    $html .= '<button type="button" onclick="window.location.href=\''.route('setOutlet', ['id' => $outlet['id'], 'previous' => $previous]).'\';" class="btn btn-lg m-2 btn-primary text-left">
                                <i class="fa fa-store"></i><span class="pl-2">'.$outlet['nama'].'</span>
                            </button>';
                    $key != 0 && $key % 3 == 0 && $html .= '<br>';
                }
            }
            echo $html;
            @endphp
        </div>
    </div>
@endsection