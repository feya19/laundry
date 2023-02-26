@php 
use App\Library\Locale;
use App\Models\Transaksi;
@endphp
<table>    
    <thead>
        <tr>
            <td colspan="8">Laporan Transaksi {{ $param['outlet'] }}</td>
        </tr>
        <tr>
            <td colspan="2">Waktu Export</td>
            <td colspan="6">: {{ Locale::humanDateTime(now()) }}</td>
        </tr>
        @if ($param['user'])   
        <tr>
            <td colspan="2">User</td>
            <td colspan="6">: {{ $param['user'] }}</td>
        </tr>
        @endif
        <tr>
            <td colspan="2">Periode Awal</td>
            <td colspan="6">: {{ Locale::humanDate($param['periode_awal']) }}</td>
        </tr>
        <tr>
            <td colspan="2">Periode Akhir</td>
            <td colspan="6">: {{ Locale::humanDate($param['periode_akhir']) }}</td>
        </tr>
        <tr>
            <td colspan="8">&nbsp;</td>
        </tr>
        <tr>
            <td align="center" style="vertical-align: center;width: 15px;">No</td>
            <td align="center" style="vertical-align: center;width: 50px;">Tanggal</td>
            <td align="center" style="vertical-align: center;width: 50px;">No Invoice</td>
            <td align="center" style="vertical-align: center;width: 50px;">Pelanggan</td>
            <td align="center" style="vertical-align: center;width: 50px;">Batas Waktu</td>
            <td align="center" style="vertical-align: center;width: 50px;">Status</td>
            <td align="center" style="vertical-align: center;width: 50px;">Dibuat Oleh</td>
            <td align="center" style="vertical-align: center;width: 50px;">Total</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($model as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ Locale::humanDateTime($item->created_at) }}</td>
                <td>{{ $item->no_invoice }}</td>
                <td>{{ $item->pelanggan->nama }}</td>
                <td>{{ Locale::humanDateTime($item->deadline) }}</td>
                <td>{{ Transaksi::enumStatus($item->latestStatus->status) }}</td>
                <td>{{ $item->user->username }}</td>
                <td>{{ Locale::numberFormat($item->total) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>