@php
    use App\Library\Locale;
@endphp
@extends('layouts.app')
@section('content')
<div class="card">
    <div class="card-header">Dashboard Bulan {{Locale::listMonth('month_' . date('m', strtotime(now())));}}</div>
    <div class="card-body">
        @if (isset($data))
        <div class="row portlet-row-fill-sm">
            <div class="col-md-6">
                <div class="portlet portlet-primary">
                    <div class="portlet-header">
                        <h3 class="portlet-title">Total Transaksi Hari ini</h3>
                    </div>
                    <div class="portlet-body widget3 widget3-sm">
                        <div class="widget3-display justify-content-end">
                            <h3 class="widget3-title text-white">{{Locale::numberFormat($data['transaksi'])}} <sub class="widget3-subtitle"></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3 class="portlet-title">Total Dibayarkan Hari ini</h3>
                    </div>
                    <div class="portlet-body widget3 widget3-sm">
                        <div class="widget3-display justify-content-end">
                            <h3 class="widget3-title">{{Locale::numberFormat($data['pembayaran'])}} <sub class="widget3-subtitle"></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portlet">
            <!-- BEGIN Widget -->
            <div class="widget10 widget10-vertical-md">
                <div class="widget10-item">
                    <div class="widget10-content">
                        <h2 class="widget10-title">{{$data['queue']}}</h2>
                        <span class="widget10-subtitle">Antrian</span>
                    </div>
                    <div class="widget10-addon">
                        <!-- BEGIN Avatar -->
                        <div class="avatar avatar-label-info avatar-circle widget8-avatar m-0">
                            <div class="avatar-display">
                                <i class="fa fa-dollar-sign"></i>
                            </div>
                        </div>
                        <!-- END Avatar -->
                    </div>
                </div>
                <div class="widget10-item">
                    <div class="widget10-content">
                        <h2 class="widget10-title">{{$data['process']}}</h2>
                        <span class="widget10-subtitle">Diproses</span>
                    </div>
                    <div class="widget10-addon">
                        <!-- BEGIN Avatar -->
                        <div class="avatar avatar-label-primary avatar-circle widget8-avatar m-0">
                            <div class="avatar-display">
                                <i class="fa fa-boxes"></i>
                            </div>
                        </div>
                        <!-- END Avatar -->
                    </div>
                </div>
                <div class="widget10-item">
                    <div class="widget10-content">
                        <h2 class="widget10-title">{{$data['done']}}</h2>
                        <span class="widget10-subtitle">Selesai</span>
                    </div>
                    <div class="widget10-addon">
                        <!-- BEGIN Avatar -->
                        <div class="avatar avatar-label-success avatar-circle widget8-avatar m-0">
                            <div class="avatar-display">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>
                        <!-- END Avatar -->
                    </div>
                </div>
                <div class="widget10-item">
                    <div class="widget10-content">
                        <h2 class="widget10-title">{{$data['taken']}}</h2>
                        <span class="widget10-subtitle">Diambil</span>
                    </div>
                    <div class="widget10-addon">
                        <!-- BEGIN Avatar -->
                        <div class="avatar avatar-label-danger avatar-circle widget8-avatar m-0">
                            <div class="avatar-display">
                                <i class="fa fa-link"></i>
                            </div>
                        </div>
                        <!-- END Avatar -->
                    </div>
                </div>
            </div>
            <!-- END Widget -->
        </div>
        @else
            <div class="text-center">
                {!! Form::label('pilihOutlet', "Untuk Menampilkan Data Dashboard Silakan ", ['class' => 'mb-0']) !!}
                {!! link_to_route('selectOutlet', $title = 'Pilih Outlet', $parameters = [], $attributes = ['class' => 'btn btn-primary', 'id' => 'pilihOutlet']); !!}
            </div>
        @endif
    </div>
</div>
@endsection
