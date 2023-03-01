@php
    use App\Library\Locale;
@endphp
@extends('layouts.app')
@section('content')
    @if (isset($model))
        <div class="row portlet-row-fill-sm">
            <div class="col-md-6">
                <div class="portlet portlet-primary">
                    <div class="portlet-header">
                        <h3 class="portlet-title">Total Dibayarkan Hari ini</h3>
                    </div>
                    <div class="portlet-body widget3 widget3-sm">
                        <div class="widget3-display justify-content-end">
                            <h3 class="widget3-title text-white">{{Locale::numberFormat($model->pembayaran)}} <sub class="widget3-subtitle"></h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="portlet">
                    <div class="portlet-header">
                        <h3 class="portlet-title">Total Transaksi Hari ini</h3>
                    </div>
                    <div class="portlet-body widget3 widget3-sm">
                        <div class="widget3-display justify-content-end">
                            <h3 class="widget3-title">{{Locale::numberFormat($model->transaksi)}} <sub class="widget3-subtitle"></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="portlet">
            <div class="portlet-header dashboard-status-header">
                <div class="portlet-title">
                    Status Transaksi Periode {{date('Y')}}
                </div>
                <div class="portlet-addon">
                    <!-- BEGIN Dropdown -->
                    <div class="dropdown">
                        <button class="btn btn-label-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false" id="buttonMonth">{{Locale::month(date('m'))}}</button>
                        <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(100px, 29px, 0px);">
                            @foreach (Locale::month() as $key => $item)
                            <a class="dropdown-item" href="javascript:filterDashboard('{{$key}}', '{{$item}}');">
                                <span class="dropdown-content">{{$item}}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>
                    <!-- END Dropdown -->
                </div>
            </div>
            <!-- BEGIN Widget -->
            <div class="widget10 widget10-vertical-md">
                <div class="widget10-item">
                    <div class="widget10-content">
                        <h3 class="widget10-title" id="queue">...</h3>
                        <span class="widget10-subtitle">Antrian</span>
                    </div>
                    <div class="widget10-addon">
                        <!-- BEGIN Avatar -->
                        <div class="avatar avatar-label-secondary avatar-circle widget8-avatar m-0">
                            <div class="avatar-display">
                                <i class="fa fa-clipboard-list"></i>
                            </div>
                        </div>
                        <!-- END Avatar -->
                    </div>
                </div>
                <div class="widget10-item">
                    <div class="widget10-content">
                        <h3 class="widget10-title" id="process">...</h3>
                        <span class="widget10-subtitle">Diproses</span>
                    </div>
                    <div class="widget10-addon">
                        <!-- BEGIN Avatar -->
                        <div class="avatar avatar-label-info avatar-circle widget8-avatar m-0">
                            <div class="avatar-display">
                                <i class="fa fa-spinner"></i>
                            </div>
                        </div>
                        <!-- END Avatar -->
                    </div>
                </div>
                <div class="widget10-item">
                    <div class="widget10-content">
                        <h3 class="widget10-title" id="done">...</h3>
                        <span class="widget10-subtitle">Selesai</span>
                    </div>
                    <div class="widget10-addon">
                        <!-- BEGIN Avatar -->
                        <div class="avatar avatar-label-primary avatar-circle widget8-avatar m-0">
                            <div class="avatar-display">
                                <i class="fa fa-clipboard-check"></i>
                            </div>
                        </div>
                        <!-- END Avatar -->
                    </div>
                </div>
                <div class="widget10-item">
                    <div class="widget10-content">
                        <h3 class="widget10-title" id="taken">...</h3>
                        <span class="widget10-subtitle">Diambil</span>
                    </div>
                    <div class="widget10-addon">
                        <!-- BEGIN Avatar -->
                        <div class="avatar avatar-label-success avatar-circle widget8-avatar m-0">
                            <div class="avatar-display">
                                <i class="fa fa-user-check"></i>
                            </div>
                        </div>
                        <!-- END Avatar -->
                    </div>
                </div>
                <div class="widget10-item">
                    <div class="widget10-content">
                        <h3 class="widget10-title" id="overdue">...</h3>
                        <span class="widget10-subtitle">Over Due</span>
                    </div>
                    <div class="widget10-addon">
                        <!-- BEGIN Avatar -->
                        <div class="avatar avatar-label-danger avatar-circle widget8-avatar m-0">
                            <div class="avatar-display">
                                <i class="fa fa-clock"></i>
                            </div>
                        </div>
                        <!-- END Avatar -->
                    </div>
                </div>
            </div>
            <!-- END Widget -->
        </div>
        <div class="row portlet-row-fill-md">
            <div class="col-md-4 col-xl-12">
                <!-- BEGIN Portlet -->
                <div class="portlet">
                    <div class="portlet-body">
                        <!-- BEGIN Widget -->
                        <div class="widget10-item p-0">
                            <div class="widget10-content">
                                <h2 class="widget10-title" id="pembayaran">...</h2>
                                <span class="widget10-subtitle">Total Transaksi</span>
                            </div>
                            <div class="widget10-addon">
                                <!-- BEGIN Avatar -->
                                <div class="avatar avatar-label-primary avatar-circle widget8-avatar m-0">
                                    <div class="avatar-display">
                                        <i class="fa fa-coins"></i>
                                    </div>
                                </div>
                                <!-- END Avatar -->
                            </div>
                        </div>
                        <!-- END Widget -->
                    </div>
                    <!-- BEGIN Chart -->
                    <div id="chartPembayaran" data-chart-identifier="pembayaran"  class="widget11 widget11-bottom widget-chart-7" data-chart-color="#2196f3" data-chart-label="Total Transaksi" data-chart-series=""></div>
                    <!-- END Chart -->
                </div>
                <!-- END Portlet -->
            </div>
            <div class="col-md-4 col-xl-12">
                <!-- BEGIN Portlet -->
                <div class="portlet">
                    <div class="portlet-body">
                        <!-- BEGIN Widget -->
                        <div class="widget10-item p-0">
                            <div class="widget10-content">
                                <h2 class="widget10-title" id="transaksi">...</h2>
                                <span class="widget10-subtitle">Jumlah Transaksi</span>
                            </div>
                            <div class="widget10-addon">
                                <!-- BEGIN Avatar -->
                                <div class="avatar avatar-label-success avatar-circle widget8-avatar m-0">
                                    <div class="avatar-display">
                                        <i class="fa fa-boxes"></i>
                                    </div>
                                </div>
                                <!-- END Avatar -->
                            </div>
                        </div>
                        <!-- END Widget -->
                    </div>
                    <!-- BEGIN Chart -->
                    <div id="chartTransaksi" data-chart-identifier="transaksi" class="widget11 widget11-bottom widget-chart-7" data-chart-color="#4caf50" data-chart-label="Transaksi" data-chart-currency="false" data-chart-series=""></div>
                    <!-- END Chart -->
                </div>
                <!-- END Portlet -->
            </div>
            <div class="col-md-4 col-xl-12">
                <div class="portlet">
                    <div class="portlet-body">
                        <!-- BEGIN Widget -->
                        <div class="widget10-item p-0">
                            <div class="widget10-content">
                                <h2 class="widget10-title" id="pajak">...</h2>
                                <span class="widget10-subtitle">Total Pajak</span>
                            </div>
                            <div class="widget10-addon">
                                <!-- BEGIN Avatar -->
                                <div class="avatar avatar-label-warning avatar-circle widget8-avatar m-0">
                                    <div class="avatar-display">
                                        <i class="fa fa-dollar-sign"></i>
                                    </div>
                                </div>
                                <!-- END Avatar -->
                            </div>
                        </div>
                        <!-- END Widget -->
                    </div>
                    <!-- BEGIN Chart -->
                    <div id="chartPajak" data-chart-identifier="pajak"  class="widget11 widget11-bottom widget-chart-7" data-chart-color="#ffe715" data-chart-label="Total Pajak" data-chart-series=""></div>
                    <!-- END Chart -->
                </div>
            </div>
        </div>
        <div class="portlet portlet-primary">
            <div class="portlet-header">
                <div class="portlet-icon">
                    <i class="fa fa-users"></i>
                </div>
                <h3 class="portlet-title">Top 5 Pelanggan Teraktif</h3>
            </div>
            <div class="portlet-body" id="top-pelanggan">
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-header">Dashboard</div>
            <div class="card-body">
                <div class="text-center">
                    {!! Form::label('pilihOutlet', "Untuk Menampilkan Data Dashboard Silakan ", ['class' => 'mb-0']) !!}
                    {!! link_to_route('selectOutlet', $title = 'Pilih Outlet', $parameters = [], $attributes = ['class' => 'btn btn-primary', 'id' => 'pilihOutlet']); !!}
                </div>
            </div>
        </div>
        @endif
@endsection
@push('script')
    <script>
        var month = '{{date('m')}}';
        var text;
        $(setTimeout(() => {
            filterDashboard(month);
        }), 100);

        function filterDashboard(param, text = ''){
            month = param;
            text != '' && $('#buttonMonth').text(text);
            axios.get('{{route('statusTransaksi', ['outlet' => session('outlets_id')])}}/'+month)
            .then((responses)=>{
                var response = responses.data.data;
                $.each(response, (i, v) => {
                    console.log(i)
                    if(i != 'chart'){
                        var val = (i == 'pembayaran' || i == 'pajak' ? localization.number(v) : (i == 'transaksi' ? localization.number(v, 0) : v ));
                        $(`#${i}`).text(val);
                    }
                    if(i == 'pelanggan'){
                        var html = '';
                        $.each(v, function(index, value){
                            html += `<div class="portlet mb-2">
                                        <div class="portlet-body">
                                            <!-- BEGIN Widget -->
                                            <div class="widget5">
                                                <h3 class="widget5-title">${value.nama}</h3>
                                                <div class="widget5-group">
                                                    <div class="widget5-item">
                                                        <span class="widget5-info">Jumlah Transaksi</span>
                                                        <span class="widget5-value">${value.transaksi}</span>
                                                    </div>
                                                    <div class="widget5-item">
                                                        <span class="widget5-info">Total Transaksi</span>
                                                        <span class="widget5-value text-success">${localization.number(value.total)}</span>
                                                    </div>
                                                    <div class="widget5-item">
                                                        <span class="widget5-info">Total Dibayarkan</span>
                                                        <span class="widget5-value text-success">${localization.number(value.pembayaran)}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END Widget -->
                                        </div>
                                    </div>`;
                        });
                        $('#top-pelanggan').html(html)
                    }
                })
                $('#chartPembayaran,#chartTransaksi,#chartPajak').html('');
                $('#chartPembayaran').data('chart-series', response.chart.pembayaran);
                $('#chartTransaksi').data('chart-series', response.chart.transaksi);
                $('#chartPajak').data('chart-series', response.chart.pajak);
                var months = response.chart.month.split(',')
                var colors={blue:"#2196f3",green:"#4caf50", yellow: "#ffe715",white:"#fff",black:"#424242"}
                var themeOptions={light:{theme:{mode:"light",palette:"palette1"}},dark:{theme:{mode:"dark",palette:"palette1"}}};
                var isDarkDefault=localStorage.getItem("theme-variant")=="dark"
                var themeVariantDefault=isDarkDefault?"dark":"light"
                function ownKeys(object,enumerableOnly){var keys=Object.keys(object);if(Object.getOwnPropertySymbols){var symbols=Object.getOwnPropertySymbols(object);if(enumerableOnly){symbols=symbols.filter(function(sym){return Object.getOwnPropertyDescriptor(object,sym).enumerable})}keys.push.apply(keys,symbols)}return keys}function _objectSpread(target){for(var i=1;i<arguments.length;i++){var source=arguments[i]!=null?arguments[i]:{};if(i%2){ownKeys(Object(source),true).forEach(function(key){_defineProperty(target,key,source[key])})}else if(Object.getOwnPropertyDescriptors){Object.defineProperties(target,Object.getOwnPropertyDescriptors(source))}else{ownKeys(Object(source)).forEach(function(key){Object.defineProperty(target,key,Object.getOwnPropertyDescriptor(source,key))})}}return target}function _defineProperty(obj,key,value){if(key in obj){Object.defineProperty(obj,key,{value:value,enumerable:true,configurable:true,writable:true})}else{obj[key]=value}return obj}
                var chart = $(".widget-chart-7").map(function(){
                    var color = $(this).data("chart-color");
                    var label = $(this).data("chart-label");
                    var id_chart = $(this).data("chart-identifier");
                    var series= $(this).data("chart-series").split(",").map(function(data){
                        return Number(data);
                    });
                    return new ApexCharts(this,_objectSpread(_objectSpread({},themeOptions[themeVariantDefault]),{},{
                        series:[{
                            name:label,
                            data:series
                        }],
                        chart:{
                            type:"area",
                            height:200,
                            background:"transparent",
                            sparkline:{enabled:true},
                        },
                        noData: {
                            text: 'No Data Available'
                        },
                        fill:{type:"solid",colors:[color],opacity:.1},
                        stroke:{show:true,colors:[color]},
                        markers:{colors:isDarkDefault?colors.black:colors.white,strokeWidth:4,strokeColors:color},
                        tooltip:{marker:{show:false},
                        y:{formatter:(val) => {return (id_chart == 'pembayaran' || id_chart == 'pajak' ? localization.number(val) : val/150000)}}},
                        xaxis:{categories:months,
                        crosshairs:{show:false}}}))
                });
                chart.each(function(){this.render()})
            })
        }
    </script>
@endpush
