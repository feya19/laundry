<?php
namespace App\Exports;

use App\Models\Outlet;
use App\Models\Transaksi;
use DragonCode\Support\Facades\Helpers\Arr;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TransaksiExport implements FromView
{
    public $startDate,$endDate,$users,$outlet;

    public function __construct($users, $outlet, $startDate, $endDate)
    {
        $this->users = $users;

        $this->outlet = $outlet;

        $this->startDate = now()->parse($startDate)->format('Y-m-d');

        $this->endDate = now()->parse($endDate)->format('Y-m-d');
    }

    public function query()
    {
        $users = $this->users;
        return Transaksi::with(['user', 'pelanggan', 'transaksiDetail.produk', 'transaksiStatus.user', 'latestStatus'])
            ->when($users, function($q) use ($users){
                $q->whereIn('users_id', $users);
            })
            ->where([['outlets_id', $this->outlet], ['created_at', '>=', $this->startDate.' 00:00:00'], ['created_at', '<=',$this->endDate.' 23:59:59']])
            ->orderBy('created_at')
            ->get();
    }

    public function view(): View
    {
        return view('report.excel', [
            'model' => $this->query(),
            'param' => [
                'user' => collect($this->users)->map(function($data){ return $data->username; })->implode(','),
                'outlet' => Outlet::find($this->outlet)->nama,
                'periode_awal' => $this->startDate,
                'periode_akhir' => $this->endDate
            ]
        ]);
    }
}