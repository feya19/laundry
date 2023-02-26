<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiJsonRequest;
use App\Http\Requests\PelangganRequest;
use App\Models\Pelanggan;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Pelanggan';
        $jenis_kelamin = ['' => 'Pilih'] + Pelanggan::enumJenisKelamin();
        if(request()->ajax()){
            $model = Pelanggan::when(request()->has('jenis_kelamin'), function($q){
                $q->where('jenis_kelamin', request('jenis_kelamin'));
            });
            return DataTables::of($model)
            ->editColumn('jenis_kelamin', function($data){
                return Pelanggan::enumJenisKelamin($data->jenis_kelamin);
            })
            ->addColumn('_', function ($data){
                $html = '<button class="btn btn-info btn-icon" type="button" onclick="show('.$data->id.')" title="Show"><i class="fas fa-eye"></i></button>'; 
                $html .= '<button class="btn btn-warning btn-icon mx-2" type="button" onclick="edit('.$data->id.')" title="Edit"><i class="fas fa-edit"></i></button>';
                $html .= '<button class="btn btn-danger btn-icon" type="button" onclick="destroy('.$data->id.')" title="Delete" ><i class="far fa-trash-alt"></i></button>';
                return $html;  
            })
            ->rawColumns(['_'])
            ->make(true);
        }
        return view('master.pelanggan.index', compact('title', 'jenis_kelamin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            $jenis_kelamin = Pelanggan::enumJenisKelamin();
            return view('master.pelanggan.create', compact('jenis_kelamin'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Menambahkan Pelanggan',
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PelangganRequest $request): JsonResponse
    {
        $post = $request->validated();
        $post['created_by'] = auth()->user()->username;
        DB::beginTransaction();
        try{
            $model = Pelanggan::create($post);
            DB::commit();
            return response()->json([
                'message' => 'Berhasil Menambahkan Pelanggan',
                'data' => $model
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal Menambahkan Pelanggan',
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try{
            $model = Pelanggan::find($id);
            $model->jenis_kelamin = Pelanggan::enumJenisKelamin($model->jenis_kelamin);
            if(!$model){
                return response()->json([
                    'message' => 'Pelanggan Tidak Ditemukan'
                ], 404);
            }
            return view('master.pelanggan.show', compact('model'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Pelanggan Gagal Ditampilkan',
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            $model = Pelanggan::find($id);
            $jenis_kelamin = Pelanggan::enumJenisKelamin();
            $model->telepon = substr($model->telepon, 1);
            if(!$model){
                return response()->json([
                    'message' => 'Pelanggan Tidak Ditemukan'
                ], 404);
            }
            return view('master.pelanggan.edit', compact('model', 'jenis_kelamin'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Pelanggan Gagal Ditampilkan',
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PelangganRequest $request, $id): JsonResponse
    {
        $post = $request->validated()+['updated_by' => auth()->user()->username];
        try{
            $model = Pelanggan::find($id);
            if(!$model){
                return response()->json([
                    'message' => 'Pelanggan Tidak Ditemukan'
                ], 404);
            }
            $model->update($post);
            return response()->json([
                'message' => 'Berhasil Memperbarui Pelanggan'
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Memperbarui Pelanggan',
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        DB::beginTransaction();
        try{
            $model = Pelanggan::find($id);
            if(!$model){
                return response()->json([
                    'message' => 'Pelanggan Tidak Ditemukan'
                ], 404);
            }
            $model->delete();
            DB::commit();
            return response()->json([
                'message' => 'Berhasil Menghapus Pelanggan'
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal Menghapus Pelanggan',
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function pelangganJson(ApiJsonRequest $request){
        try{
            $pelanggan = Pelanggan::when($request->has('q'), function($q) use ($request){
                $q->where('nama', 'LIKE', '%'.$request->q.'%')
                ->orWhere(DB::raw('SUBSTRING(telepon, 1)'), 'LIKE', '%'.$request->q.'%');
            });
            $request->has('limit') ? $pelanggan->limit($request->limit) : $pelanggan->limit(10);
            return response()->json([
                'message' => 'Berhasil Mendapatkan Pelanggan',
                'data' => $pelanggan->get()
            ], 200);
        }catch (Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } 
    }
}
