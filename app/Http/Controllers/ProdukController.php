<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdukRequest;
use App\Library\Locale;
use App\Models\JenisProduk;
use App\Models\Outlet;
use App\Models\Produk;
use App\Models\ProdukJenis;
use App\Models\ProdukOutlet;
use DragonCode\Support\Facades\Helpers\Arr;
use Exception;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Produk';
        if(request()->ajax()){
            $model = Produk::with(['produkJenis.jenis', 'produkOutlet.outlet']);
            return DataTables::of($model)
                ->editColumn('harga', function($data){
                    return Locale::numberFormat($data->harga);
                })
                ->addColumn('jenis_produk', function($data){
                    return $data->produkJenis->map(function($produkJenis){
                        return '<label class="badge badge-primary">' . $produkJenis->jenis->jenis . '</label>';
                    })->implode(' ');
                })
                ->addColumn('outlet_produk', function($data){
                    return $data->produkOutlet->map(function($produkOutlet){
                        return '<label class="badge badge-primary">' . $produkOutlet->outlet->nama . '</label>';
                    })->implode(' ');
                })
                ->addColumn('_', function ($data){
                    $html = '<button class="btn btn-info btn-icon" type="button" onclick="show('.$data->id.')" title="Show"><i class="fas fa-eye"></i></button>'; 
                    $html .= '<button class="btn btn-warning btn-icon mx-2" type="button" onclick="edit('.$data->id.')" title="Edit"><i class="fas fa-edit"></i></button>';
                    $html .= '<button class="btn btn-danger btn-icon" type="button" onclick="destroy('.$data->id.')" title="Delete" ><i class="far fa-trash-alt"></i></button>';
                    return $html;  
                })
                ->rawColumns(['_', 'jenis_produk','outlet_produk'])
                ->make(true);
        }
        return view('master.produk.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            $jenis_produk = JenisProduk::pluck('jenis', 'id')->toArray();
            $outlet = Outlet::pluck('nama', 'id')->toArray();
            return view('master.produk.create', compact('jenis_produk', 'outlet'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Menambahkan Produk',
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProdukRequest $request)
    {
        $post = $request->validated();
        $post['jenis_produks_id'] = $post['jenis_produk'];
        $post['created_by'] = auth()->user()->username;
        DB::beginTransaction();
        try{
            $produk = Produk::create($post);
            $produk_jenis = Arr::map($post['jenis_produk'], function ($jenis) use ($produk) {
                return [
                    'produks_id' => $produk->id,
                    'jenis_produks_id' => $jenis
                ];
            });
            ProdukJenis::insert($produk_jenis);
            $produk_outlet = Arr::map($post['produk_outlet'], function ($outlet) use ($produk) {
                return [
                    'produks_id' => $produk->id,
                    'outlets_id' => $outlet
                ];
            });
            ProdukOutlet::insert($produk_outlet);
            DB::commit();
            return response()->json([
                'message' => 'Berhasil Menambahkan Produk' 
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal Menambahkan Produk',
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
            $model = Produk::with(['produkJenis.jenis', 'produkOutlet.outlet'])->find($id);
            $model->jenis_produk = $model->produkJenis->map(function($produkJenis){
                return '<label class="badge badge-primary">' . $produkJenis->jenis->jenis . '</label>';
            })->implode(' ');
            $model->produk_outlet = $model->produkOutlet->map(function($produkOutlet){
                return '<label class="badge badge-primary">' . $produkOutlet->outlet->nama . '</label>';
            })->implode(' ');
            $model->harga = Locale::numberFormat($model->harga);
            if(!$model){
                return response()->json([
                    'message' => 'Produk Tidak Ditemukan'
                ], 404);
            }
            return view('master.produk.show', compact('model'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Produk Gagal Ditampilkan',
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
            $model = Produk::with(['produkJenis.jenis', 'produkOutlet.outlet'])->find($id);
            $model->jenis_produk = $model->produkJenis->map(function($produkJenis){
                return $produkJenis->jenis->id;
            });
            $model->produk_outlet = $model->produkOutlet->map(function($produkOutlet){
                return $produkOutlet->outlet->id;
            });
            $jenis_produk = JenisProduk::pluck('jenis', 'id')->toArray();
            $outlet = Outlet::pluck('nama', 'id')->toArray();
            $model->harga = Locale::numberFormat($model->harga);
            if(!$model){
                return response()->json([
                    'message' => 'Produk Tidak Ditemukan'
                ], 404);
            }
            return view('master.produk.edit', compact(['model', 'jenis_produk', 'outlet']));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Produk Gagal Diedit',
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
    public function update(ProdukRequest $request, $id)
    {
        $post = $request->validated();
        $post['jenis_produks_id'] = $post['jenis_produk'];
        $post['updated_by'] =  auth()->user()->username;
        DB::beginTransaction();
        try{
            $produk = Produk::findOrFail($id)->update($post);
            ProdukJenis::where('produks_id', $id)->delete();
            $produk_jenis = Arr::map($post['jenis_produk'], function ($jenis) use ($produk) {
                return [
                    'produks_id' => $produk->id,
                    'jenis_produks_id' => $jenis
                ];
            });
            ProdukJenis::insert($produk_jenis);
            ProdukOutlet::where('produks_id', $id)->delete();
            $produk_outlet = Arr::map($post['produk_outlet'], function ($outlet) use ($produk) {
                return [
                    'produks_id' => $produk->id,
                    'outlets_id' => $outlet
                ];
            });
            ProdukOutlet::insert($produk_outlet);
            DB::commit();
            return response()->json([
                'message' => 'Berhasil Menambahkan Produk' 
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal Menambahkan Produk',
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
            $model = Produk::find($id);
            if(!$model){
                return response()->json([
                    'message' => 'Produk Tidak Ditemukan'
                ], 404);
            }
            $model->delete();
            DB::commit();
            return response()->json([
                'message' => 'Berhasil Menghapus Produk'
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal Menghapus Produk',
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
}
