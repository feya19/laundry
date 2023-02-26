<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Models\Outlet;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class HomeController extends Controller
{
    const PROFILE_FOLDER = 'upload/profile';
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        if($outlet = session('outlets_id')){
            $model = Transaksi::with(['latestStatus'])->where([
                ['outlets_id', $outlet],
                ['created_at', '>=', date('Y-m-01 00:00:00')]
            ])->get();
            $data = ['queue' => 0, 'process' => 0, 'done' => 0, 'taken' => 0, 'transaksi' => 0, 'pembayaran' => 0];
            foreach($model as $val){
                $status = $val->latestStatus->status;
                if($status == 'queue'){
                    $data['queue']++;
                }else if($status == 'process'){
                    $data['process']++;
                }else if($status == 'done'){
                    $data['done']++;
                }else{
                    $data['done']++;
                }
                $data['transaksi'] += $val->total;
                $data['pembayaran'] += $val->bayar;
            }
            return view('home', compact('data'));
        }
        return view('home');
    }

    public function settings(): View
    {
        $user = auth()->user();
        $title = 'Pengaturan';
        return view('settings.index', compact('user', 'title'));
    }

    public function changeProfile(ProfileRequest $request): RedirectResponse
    {
        $data = User::findOrFail(auth()->user()->id);
        $update = ['name' => $request['name']];
        if (isset($request['file'])) {
            if($data->photo){
                $path = public_path(self::PROFILE_FOLDER).'/'.$data->photo;
                if(file_exists($path))unlink($path);
            }
            $file = request()->file('file');
            $filename = date('YmdHis') . '_' . uniqid() . '.' . $file->extension();
            $file->move(public_path(self::PROFILE_FOLDER), $filename);

            $update['photo'] = $filename;
        }
        $data->update($update);
        return to_route('settings')->with('success_message', 'Profil berhasil diubah');
    }

    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        $model = User::findOrFail(auth()->user()->id);
        if (Hash::check($request['oldpassword'] ,  $model->password )) {
            if (!Hash::check($request['newpassword'] , $model->password)) {
                $model->update(['password' => Hash::make($request['newpassword'])]);
                return to_route('settings')->with('success_message', 'Password berhasil diubah');
            }else{
                return redirect()->back()->with('error_message', 'Password harus baru');
            }
        }else{
            return redirect()->back()->with('error_message', 'Password Lama Salah');
        }
    }

    public function selectOutlet(): View
    {
        $user = User::with(['userOutlet' => function($q){
            $q->where('users_id', auth()->user()->id);
        }])->findOrFail(auth()->user()->id);
        $outlets = $user->userOutlet->map(function($data){
            return ['id' => $data->outlet->id, 'nama' => $data->outlet->nama];
        });
        $previous = session('url_intended') ?? Crypt::encrypt(url()->previous());
        return view('outlet', compact('outlets', 'previous'));
    }

    public function setOutlet($id, $previous): RedirectResponse
    {
        $url = Crypt::decrypt($previous);
        Session::put(['outlets_id' => $id]);
        Session::forget('url_intended');
        return redirect()->to($url);
    }
}
