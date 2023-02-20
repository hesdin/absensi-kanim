<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\Cuti;
use App\Models\Time;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use DataTables;


class UserController extends Controller
{
  public function dashboard()
  {
    $data = [
      'hadir' => Absen::where('user_id', auth()->user()->id)->where('status_masuk', 'hadir')->whereMonth('created_at', date('m'))->get()->count(),
      'terlambat' => Absen::where('user_id', auth()->user()->id)->where('status_masuk', 'terlambat')->whereMonth('created_at', date('m'))->get()->count(),
      'tidakHadir' => Absen::where('user_id', auth()->user()->id)->where('status_masuk', 'tidak hadir')->whereMonth('created_at', date('m'))->get()->count()
    ];
    return view('user.dashboard', $data);
  }

  public function profile()
  {

    return view('user.profile');
  }

  public function profileUpdate(Request $request, $id)
  {

    $profile = User::findOrFail($id);

    if ($request->filled('pass') && $request->has('newPass')) {
      if (Hash::check($request->pass, $profile->password)) {
         $profile->password = bcrypt($request->newPass);
      } else {
        $request->session()->flash('error', 'Password does not match');
        return redirect()->back()->with('fail', 'Profile gagal diupdate');
      }
    }

    $profile->nama = $request->nama;
    $profile->jk = $request->jk;
    $profile->email = $request->email;

    if ($request->hasFile('foto')) {
      $destination = public_path('assets/images/profiles/'.$profile->foto);
      if (File::exists($destination)) {
        File::delete($destination);
      }

      $foto = $request->file('foto');
      $extention = $foto->getClientOriginalExtension();
      $namaFoto = time().'-'.$request->nama.'.'.$extention;
      $foto->move(public_path('assets/images/profiles'), $namaFoto);

      $profile->foto = $namaFoto;
    }

    $update = $profile->Update();

    if ($update) {
      return redirect()->back()->with('success', 'Profile berhasil diupdate');
    } else {
      return redirect()->back()->with('fail', 'Profile gagal diupdate');
    }

  }

  public function absen()
  {
    $date = Carbon::now();

    $absen = Absen::where('user_id', Auth::user()->id)
            ->whereDate('date_masuk', Carbon::today())
            ->first();

    $jadwal_masuk = Time::find(1);

    $jadwal_masuk_mulai = $jadwal_masuk->mulai;
    $jadwal_masuk_mulai = Carbon::createFromTimeString($jadwal_masuk_mulai);
    $jadwal_masuk_mulai = $date->gt($jadwal_masuk_mulai);

 
    $jadwal_pulang = Time::find(2);
    $jadwal_pulang_mulai = $jadwal_pulang->mulai;
    $jadwal_pulang_mulai = Carbon::createFromTimeString($jadwal_pulang_mulai);
    $jadwal_pulang_mulai = $date->gt($jadwal_pulang_mulai);

   
    $check2 = true;

    if ($absen !== null) {
      $a = $date->format('M d Y');
      $b = $absen->created_at->format('M d Y');

      if ($a !== $b) {
        $check2 = false;
      } else {
        $check2 = true;
      }

    }

    $data = [
      'waktu' => $date,
      'check' => $absen,
      'check2' => $check2,
      'jadwal_masuk_mulai' => $jadwal_masuk_mulai,
      'jadwal_pulang_mulai' => $jadwal_pulang_mulai,

      // 'jadwal_masuk_selesai' => $jadwal_masuk_selesai,

    ];

    return view('user.absen', $data);
  }

  public function absenStore(Request $request, $id)
  {
    $cuti = Cuti::where('user_id', Auth::user()->id)
                  ->where('status', 1)
                  ->first();

    if ($cuti) {
      return redirect()->back()->with('fail', 'Gagal absen, anda dalam masa cuti');
    }
    
    $date = Carbon::now();

    $img =  $request->get('image');

    if ($img === null) {
       return redirect()->back()->with('fail', 'Gagal absen, anda belum ambil foto');
    }

    $folderPath = public_path('assets/images/absens/');
    $image_parts = explode(";base64,", $img);

    foreach ($image_parts as $key => $image){
      $image_base64 = base64_decode($image);
    }

    $fileName = 'masuk-'.uniqid() .'.png';
    $file = $folderPath . $fileName;

    file_put_contents($file, $image_base64);

    $jadwal_masuk = Time::find(1);
    $jadwal_masuk_selesai = $jadwal_masuk->selesai;
    $jadwal_masuk_selesai = Carbon::createFromTimeString($jadwal_masuk_selesai);
    $jadwal_masuk_selesai = $date->gt($jadwal_masuk_selesai);

    $absen = new Absen();

    $absen->user_id = $id;
    $absen->date_masuk = Carbon::now();

    if ($jadwal_masuk_selesai === true) {
      $absen->status_masuk = 'absen terlambat';
    } else {
      $absen->status_masuk = 'absen hadir';
    }

    $absen->foto_masuk = $fileName;

    $absen->save();

    return redirect()->back()->with('success', 'Anda berhasil absen, silahkan tunggu konfirmasi lewat email, jika ditolak anda harus absen ulang');

  }

  public function absenUpdate(Request $request, $id)
  {
    $date = Carbon::now();

    $img =  $request->get('image');

    if ($img === null) {
      return redirect()->back()->with('fail', 'Gagal absen, anda belum ambil foto');
    }

    $folderPath = public_path('assets/images/absens/');
    $image_parts = explode(";base64,", $img);

    foreach ($image_parts as $key => $image){
      $image_base64 = base64_decode($image);
    }

    $fileName = 'pulang-'.uniqid() .'.png';
    $file = $folderPath . $fileName;

    file_put_contents($file, $image_base64);


    $jadwal_pulang = Time::find(2);
    $jadwal_pulang_selesai = $jadwal_pulang->selesai;
    $jadwal_pulang_selesai = Carbon::createFromTimeString($jadwal_pulang_selesai);
    $jadwal_pulang_selesai = $date->gt($jadwal_pulang_selesai);


    $absen = Absen::where('user_id', $id)
            ->orderBy('id', 'DESC')
            ->first();

    if ($jadwal_pulang_selesai === true) {
      $absen->status_pulang = 'absen lembur';
    } else {
      $absen->status_pulang = 'absen pulang';
    }

    $absen->date_pulang = Carbon::now();
    $absen->foto_pulang = $fileName;

    $absen->update();

    return redirect()->back()->with('success', 'Anda berhasil absen, silahkan tunggu konfirmasi lewat email, jika ditolak anda harus absen ulang');

  }

  public function tabelAbsen(Request $request, $id)
  {
    $datas = Absen::select('*')
          ->where('user_id', '=', $id);

    if ($request->ajax()) {


      return Datatables::of($datas)

          ->addIndexColumn()

          ->filter(function ($instance) use ($request) {

            if ($request->get('month')) {

              $instance->whereMonth('created_at', $request->get('month'));

            }

            if (!empty($request->get('search'))) {

              $search = $request->get('search');

              $instance->where('status_masuk', 'LIKE', "%$search%");

              //  $instance->where(function($w) use($request){

              //     $search = $request->get('search');

              //     $w->orWhere('status_masuk', 'LIKE', "%$search%")

              //    ;

              // });

            }

          })

          ->make(true);

    }

    return view('user.tabel-absensi');
  }

  public function rekapAbsen(Request $request, $id)
  {
    $tests = Absen::where('user_id', 1)->where('status_masuk', 'hadir')->count();

    dd($tests);

    $datas = Absen::select('*')
          ->where('user_id', '=', $id);

    if ($request->ajax()) {

      return Datatables::of($datas)

          ->addIndexColumn()

          ->filter(function ($instance) use ($request) {

            if ($request->get('month')) {

              $instance->whereMonth('created_at', $request->get('month'));

            }

            if (!empty($request->get('search'))) {

              $search = $request->get('search');

              $instance->where('status_masuk', 'LIKE', "%$search%");

              //  $instance->where(function($w) use($request){

              //     $search = $request->get('search');

              //     $w->orWhere('status_masuk', 'LIKE', "%$search%")

              //    ;

              // });

            }

          })

          ->make(true);

    }

    return view('user.rekap-absen');
  }

  public function permohonanCuti()
  {
    // $u->whereRelation('absen', 'created_at', '=', $hari)->first());

    $userHasCuti = User::has('cuti')->find(Auth::user()->id);
    // $userHasCuti = User::has('cuti')->get();

    // dd($userHasCuti);
    // foreach ($userHasCuti as $u) {
    //   if ($userHasCuti) {
    //     dd($u->cuti->status);
    //   }
    // }

    // dd($userHasCuti);

    // dd($userHasCuti->cuti->status);
    // if ($userHasCuti->cuti->status == true) {
    //   dd('ada');
    // }

    // dd('tidak');

    return view('user.cuti', ['userHasCuti' => $userHasCuti]);
  }

  public function permohonanCutiStore(Request $request, $id)
  {
    $userHasAbsen = Absen::where('user_id', $id)->latest()->first();

    if ($userHasAbsen !== null) {
      if ($userHasAbsen->created_at->isToday()) {
        return redirect()->back()->with('fail', 'Gagal, tidak bisa ajukan cuti karena sebelumnya telah absen, ajukan lagi besok');
      }
    }

    $img =  $request->get('image');

    if ($img === null) {
      return redirect()->back()->with('fail', 'Gagal, foto permohonan belum ada');
    }

    // Validate
    $request->validate([
      'jc' => 'required',
      'date' => 'required',
    ],
    [
      'jc.required' => 'Cuti belum dipilih',
      'date.required' => 'Tidak boleh kosong',

    ]);

    $folderPath = public_path('assets/images/cuti/');
    $image_parts = explode(";base64,", $img);

    foreach ($image_parts as $key => $image){
      $image_base64 = base64_decode($image);
    }

    $fileName = 'cuti-'.uniqid() .'.png';
    $file = $folderPath . $fileName;

    file_put_contents($file, $image_base64);

    $cuti = new Cuti();

    $cuti->user_id = $id;
    $cuti->jc = $request->jc;
    $cuti->date = $request->date;
    $cuti->foto = $fileName;

    $cuti->save();

    return redirect()->back()->with('success', 'Permohonan cuti berhasil terkirim, silahkan tunggu konfirmasi lewat email, jika ditolak permohonan harus di ulang');

  }

}
