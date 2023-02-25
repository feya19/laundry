<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaksiRequest;
use App\Library\Locale;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Http\Controllers\ProdukController;
use App\Library\AutoNumber;
use App\Models\Produk;
use App\Models\TransaksiStatus;
use DragonCode\Support\Facades\Helpers\Arr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TransaksiController extends Controller
{
    public function index()
    {
        $title = 'Transaksi';
        if(request()->ajax()){
            $model = Transaksi::with(['transaksiDetail.produk', 'transaksiStatus' => function($q){
                $q->latest();
            }]);
            return DataTables::of($model)
                ->editColumn('created_at', function($data){
                    return Locale::humanDateTime($data->created_at);
                })
                ->editColumn('total', function($data){
                    return Locale::numberFormat($data->total);
                })
                ->editColumn('status', function($data){
                    $key = $data->transaksiStatus[0]->status;
                    $status = '<label ';
                    switch ($key) {
                        case 'queue':
                            $status .= 'class="badge badge-outline-secondary"';
                            break;
                        case 'process':
                            $status .= 'class="badge badge-outline-info"';
                            break;
                        case 'done':
                            $status .= 'class="badge badge-outline-primary"';
                            break;
                        default:
                            $status .= 'class="badge badge-outline-success"';
                            break;
                    }
                    $status .= '>'.Transaksi::enumStatus($key).'</label>';
                    return $status;
                })
                ->editColumn('payment', function($data){
                    return Locale::boolean($data->payment);
                })
                ->addColumn('_', function ($data){
                    $html = '<button class="btn btn-info btn-icon" type="button" onclick="show('.$data->id.')" title="Show"><i class="fas fa-eye"></i></button>'; 
                    $html .= '<button class="btn btn-warning btn-icon mx-2" type="button" onclick="edit('.$data->id.')" title="Edit"><i class="fas fa-edit"></i></button>';
                    $html .= '<button class="btn btn-danger btn-icon" type="button" onclick="destroy('.$data->id.')" title="Delete" ><i class="far fa-trash-alt"></i></button>';
                    return $html;  
                })
                ->rawColumns(['_', 'status', 'payment'])
                ->make(true);
        }
        return view('transaksi.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            $pelanggan = Pelanggan::pluck('nama', 'id')->toArray();
            $status = Transaksi::enumStatus();
            return view('transaksi.create', compact(['status', 'pelanggan']));
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
            if($post['status'] == 'taken'){
                $post['payment_date'] = now();
            }
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
            dd($e);
            return redirect()->back()->withInput()->with('error_message', 'Gagal Menambahkan Transaksi');
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
            $model = Transaksi::with(['TransaksiJenis.jenis', 'TransaksiDetail.outlet'])->find($id);
            $model->jenis_Transaksi = $model->TransaksiJenis->map(function($TransaksiJenis){
                return '<label class="badge badge-primary">' . $TransaksiJenis->jenis->jenis . '</label>';
            })->implode(' ');
            $model->Transaksi_outlet = $model->TransaksiDetail->map(function($TransaksiDetail){
                return '<label class="badge badge-primary">' . $TransaksiDetail->outlet->nama . '</label>';
            })->implode(' ');
            $model->harga = Locale::numberFormat($model->harga);
            if(!$model){
                return response()->json([
                    'message' => 'Transaksi Tidak Ditemukan'
                ], 404);
            }
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
            $model = Transaksi::with(['TransaksiJenis.jenis', 'TransaksiDetail.outlet'])->find($id);
            $model->jenis_Transaksi = $model->TransaksiJenis->map(function($TransaksiJenis){
                return $TransaksiJenis->jenis->id;
            });
            $model->Transaksi_outlet = $model->TransaksiDetail->map(function($TransaksiDetail){
                return $TransaksiDetail->outlet->id;
            });
            $jenis_Transaksi = JenisTransaksi::pluck('jenis', 'id')->toArray();
            $outlet = Outlet::pluck('nama', 'id')->toArray();
            $model->harga = Locale::numberFormat($model->harga);
            if(!$model){
                return response()->json([
                    'message' => 'Transaksi Tidak Ditemukan'
                ], 404);
            }
            return view('transaksi.edit', compact(['model', 'jenis_Transaksi', 'outlet']));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Transaksi Gagal Diedit',
                'error' => $e->getMessage()
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
        $post['jenis_Transaksis_id'] = $post['jenis_Transaksi'];
        $post['updated_by'] =  auth()->user()->username;
        DB::beginTransaction();
        try{
            $Transaksi = Transaksi::findOrFail($id)->update($post);
            TransaksiJenis::where('Transaksis_id', $id)->delete();
            $Transaksi_jenis = Arr::map($post['jenis_Transaksi'], function ($jenis) use ($Transaksi) {
                return [
                    'Transaksis_id' => $Transaksi->id,
                    'jenis_Transaksis_id' => $jenis
                ];
            });
            TransaksiJenis::insert($Transaksi_jenis);
            TransaksiDetail::where('Transaksis_id', $id)->delete();
            $Transaksi_outlet = Arr::map($post['Transaksi_outlet'], function ($outlet) use ($Transaksi) {
                return [
                    'Transaksis_id' => $Transaksi->id,
                    'outlets_id' => $outlet
                ];
            });
            TransaksiDetail::insert($Transaksi_outlet);
            DB::commit();
            return response()->json([
                'message' => 'Berhasil Menambahkan Transaksi' 
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal Menambahkan Transaksi',
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
            $model = Transaksi::find($id);
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
}
