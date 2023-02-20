<?php

namespace App\Http\Controllers;

use App\Http\Requests\JenisProdukRequest;
use App\Models\JenisProduk;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class JenisProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Jenis Produk';
        if(request()->ajax()){
            $model = JenisProduk::orderBy('id');
            return DataTables::of($model)
                ->addColumn('_', function ($data){
                    $html = '<button class="btn btn-info btn-icon" type="button" onclick="show('.$data->id.')" title="Show"><i class="fas fa-eye"></i></button>'; 
                    $html .= '<button class="btn btn-warning btn-icon mx-2" type="button" onclick="edit('.$data->id.')" title="Edit"><i class="fas fa-edit"></i></button>';
                    $html .= '<button class="btn btn-danger btn-icon" type="button" onclick="destroy('.$data->id.')" title="Delete" ><i class="far fa-trash-alt"></i></button>';
                    return $html;  
                })
                ->rawColumns(['_'])
                ->make(true);
        }
        return view('master.jenis_produk.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            return view('master.jenis_produk.create');
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Tambah Jenis Produk',
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JenisProdukRequest $request)
    {
        $post = $request->validated()+['created_by' => auth()->user()->username];
        try{
            JenisProduk::create($post);
            return response()->json([
                'message' => 'Berhasil Menambahkan Jenis Produk' 
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Menambahkan Jenis Produk',
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
            $model = JenisProduk::find($id);
            if(!$model){
                return response()->json([
                    'message' => 'JenisProduk Tidak Ditemukan'
                ], 404);
            }
            return view('master.jenis_produk.show', compact('model'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'JenisProduk Gagal Ditampilkan',
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
            $model = JenisProduk::find($id);
            if(!$model){
                return response()->json([
                    'message' => 'JenisProduk Tidak Ditemukan'
                ], 404);
            }
            return view('master.jenis_produk.edit', compact('model'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'JenisProduk Gagal Diedit',
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
    public function update(JenisProdukRequest $request, $id)
    {
        $post = $request->validated()+['updated_by' => auth()->user()->username];
        try{
            $model = JenisProduk::find($id);
            if(!$model){
                return response()->json([
                    'message' => 'JenisProduk Tidak Ditemukan'
                ], 404);
            }
            $model->update($post);
            return response()->json([
                'message' => 'Berhasil Memperbarui Jenis Produk'
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Memperbarui Jenis Produk',
                'error' => $e->getMessage()
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
        try{
            $model = JenisProduk::find($id);
            if(!$model){
                return response()->json([
                    'message' => 'JenisProduk Tidak Ditemukan'
                ], 404);
            }
            $model->delete();
            return response()->json([
                'message' => 'Berhasil Menghapus Jenis Produk'
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Menghapus Jenis Produk',
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
}
