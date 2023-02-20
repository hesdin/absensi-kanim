<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Absen;
use App\Models\Time;
use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use App\Mail\KonfirmasiAbsen;
use App\Mail\KonfirmasiCuti;
use DataTables;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapAbsen;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    public function dashboard()
    {
       $masuk = Absen::where('status_masuk', 'absen hadir')
                        ->orWhere('status_masuk', 'absen terlambat')
                        ->whereDate('created_at', Carbon::today())
                        ->count('date_masuk');
                        
        $pulang = Absen::where('status_pulang', 'absen pulang')
                        ->orWhere('status_pulang', 'absen lembur')
                        ->whereDate('created_at', Carbon::today())
                        ->count('date_masuk');
        
        $cuti = Cuti::where('status', 0)->whereDate('created_at', Carbon::today())->count();

        $jumlahKaryawan = User::all()->count();
        $absenHariIni = Absen::where('status_masuk', 'hadir')->orWhere('status_masuk', 'terlambat')->whereDate('created_at', Carbon::now())->get()->count();
        $belumAbsenHariIni = $jumlahKaryawan - $absenHariIni;

        $data = [
            'masuk' => $masuk,
            'pulang' => $pulang,
            'cuti' => $cuti,

            'jumlahKaryawan' => $jumlahKaryawan,
            'absenHariIni' => $absenHariIni,
            'belumAbsenHariIni' => $belumAbsenHariIni
        ];

        // dd($cuti);

        return view('admin.dashboard', $data);
    }

    public function tambahAnggota()
    {
        return view('admin.tambah-anggota');
    }

    public function storeAnggota(Request $request)
    {

        // Validate
        $request->validate(
            [
                'nip' => 'required|numeric|min:18|unique:users,nip',
                'nama' => 'required',
                'jk' => 'required',
                'seksi' => 'required',
                'jabatan' => 'required',
                'email' => 'required|unique:users,email',
                'pass' => 'required|min:3',
            ],
            [
                'email.unique' => 'This email already exists'
            ]
        );

        $user = new User();

        $user->nip = $request->nip;
        $user->nama = $request->nama;
        $user->jk = $request->jk;
        $user->seksi = $request->seksi;
        $user->jabatan = $request->jabatan;
        $user->email = $request->email;
        $user->foto = 'profile.png';
        $user->password = bcrypt($request->pass);

        $save = $user->save();

        if ($save) {
            return redirect()->route('admin.data-anggota')->with('success', 'Berhasil ditambahkan !');
        } else {
            return redirect()->back()->with('fail', 'Terjadi kesalahan, ulangi nanti');
        }
    }

    public function anggota()
    {
        $data = [
            'users' => User::select('*')
                ->orderBy('updated_at', 'desc')
                ->get(),
        ];

        return view('admin.data-anggota', $data);
    }

    public function editAnggota($id)
    {
        $user = User::findOrFail($id);

        $data = [
            'user' => $user,
        ];

        return view('admin.edit-anggota', $data);
    }

    public function updateAnggota(Request $request, $id)
    {

        $user = User::findOrFail($id);

        $user->nama = $request->nama;
        $user->jk = $request->jk;
        $user->email = $request->email;
        if ($request->pass) {
            $user->password = bcrypt($request->pass);
        }

        if ($request->hasFile('foto')) {
            $destination = public_path('assets/images/profiles/' . $user->foto);
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $foto = $request->file('foto');
            $extention = $foto->getClientOriginalExtension();
            $namaFoto = time() . '-' . $request->nama . '.' . $extention;
            $foto->move(public_path('assets/images/profiles'), $namaFoto);

            $user->foto = $namaFoto;
        }

        $update = $user->Update();

        if ($update) {
            return redirect()->route('admin.data-anggota')->with('success', 'Data user berhasil diupdate');
        } else {
            return redirect()->back()->with('fail', 'Data user gagal diupdate');
        }
    }

    public function deleteAnggota($id)
    {
        $user = User::findOrFail($id);

        if ($user->foto != "profile.png" || $user->foto != "default.png") {
          $destination = public_path('assets/images/profiles/' . $user->foto);

          if (File::exists($destination)) {
              File::delete($destination);
          }
        }

        $user->delete();

        return redirect()->back()->with('success', 'Berhasil dihapus !');
    }

    public function konfirmasiKehadiran()
    {
        $absen = Absen::where('status_masuk', 'absen hadir')
            ->orWhere('status_masuk', 'absen terlambat')
            ->get();

        $data = [
            'absens' => $absen,
        ];

        return view('admin.konfirmasi-kehadiran', $data);
    }

    public function fotoKehadiran($id)
    {
        $absen = Absen::find($id);

        $foto = $absen->foto_masuk;

        return view('admin.foto-kehadiran', ['fotoMasuk' => $foto]);
    }

    public function terimaKehadiran($status, $id)
    {

        $absen = Absen::find($id);

        $email = $absen->user->email;

        if ($status == 'masuk') {

            $details = [

                'title' => 'email dari absensi.kanim',

                'body' => 'Absen masuk diterima, Anda dinyatakan hadir'

            ];

            if ($absen->status_masuk == 'absen hadir') {
                $absen->status_masuk = 'hadir';
                $absen->update();
            } else {
                $absen->status_masuk = 'terlambat';
                $absen->update();
            }
        } else {

            $details = [

                'title' => 'email dari absensi.kanim',

                'body' => 'Absen pulang diterima'

            ];

            if ($absen->status_pulang == 'absen pulang') {
                $absen->status_pulang = 'pulang';
                $absen->update();
            } else {
                $absen->status_pulang = 'lembur';
                $absen->update();
            }
        }

        Mail::to($email)->send(new KonfirmasiAbsen($details));

        return redirect()->back()->with('success', 'Berhasil, absen ' . $absen->user->nama . ' diterima');
    }

    public function tolakKehadiran($status, $id)
    {

        $absen = Absen::find($id);

        $email = $absen->user->email;

        if ($status == 'masuk') {

            $details = [

                'title' => 'email dari absensi.kanim',

                'body' => 'Absens masuk ditolak, silahkan absen ulang'

            ];

            $destination = public_path('assets/images/absens/' . $absen->foto_masuk);

            if (File::exists($destination)) {
                File::delete($destination);
            }

            $absen->delete();
        } else {

            $details = [

                'title' => 'email dari absensi.kanim',

                'body' => 'Absens pulang ditolak, silahkan absen ulang'

            ];

            $destination = public_path('assets/images/absens/' . $absen->foto_pulang);

            if (File::exists($destination)) {
                File::delete($destination);
            }

            $absen->date_pulang = null;
            $absen->status_pulang = null;
            $absen->foto_pulang = null;
            $absen->save();
        }

        Mail::to($email)->send(new KonfirmasiAbsen($details));

        return redirect()->back()->with('success', 'Absen ditolak Email telah dikirim ke ' . $absen->user->nama . ' untuk absen ulang');
    }

    public function konfirmasiPulang()
    {
        $absen = Absen::where('status_pulang', 'absen pulang')
            ->orWhere('status_pulang', 'absen lembur')
            ->get();

        $data = [
            'absens' => $absen,
        ];

        return view('admin.konfirmasi-pulang', $data);
    }

    public function fotoPulang($id)
    {
        $absen = Absen::find($id);

        $foto = $absen->foto_pulang;

        return view('admin.foto-kehadiran', ['fotoPulang' => $foto]);
    }

    public function waktuAbsensi()
    {
        $data1 = Time::where('id', '1')->first();
        $data2 = Time::where('id', '2')->first();

        return view('admin.waktu-absensi', ['masuk' => $data1, 'pulang' => $data2]);
    }

    public function waktuAbsensiUpdate(Request $request, $id)
    {
        $data = Time::findOrFail($id);

        $data->mulai = $request->mulai;
        $data->selesai = $request->selesai;

        $data->update();

        return redirect()->back();
    }

    public function tabelKehadiran()
    {
        $user = User::get();

        return view('admin.tabel-kehadiran', ['users' => $user]);
    }

    public function detailKehadiran(Request $request, $id)
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

        return view('admin.detail-kehadiran');
    }

    public function konfirmasiCuti()
    {
        $cuti = Cuti::where('status', false)->get();

        return view('admin.konfirmasi-cuti', ['cutis' => $cuti]);
    }

    public function fotoCuti($id)
    {
        $cuti = Cuti::find($id);

        $foto = $cuti->foto;

        return view('admin.foto-kehadiran', ['fotoCuti' => $foto]);
    }

    public function terimaCuti($id)
    {
        $cuti = Cuti::findOrFail($id);

        $email = $cuti->user->email;

        $cuti->status = true;

        $details = [

            'title' => 'email dari absensi.kanim',

            'body' => 'Cuti diterima'

        ];

        $cuti->save();

        Mail::to($email)->send(new KonfirmasiCuti($details));

        return redirect()->back()->with('success', 'Berhasil, permohonan cuti ' . $cuti->user->nama . ' diterima');
    }

    public function tolakCuti($id)
    {

        $cuti = Cuti::find($id);

        $email = $cuti->user->email;

        $details = [

            'title' => 'email dari absensi.kanim',

            'body' => 'Cuti ditolak, silahkan ajukan ulang'

        ];

        $destination = public_path('assets/images/cuti/' . $cuti->foto);

        if (File::exists($destination)) {
            File::delete($destination);
        }

        $cuti->delete();

        Mail::to($email)->send(new KonfirmasiCuti($details));

        return redirect()->back()->with('success', 'Cuti ditolak! Email telah dikirim ke ' . $cuti->user->nama . ' untuk ajukan cuti ulang');
    }

    public function rekapKehadiran()
    {

        return view('admin.rekap-kehadiran');
    }

    public function rekapKehadiranData(Request $request)
    {
        $bulan = $request->bulan;
        $users = User::all();

        // foreach ($users as $user) {


        //   $a = Absen::where('user_id', $user->id)->where(function ($query) {
        //     $query->where('status_masuk', 'terlambat');
        //   })->get();

        //       foreach ($a as $hadir) {
        //         if ($hadir->created_at->month == $bulan) {
        //           $kehadiran = 0;
        //           $kehadiran =  $kehadiran + 1;

        //         }

        //       }



        // }


        // dd($request->bulan);

        // dd($bulan);


        return view('admin.rekap-kehadiran', ['users' => $users, 'bulan' => $bulan]);
    }

    public function exportAbsen($tipe, $bulan)
    {
      $users = User::all();
      if ($tipe == "excel") {
        $nama_file = 'rekap_absen_' . $bulan . '.xlsx';

        return Excel::download(new RekapAbsen("exports.rekap-absen", ['users' => $users, 'bulan' => $bulan]), $nama_file);
      } elseif ($tipe == "pdf") {
        $pdf = Pdf::loadView('exports.pdf-rekap-absen', ['users' => $users, 'bulan' => $bulan]);

        return $pdf->download('rekap_absen_' . $bulan . '.pdf');
      }
      return redirect()->route('admin.dashboard');
    }
}
