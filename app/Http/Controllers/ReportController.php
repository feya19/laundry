<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(): View
    {
        $title = 'Laporan';
        $status = Transaksi::enumStatus();
        return view('report.index', compact(['title', 'status']));
    }
}
