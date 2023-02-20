<?php

namespace App\Http\Controllers;

use App\Http\Requests\OutletRequest;
use App\Models\Outlet;
use Exception;
use Yajra\DataTables\Facades\DataTables;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Outlet';
        if(request()->ajax()){
            $model = Outlet::orderBy('id');
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
        return view('master.outlet.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try{
            return view('master.outlet.create');
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Tambah Outlet',
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OutletRequest $request)
    {
        $post = $request->validated()+['created_by' => auth()->user()->username];
        try{
            Outlet::create($post);
            return response()->json([
                'message' => 'Berhasil Menambahkan Outlet' 
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Menambahkan Outlet',
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
            $model = Outlet::find($id);
            if(!$model){
                return response()->json([
                    'message' => 'Outlet Tidak Ditemukan'
                ], 404);
            }
            return view('master.outlet.show', compact('model'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Outlet Gagal Ditampilkan',
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
            $model = Outlet::find($id);
            if(!$model){
                return response()->json([
                    'message' => 'Outlet Tidak Ditemukan'
                ], 404);
            }
            return view('master.outlet.edit', compact('model'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Outlet Gagal Diedit',
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
    public function update(OutletRequest $request, $id)
    {
        $post = $request->validated()+['updated_by' => auth()->user()->username];
        try{
            $model = Outlet::find($id);
            if(!$model){
                return response()->json([
                    'message' => 'Outlet Tidak Ditemukan'
                ], 404);
            }
            $model->update($post);
            return response()->json([
                'message' => 'Berhasil Memperbarui Outlet'
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Memperbarui Outlet',
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
            $model = Outlet::find($id);
            if(!$model){
                return response()->json([
                    'message' => 'Outlet Tidak Ditemukan'
                ], 404);
            }
            $model->delete();
            return response()->json([
                'message' => 'Berhasil Menghapus Outlet'
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Menghapus Outlet',
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }
}
