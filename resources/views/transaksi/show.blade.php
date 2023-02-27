@php use App\Library\Locale; @endphp
<div class="row">
    <div class="col-6">
        <table class="table">
            <tr>
                <td style="border-top: 0; width: 120px">Pelanggan</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ $model->pelanggan->nama }}</td>
            </tr>
            <tr>
                <td style="border-top: 0; width: 120px">No Invoice</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ $model->no_invoice }}</td>
            </tr>
            <tr>
                <td style="border-top: 0; width: 120px">Tanggal</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ Locale::humanDateTime($model->created_at) }}</td>
            </tr>
            <tr>
                <td style="border-top: 0; width: 120px">Batas Waktu</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ Locale::humanDateTime($model->deadline) }}</td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class="table">
            <tr>
                <td style="border-top: 0; width: 120px">Status</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ $model->status }}</td>
            </tr>
            <tr>
                <td style="border-top: 0; width: 120px">Dibuat Oleh</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ $model->user->username }}</td>
            </tr>
            @if($model->updated_by)
            <tr>
                <td style="border-top: 0; width: 120px">Diperbarui Oleh</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ $model->updated_by }}</td>
            </tr>
            @endif
        </table>
    </div>
</div>
<table class="table table-consoned table-bordered">
    <thead>
        <tr>
            <th>Produk</th>
            <th width="150">Harga</th>
            <th width="50">Jumlah</th>
            <th width="200">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($model->transaksiDetail as $transaksiProduk)
            <tr>
                <td>{{ $transaksiProduk->produk->nama }}</td>
                <td class="text-right">{{ Locale::numberFormat($transaksiProduk->harga) }}</td>
                <td class="text-right">{{ Locale::numberFormat($transaksiProduk->jumlah) }}</td>
                <td class="text-right">{{ Locale::numberFormat($transaksiProduk->total) }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3">Subtotal</th>
            <th class="text-right">{{ Locale::numberFormat($model->subtotal) }}</th>
        </tr>
    </tbody>
</table>
<div class="row">
    <div class="col-6">
        <table class="table">
            <tr>
                <td style="border-top: 0; width: 120px">Diskon</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ Locale::numberFormat($model->diskon) }}%</td>
            </tr>
            <tr>
                <td style="border-top: 0; width: 120px">Potongan</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ Locale::numberFormat($model->potongan) }}</td>
            </tr>
            <tr>
                <td style="border-top: 0; width: 120px">Biaya Tambahan</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ Locale::numberFormat($model->biaya_tambahan) }}</td>
            </tr>
            <tr>
                <td style="border-top: 0; width: 120px">Dibayar Pada</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ Locale::humanDateTime($model->transaksiStatus->sortByDesc('created_at')->firstWhere('status', 'taken')->created_at) ?? '' }}</td>
            </tr>
        </table>
    </div>
    <div class="col-6">
        <table class="table">
            <tr>
                <td style="border-top: 0; width: 120px">Total</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ Locale::numberFormat($model->total) }}</td>
            </tr>
            <tr>
                <td style="border-top: 0; width: 120px">Bayar</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ Locale::numberFormat($model->bayar) }}</td>
            </tr>
            <tr>
                <td style="border-top: 0; width: 120px">Kembali</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{{ Locale::numberFormat($model->kembali) }}</td>
            </tr>
            <tr>
                <td style="border-top: 0; width: 120px">Lunas</td>
                <td style="border-top: 0; width: 1px">:</td>
                <td style="border-top: 0;">{!! Locale::boolean($model->bayar >= $model->total) !!}</td>
            </tr>
        </table>
    </div>
</div>
<div class="accordion" id="accordion2">
    <div class="card">
        <div class="card-header collapsed" data-toggle="collapse" data-target="#accordion2-collapse2" aria-expanded="false">
            <h3 class="card-title">Riwayat Status</h3>
            <div class="card-addon">
                <i class="caret accordion-caret"></i>
            </div>
        </div>
        <div id="accordion2-collapse2" class="collapse" data-parent="#accordion2" style="">
            <div class="card-body">
                <div class="timeline timeline-timed">
                @foreach ($model->transaksiStatus as $status)
                    @php
                    switch($status->status):
                        case 'queue':
                            $class = 'marker marker-circle text-secondary';
                            break;
                        case 'process':
                            $class = 'marker marker-circle text-info';
                            break;
                        case 'done':
                            $class = 'marker marker-circle text-primary';
                            break;
                        default:
                            $class = 'marker marker-circle text-success';
                            break;
                    endswitch;
                    @endphp
                    <div class="timeline-item">
                        <span class="timeline-time p-0" style="font-size: 1rem !important;">{{App\Models\Transaksi::enumStatus($status->status)}}</span>
                        <div class="timeline-pin">
                            <i class="{{$class}}"></i>
                        </div>
                        <div class="timeline-content">
                            <p class="mb-0">[ {{Locale::humanDateTime($status->created_at)}} ] Diubah Oleh {{$status->user->username}}</p>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>