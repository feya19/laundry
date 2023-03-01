<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Library\Locale;
use App\Models\Outlet;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ReportController extends Controller
{
    public function index(): View
    {
        $title = 'Laporan';
        $status = Transaksi::enumStatus();
        return view('report.index', compact(['title', 'status']));
    }

    public function download(Request $request){
        $request->validate([
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal|before_or_equal:'.date('Y-m-d', strtotime($request->periode_awal.' +3months')),
            'status' => $request->status ? 'in:queue,process,done,taken' : ''
        ]);
        $post = $request->all();
        $startDate = date('Y-m-d', strtotime($post['periode_awal']));
        $endDate = date('Y-m-d', strtotime($post['periode_akhir']));
        $outlet = Outlet::find(session('outlets_id'));
        $file_name = [];
        $styleHeader = [
            'fill' => array(
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => array('argb' => 'FF008080')
            ),
            'font' => [
            	'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => array('argb' => 'FFffffff'),
            ],
        ];
       

        if ($outlet) {
            $file_name[] = $outlet->nama;
        }
        if ($post['status']) {
            $file_name[] = Transaksi::enumStatus($post['status']);
        }
        $model = Transaksi::with(['user', 'pelanggan', 'transaksiDetail.produk', 'transaksiStatus.user', 'latestStatus'])
                ->when($request->has('user'), function($q) use ($post){
                    $q->whereIn('users_id', $post['user']);
                })
                ->where([['outlets_id', $outlet->id], ['created_at', '>=', $startDate.' 00:00:00'], ['created_at', '<=', $endDate.' 23:59:59']])
                ->orderBy('created_at')
                ->get();

        $spreadsheet = IOFactory::load(public_path('assets/report/transaksi.xlsx'));
        $worksheet = $spreadsheet->getActiveSheet();
        $spreadsheet->getActiveSheet()->getStyle('A7:O7')->applyFromArray($styleHeader);
        $row = 8;
        $grand_total = 0;
               
        $worksheet->getCell('A1')->setValue('Outlet');
        $worksheet->getCell('B1')->setValue($outlet->nama);
        $worksheet->getCell('B2')->setValue(Locale::humanDateTime(now()));
        $worksheet->getCell('B3')->setValue(Locale::humanDate($startDate));
        $worksheet->getCell('B4')->setValue(Locale::humanDate($endDate));
        if($request->has('user')){
            $worksheet->getCell('A5')->setValue('User');
            $worksheet->getCell('B5')->setValue(User::whereIn('id', $post['user'])->get()->implode('username', ', '));
        }
        foreach ($model as $key => $data) {
            $worksheet->getCell('A' . $row)->setValue($data->no_invoice);
            $worksheet->getCell('B' . $row)->setValue(Locale::humanDateTime($data->tanggal));
            $worksheet->getCell('C' . $row)->setValue($data->pelanggan->nama);
            $worksheet->getCell('D' . $row)->setValue(Locale::humanDateTime($data->deadline));
            $worksheet->getCell('E' . $row)->setValue(Transaksi::enumStatus($data->latestStatus->status));
            $worksheet->getCell('F' . $row)->setValue($data->user->username);
            $worksheet->getCell('K' . $row)->setValue($data->subtotal);
            $diskon = 0;
            if($data->diskon > 0){
                $diskon += $data->subtotal * $data->diskon / 100;
            }else if($data->potongan > 0){
                $diskon += $data->potongan;
            }
            $worksheet->getCell('L' . $row)->setValue($diskon);
            $worksheet->getCell('M' . $row)->setValue($data->biaya_tambahan);
            $worksheet->getCell('N' . $row)->setValue($data->ppn);
            $worksheet->getCell('O' . $row)->setValue($data->total);
            foreach ($data->transaksiDetail as $transaksi) {
                $worksheet->getCell('G' . $row)->setValue($transaksi->produk->nama);
                $worksheet->getCell('H' . $row)->setValue(Locale::numberFormat($transaksi->harga));
                $worksheet->getCell('I' . $row)->setValue(Locale::numberFormat($transaksi->jumlah));
                $worksheet->getCell('J' . $row)->setValue(Locale::numberFormat($transaksi->total));
                $row++;
                $grand_total += $data->total;
            }
        }
        $worksheet->getCell('A' . $row)->setValue('Grand Total');
        $worksheet->getCell('O' . $row)->setValue($grand_total);
        $spreadsheet->getActiveSheet()->mergeCells('A' . $row . ':N' . $row);
        $spreadsheet->getActiveSheet()->getStyle('A'.$row.':O'. $row)->applyFromArray($styleHeader);

        foreach ($worksheet->getColumnDimensions() as $colDim) {
            $colDim->setAutoSize(true);
        }
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        header('Content-Disposition: attachment; filename="transaksi_'.implode('_', $file_name). '.xlsx"');
        $writer->save("php://output");
        exit;
    }
}
