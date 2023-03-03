<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\ProfileRequest;
use App\Library\Locale;
use App\Models\Outlet;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\TransaksiStatus;
use App\Models\User;
use DragonCode\Support\Facades\Helpers\Arr;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class HomeController extends Controller
{
    const PROFILE_FOLDER = 'upload/profile';
    protected $month;
    public function __construct()
    {
        $this->month = date('m');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): View
    {
        $title = "Dashboard";
        if($outlet = session('outlets_id')){
            $month = $this->month;
            $model = Transaksi::selectRaw('SUM(total) AS transaksi, SUM(bayar - kembali) AS pembayaran')->where([
                ['outlets_id', $outlet],
                ['created_at', '>=', date('Y-'.$month.'-01 00:00:00')],
                ['created_at', '<=', date('Y-'.$month.'-t 23:59:59')]
            ])->first();
            return view('home', compact('title','model'));
        }
        return view('home', compact('title'));
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

    public function statusTransaksi($outlet, $month = null): JsonResponse
    {
        $month = $month ?: $this->month;
        $model = Transaksi::with(['pelanggan','latestStatus'])
        ->select('*')
        ->addSelect(DB::raw("LEFT(created_at,10) AS periode"))
        ->where([
            ['outlets_id', $outlet],
            ['created_at', '>=', date('Y-'.$month.'-01 00:00:00')],
            ['created_at', '<=', date('Y-'.$month.'-t 23:59:59')]
        ])->get();
        $status = $model->countBy('latestStatus.status');
        $transaksi = $model->groupBy('periode')->mapWithKeys(function($data, $key){
            $days = date('d', strtotime($key));
            $count = $data->count('id');
            $label = Locale::humanDateLabel($key);
            return [$days => [
                'days' => $days,
                'label' => substr($label, 0, -5),
                'bayar' => $data->sum('total'),
                'pajak' => $data->sum('ppn'),
                'transaksi' => $count * 150000
            ]];
        })->keyBy('days');
        $top5 = $model->groupBy('pelanggan')->mapWithKeys(function($data, $key){
            $pelanggan = json_decode($key);
            return [$pelanggan->id => [
                'nama' => $pelanggan->nama,
                'transaksi' => $data->count(),
                'total' => $data->sum('total'),
                'pembayaran' => $data->sum('bayar') - $data->sum('kembali')
            ]];
        })->sortBy([['transaksi', 'desc'],['total', 'desc'],['pembayaran', 'desc']])->take(5)->values();
        $data = [
            'queue' => $status['queue'] ?? 0,
            'process' => $status['process'] ?? 0,
            'done' => $status['done'] ?? 0,
            'taken' => $status['taken'] ?? 0,
            'overdue' => $model->where('deadline', '<=', date('Y-m-d H:i:s'))->whereIn('latestStatus.status', ['queue', 'process'])->count(),
            'pajak' => $model->sum('ppn'),
            'pembayaran' => $model->sum('total'),
            'transaksi' => $model->count('id'),
            'chart' => [
                'month' => $transaksi->implode('label', ','),
                'pajak' => $transaksi->implode('pajak', ','),
                'transaksi' => $transaksi->implode('transaksi', ','),
                'pembayaran' => $transaksi->implode('bayar', ',')
            ],
            'pelanggan' => $top5
        ];
        return response()->json([
            'data' => $data
        ], 200);
    }
}
