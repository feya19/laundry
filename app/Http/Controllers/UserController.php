<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiJsonRequest;
use App\Http\Requests\UserRequest;
use App\Models\Outlet;
use App\Models\User;
use App\Models\UserOutlet;
use DragonCode\Support\Facades\Helpers\Arr;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    const PROFILE_FOLDER = 'upload/profile';

    public function index() {
        $title = 'Users';
        if(request()->ajax()){
            $model = User::with('userOutlet.outlet');
            return DataTables::of($model)
                ->editColumn('role', function($data){
                    return User::enumRole($data->role);
                })
                ->addColumn('outlet_user', function($data){
                    return $data->userOutlet->map(function($userOutlet){
                        return '<label class="badge badge-primary">' . $userOutlet->outlet->nama . '</label>';
                    })->implode(' ');
                })
                ->addColumn('_', function ($data){
                    $html = '<button class="btn btn-info btn-icon" type="button" onclick="show('.$data->id.')" title="Show"><i class="fas fa-eye"></i></button>'; 
                    $html .= '<button class="btn btn-warning btn-icon mx-2" type="button" onclick="edit('.$data->id.')" title="Edit"><i class="fas fa-edit"></i></button>';
                    $html .= '<button class="btn btn-danger btn-icon" type="button" onclick="destroy('.$data->id.')" title="Delete" ><i class="far fa-trash-alt"></i></button>';
                    return $html;  
                })
                ->rawColumns(['_', 'outlet_user'])
                ->make(true);
        }
        return view('master.users.index', compact('title'));
    }

    public function create()
    {
        try{
            $roles = ['' => 'Pilih'] + User::enumRole();
            $outlet = Outlet::pluck('nama', 'id')->toArray();
            return view('master.users.create', compact('roles', 'outlet'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Menambahkan User',
            ], $e->getCode() ?: 500);
        }
    }

    public function store(UserRequest $request)
    {
        $post = $request->validated();
        $data = [
            'username' => $post['username'],
            'password' => Hash::make($post['password']),
            'name' => $post['nama'],
            'role' => $post['role']
        ];
        DB::beginTransaction();
        try{
            if (isset($post['file'])) {
                $file = $request->file('file');
                $filename = date('YmdHis') . '_' . uniqid() . '.' . $file->extension();
                $file->move(public_path(self::PROFILE_FOLDER), $filename);
    
                $data['photo'] = $filename;
            }
            $user = User::create($data);
            if($request->user_outlet){
                $userOutlet = Arr::map($request->user_outlet, function(string $data) use ($user){
                    return ['users_id' => $user->id, 'outlets_id' => $data];
                });
                UserOutlet::insert($userOutlet);
            }
            DB::commit();
            return response()->json([
                'message' => 'Berhasil Menambahkan User'
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal Menambahkan User',
            ], $e->getCode() ?: 500);
        }
    }

    public function show($id) {
        try{
            $model = User::with('userOutlet.outlet')->findOrFail($id);
            return view('master.users.show', compact('model'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'User Gagal Ditampilkan',
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function edit($id) {
        try{
            $roles = ['' => 'Pilih'] + User::enumRole();
            $outlet = Outlet::pluck('nama', 'id')->toArray();
            $model = User::with('userOutlet.outlet')->findOrfail($id);
            $model->nama = $model->name;
            $model->user_outlet = $model->userOutlet->map(function($data){
                return $data->outlet->id;
            });
            return view('master.users.edit', compact('model', 'roles', 'outlet'));
        }catch(Exception $e){
            return response()->json([
                'message' => 'Gagal Edit User',
            ], $e->getCode() ?: 500);
        }
    }

    public function update(UserRequest $request, $id) {
        $post = $request->validated();
        $data = [
            'username' => $post['username'],
            'name' => $post['nama'],
            'role' => $post['role']
        ];
        DB::beginTransaction();
        try{
            $model = User::findOrFail($id);
            if (isset($post['password'])) $data['password'] = Hash::make($post['password']);
            if (isset($post['file'])) {
                if($model->photo){
                    $path = public_path(self::PROFILE_FOLDER).'/'.$model->photo;
                    if(file_exists($path))unlink($path);
                }
                $file = $request->file('file');
                $filename = date('YmdHis') . '_' . uniqid() . '.' . $file->extension();
                $file->move(public_path(self::PROFILE_FOLDER), $filename);
    
                $data['photo'] = $filename;
            }
            UserOutlet::where('users_id', $model->id)->delete();
            if($request->user_outlet){
                $userOutlet = Arr::map($request->user_outlet, function(string $data) use ($model){
                    return ['users_id' => $model->id, 'outlets_id' => $data];
                });
                UserOutlet::insert($userOutlet);
            }
            $model->update($data);
            DB::commit();
            return response()->json([
                'message' => 'Berhasil Memperbarui User'
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal Memperbarui User',
                'error' => $e->getMessage()
            ], $e->getCode() ?: 500);
        }
    }

    public function destroy($id) {
        DB::beginTransaction();
        try{
            User::findOrfail($id)->delete();
            DB::commit();
            return response()->json([
                'message' => 'Berhasil Menghapus User'
            ], 200);
        }catch(Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal Edit User',
            ], $e->getCode() ?: 500);
        }        
    }

    public function userJson(ApiJsonRequest $request){
        try{
            $user = User::when($request->has('q'), function($q) use ($request){
                $q->where('username', 'LIKE', '%'.$request->q.'%');
            });
            if(request('kasir')) $user->whereIn('role', ['kasir', 'admin']);
            $request->has('limit') ? $user->limit($request->limit) : $user->limit(10);
            return response()->json([
                'message' => 'Berhasil Mendapatkan User',
                'data' => $user->get()
            ], 200);
        }catch (Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        } 
    }
}
