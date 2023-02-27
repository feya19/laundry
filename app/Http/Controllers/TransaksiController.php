<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaksiRequest;
use App\Library\Locale;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Http\Requests\TransaksiStatusRequest;
use App\Library\AutoNumber;
use App\Models\TransaksiStatus;
use Barryvdh\DomPDF\Facade\PDF;
use DragonCode\Support\Facades\Helpers\Arr;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    public function index()
    {
        $title = 'Transaksi';
        if(request()->ajax()){
            $model = Transaksi::with(['transaksiDetail.produk', 'latestStatus'])
            ->outletAktif()
            ->when(request()->filled('status'), function($q){
                $q->whereHas('latestStatus', function($query){
                    $query->where('status', request('status'));
                });
            });
            return DataTables::of($model)
                ->editColumn('created_at', function($data){
                    return Locale::humanDateTime($data->created_at);
                })
                ->editColumn('deadline', function($data){
                    return Locale::humanDateTime($data->deadline);
                })
                ->editColumn('total', function($data){
                    return Locale::numberFormat($data->total);
                })
                ->editColumn('status', function($data){
                    $key = $data->latestStatus->status;
                    $status = '<label ';
                    switch ($key) {
                        case 'queue':
                            $status .= 'class="badge badge-secondary"';
                            break;
                        case 'process':
                            $status .= 'class="badge badge-info"';
                            break;
                        case 'done':
                            $status .= 'class="badge badge-primary"';
                            break;
                        default:
                            $status .= 'class="badge badge-success"';
                            break;
                    }
                    $status .= '>'.Transaksi::enumStatus($key).'</label>';
                    return $status;
                })
                ->addColumn('lunas', function($data){
                    return Locale::boolean($data->bayar >= $data->total);
                })
                ->addColumn('_', function ($data){
                    $html = '<button class="btn btn-info btn-icon" type="button" onclick="show('.$data->id.')" title="Show"><i class="fas fa-eye"></i></button>'; 
                    $html .= '<button class="btn btn-primary btn-icon ml-2" type="button" onclick="window.open(\''.route('transaksi.invoice', ['id' => $data->id]).'\', \'_blank\')" title="Invoice"><i class="fas fa-file-invoice-dollar"></i></button>';
                    if($data->bayar < $data->total && $data->latestStatus->status != 'taken'){
                        $html .= '<button class="btn btn-success btn-icon mx-2" type="button" onclick="editStatus('.$data->id.')" title="Edit Status"><i class="fas fa-tasks"></i></button>';
                        $html .= '<button class="btn btn-warning btn-icon" type="button" onclick="window.location.href=\''.route('transaksi.edit', ['transaksi' => $data->id]).'\'" title="Edit"><i class="fas fa-edit"></i></button>';
                    }
                    $html .= '<button class="btn btn-danger btn-icon ml-2" type="button" onclick="destroy('.$data->id.')" title="Delete" ><i class="far fa-trash-alt"></i></button>';
                    return $html;  
                })
                ->rawColumns(['_', 'status', 'lunas'])
                ->make(true);
        }
        $status = Transaksi::enumStatus();
        return view('transaksi.index', compact('title', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            $status = Transaksi::enumStatus();
            return view('transaksi.create', compact(['status']));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Menambahkan Transaksi',
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransaksiRequest $request)
    {
        $post = $request->except(['_token', 'no_invoice']);
        DB::beginTransaction();
        try{
            $post['outlets_id'] = session('outlets_id');
            $post['deadline'] = isset($post['batas_waktu']) ? date('Y-m-d H:i:s', strtotime($post['batas_waktu'])) : null;
            $post['no_invoice'] =  AutoNumber::generate('transaksi', 'id', 'INV-'.$post['outlets_id'].'{Y}{m}{d}:4');
            $post['users_id'] = auth()->user()->id;
            if($post['status'] == 'taken') $post['payment_date'] = now();
            $transaksi = Transaksi::create($post);
            $transaksi_detail = Arr::map($post['produk'], function ($produk) use ($transaksi) {
                return [
                    'transaksi_id' => $transaksi->id,
                    'produks_id' => $produk['produks_id'],
                    'harga' => $produk['harga'],
                    'jumlah' => $produk['jumlah'],
                    'total' => $produk['total']
                ];
            });
            TransaksiDetail::insert($transaksi_detail);
            TransaksiStatus::insert([
                'transaksi_id' => $transaksi->id,
                'status' => $post['status'],
                'users_id' => $post['users_id'],
                'created_at' => now()
            ]);
            DB::commit();
            return to_route('transaksi.create')->with('success_message', 'Berhasil Menambahkan Transaksi');
        }catch(Exception $e){
            DB::rollBack();
            return redirect()->back()->withInput()->with('error_message', 'Gagal Menambahkan Transaksi');
        }
    }

    public function editStatus($id){
        try{
            $model = Transaksi::with(['latestStatus'])->outletAktif()->find($id);
            if(!$model){
                return response()->json([
                    'message' => 'Transaksi Tidak Ditemukan'
                ], 404);
            }
            $model->status = $model->latestStatus->status;
            $status = Transaksi::enumStatus();
            return view('transaksi.update-status', compact('model', 'status'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Transaksi Gagal Ditampilkan',
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function updateStatus(TransaksiStatusRequest $request, $id) : JsonResponse
    {
        $post = $request->validated();
        $post['updated_by'] = auth()->user()->username;
        DB::beginTransaction();
        try{
            $transaksi = Transaksi::findOrfail($id);
            if($request->status == 'taken'):
                $post['payment_date'] = now();
                $transaksi->update($post);
            endif;
            TransaksiStatus::insert([
                'transaksi_id' => $id,
                'status' => $post['status'],
                'users_id' => auth()->user()->id,
                'created_at' => now()
            ]);
            DB::commit();
            return response()->json([
                'message' => 'Berhasil Memperbarui Status Transaksi'
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal Memperbarui Status Transaksi',
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $model = Transaksi::with(['user', 'pelanggan', 'transaksiDetail.produk', 'transaksiStatus.user', 'latestStatus'])->find($id);
            if(!$model){
                return response()->json([
                    'message' => 'Transaksi Tidak Ditemukan'
                ], 404);
            }
            $model->status = Transaksi::enumStatus($model->latestStatus->status);
            return view('transaksi.show', compact('model'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Transaksi Gagal Ditampilkan',
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $model = Transaksi::with(['transaksiDetail.produk', 'latestStatus'])->outletAktif()->find($id);
            if(!$model){
                return response()->json([
                    'message' => 'Transaksi Tidak Ditemukan'
                ], 404);
            }
            $pelanggan = Pelanggan::findOrfail($model->pelanggan_id)->pluck('nama', 'id')->toArray();
            $model->deadline = date('Y-m-d H:i', strtotime($model->deadline));
            $model->status = $model->latestStatus->status;
            $status = Transaksi::enumStatus();
            return view('transaksi.edit', compact('model', 'status', 'pelanggan'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Menambahkan Transaksi',
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TransaksiRequest $request, $id)
    {
        $post = $request->validated();
        DB::beginTransaction();
        try{
            $post['updated_by'] =  auth()->user()->username;
            if($post['status'] == 'taken') $post['payment_date'] = now();
            Transaksi::findOrfail($id)->update($post);
            TransaksiDetail::where('transaksi_id', $id)->delete();
            $transaksi_detail = Arr::map($post['produk'], function ($produk) use ($id) {
                return [
                    'transaksi_id' => $id,
                    'produks_id' => $produk['produks_id'],
                    'harga' => $produk['harga'],
                    'jumlah' => $produk['jumlah'],
                    'total' => $produk['total']
                ];
            });
            TransaksiDetail::insert($transaksi_detail);
            $latest = TransaksiStatus::where(['transaksi_id' => $id])->latest()->first();
            if($latest->status != $post['status']):
                TransaksiStatus::insert([
                    'transaksi_id' => $id,
                    'status' => $post['status'],
                    'users_id' => auth()->user()->id,
                    'created_at' => now()
                ]);
            endif;
            DB::commit();
            return response()->json([
                'message' => 'Berhasil Memperbarui Transaksi' 
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            dd($e);
            return response()->json([
                'message' => 'Gagal Memperbarui Transaksi',
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $model = Transaksi::outletAktif()->find($id);
            if(!$model){
                return response()->json([
                    'message' => 'Transaksi Tidak Ditemukan'
                ], 404);
            }
            $model->delete();
            DB::commit();
            return response()->json([
                'message' => 'Berhasil Menghapus Transaksi'
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal Menghapus Transaksi',
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function invoice($id){
        $model = Transaksi::with(['user', 'pelanggan', 'transaksiDetail.produk'])->outletAktif()->find($id);
        $pdf = PDF::loadView('transaksi.invoice', compact('model'));
        return $pdf->stream($model->no_invoice.'.pdf');
    }
}
