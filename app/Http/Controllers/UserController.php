<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    const PROFILE_FOLDER = 'assets/upload_file';

    public function index() {
        $title = 'Users';
        if(request()->ajax()){
            $model = User::orderBy('id');
            return DataTables::of($model)
                ->editColumn('role', function($data){
                    return User::enumRole($data->role);
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
        return view('master.users.index', compact('title'));
    }

    public function create()
    {
        try{
            $roles = ['' => 'Pilih'] + User::enumRole();
            return view('master.users.create', compact('roles'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Tambah User',
            ], $e->getCode() ?: 500);
        }
    }

    public function store(UserRequest $request) {
        $post = $request->validated();
        $data = [
            'username' => $post['username'],
            'password' => Hash::make($post['password']),
            'name' => $post['name'],
            'role' => $post['role']
        ];
        if (isset($post['file'])) {
            $file = request()->file('file');
            $filename = date('YmdHis') . '_' . uniqid() . '.' . $file->extension();
            $file->move(public_path(self::PROFILE_FOLDER), $filename);

            $data['photo'] = $filename;
        }
        if (User::create($data)) {
            return response()->json([
                'message' => 'Berhasil Menambahkan User'
            ], 200);
        }else{
            return response()->json([
                'message' => 'Gagal Menambahkan User'
            ], 500);
        }
            
    }

    public function show($id) {
        $model = User::findOrFail($id);
        return view('master.users.show', compact('model'));
    }

    public function edit($id) {
        try{
            $roles = ['' => 'Pilih'] + User::enumRole();
            $model = User::findOrfail($id);
            $model->nama = $model->name;
            return view('master.users.edit', compact('model', 'roles'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Edit User',
            ], $e->getCode() ?: 500);
        }
    }

    public function update($post, $id) {
        $model = User::findOrFail($id);
        $data = [
            'username' => $post['username'],
            'name' => $post['name'],
            'role' => $post['role']
        ];
        if($post['password']){
            $data['password'] = Hash::make($post['password']);
        }
        if (isset($post['file'])) {
            if($model->photo){
                $path = public_path(self::PROFILE_FOLDER).'/'.$model->photo;
                if(file_exists($path))unlink($path);
            }
            $file = request()->file('file');
            $filename = date('YmdHis') . '_' . uniqid() . '.' . $file->extension();
            $file->move(public_path(self::PROFILE_FOLDER), $filename);

            $data['photo'] = $filename;
        }
        if ($model->update($data)) {
            return response()->json([
                'message' => 'Berhasil Memperbarui User'
            ], 200);
        }else{
            return response()->json([
                'message' => 'Gagal Memperbarui User'
            ], 200);
        }               
    }

    public function destroy($id) {
        $model = User::findOrFail($id);
        if ($model->delete()) {
            return response()->json([
                'message' => 'Berhasil Menghapus User'
            ], 200);
        }else{
            return response()->json([
                'message' => 'Gagal Menghapus User'
            ], 500);
        }           
    }
}
