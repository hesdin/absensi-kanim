<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Cuti;
use App\Models\Time;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DataController extends Controller
{
  public function statusAbsen($id)
  {
    $dataCuti = Cuti::where('user_id', $id)->whereDate('date', '>=', Carbon::today())->first();

    if ($dataCuti && $dataCuti->status == 1) {
      return response()->json([
        'status' => "cuti",
        'hingga' => Carbon::parse($dataCuti->date)->isoFormat('D MMMM YYYY')
      ], 200);
    }

    $jadwal_masuk = Time::find(1);

    $jadwal_masuk_mulai = $jadwal_masuk->mulai;
    $jadwal_masuk_mulai = Carbon::createFromTimeString($jadwal_masuk_mulai);
    $jadwal_masuk_mulai = Carbon::now()->gt($jadwal_masuk_mulai);

    if (!$jadwal_masuk_mulai) {
      return response()->json([
        'status' => 'absen masuk belum dibuka'
      ], 200);
    }

    $statusMasuk = Absen::where('user_id', $id)->whereDate('date_masuk', Carbon::today())->first();
    if ($statusMasuk) {
      $statusPulang = Absen::where('user_id', $id)->whereDate('date_pulang', Carbon::today())->first();
      if ($statusPulang) {
        return response()->json([
          'status' => 'sudah pulang'
        ], 200);
      }

      $jadwal_pulang = Time::find(2);
      $jadwal_pulang_mulai = $jadwal_pulang->mulai;
      $jadwal_pulang_mulai = Carbon::createFromTimeString($jadwal_pulang_mulai);
      $jadwal_pulang_mulai = Carbon::now()->gt($jadwal_pulang_mulai);

      if (!$jadwal_pulang_mulai) {
        return response()->json([
          'status' => 'absen pulang belum dibuka'
        ], 200);
      }

      return response()->json([
        'status' => 'hadir'
      ], 200);
    }

    return response()->json([
      'status' => 'belum absen'
    ], 200);
  }

  public function absenMasuk(Request $req)
  {
    $date = Carbon::now();

    $file = $req->file('foto');
    if ($file) {
      $filename = 'masuk-'.uniqid().'.'. $file->getClientOriginalExtension();
    }

    $jadwal_masuk = Time::find(1);
    $jadwal_masuk_selesai = $jadwal_masuk->selesai;
    $jadwal_masuk_selesai = Carbon::createFromTimeString($jadwal_masuk_selesai);
    $jadwal_masuk_selesai = $date->gt($jadwal_masuk_selesai);

    $absen = new Absen();
    $absen->user_id = $req->user()->id;
    $absen->date_masuk = Carbon::now();
    if ($jadwal_masuk_selesai === true) {
      $absen->status_masuk = 'absen terlambat';
    } else {
      $absen->status_masuk = 'absen hadir';
    }
    if($file) {
      $absen->foto_masuk = $filename;
      $file->move(public_path('assets/images/absens/'), $filename);
      
        // File and new size
        $source = public_path('assets/images/absens/'.$filename);
        $dest = public_path('assets/images/absens/'.$filename);
        $percent = 0.2;
        
        // Content type
        header('Content-Type: image/jpeg');
        
        // Get new sizes
        list($width, $height) = getimagesize($source);
        $newwidth = $width * $percent;
        $newheight = $height * $percent;
    
        // Load
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        $source = imagecreatefromjpeg($source);
    
        // Resize
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    
        $destination = public_path('assets/images/absens/'.$filename);
        if (File::exists($destination)) {
            File::delete($destination);
        }
    
        // Save Resized Image
        imagejpeg($thumb, $dest);
    }

    $absen->save();

    return response()->json([
      'message' => 'absen berhasil'
    ], 200);
  }

  public function absenPulang(Request $req)
  {
    $date = Carbon::now();

    $file = $req->file('foto');
    if ($file) {
      $filename = 'pulang-'.uniqid().'.'. $file->getClientOriginalExtension();
    }

    $jadwal_pulang = Time::find(2);
    $jadwal_pulang_selesai = $jadwal_pulang->selesai;
    $jadwal_pulang_selesai = Carbon::createFromTimeString($jadwal_pulang_selesai);
    $jadwal_pulang_selesai = $date->gt($jadwal_pulang_selesai);

    $absen = Absen::where('user_id', $req->user()->id)
            ->orderBy('id', 'DESC')
            ->first();
    $absen->user_id = $req->user()->id;
    $absen->date_pulang = Carbon::now();
    if ($jadwal_pulang_selesai === true) {
      $absen->status_pulang = 'absen lembur';
    } else {
      $absen->status_pulang = 'absen pulang';
    }
    if($file) {
        $absen->foto_pulang = $filename;
        $file->move(public_path('assets/images/absens/'), $filename);
        
        // File and new size
        $source = public_path('assets/images/absens/'.$filename);
        $dest = public_path('assets/images/absens/'.$filename);
        $percent = 0.2;
        
        // Content type
        header('Content-Type: image/jpeg');
        
        // Get new sizes
        list($width, $height) = getimagesize($source);
        $newwidth = $width * $percent;
        $newheight = $height * $percent;
    
        // Load
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        $source = imagecreatefromjpeg($source);
    
        // Resize
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    
        $destination = public_path('assets/images/absens/'.$filename);
        if (File::exists($destination)) {
            File::delete($destination);
        }
    
        // Save Resized Image
        imagejpeg($thumb, $dest);
    }

    $absen->save();

    return response()->json([
      'message' => 'absen berhasil'
    ], 200);
  }

  public function dataAbsen($id)
  {
    $data = Absen::where('user_id', $id)->get();

    return response()->json([
      'data' => $data
    ], 200);
  }

  public function statusCuti($id)
  {
    // dd(User::has('cuti')->get());
    $dataCuti = Cuti::where('user_id', $id)->whereDate('date', '>=', Carbon::today())->first();

    // dd($dataCuti);
    if ($dataCuti) {
      if ($dataCuti->status == 0) {
        return response()->json([
          'status' => "cuti pending"
        ], 200);
      }
      return response()->json([
        'status' => "sedang cuti",
        'hingga' => Carbon::parse($dataCuti->date)->isoFormat('D MMMM YYYY')
      ], 200);
    }

    return response()->json([
      'status' => 'tidak cuti'
    ], 200);
  }

  public function kirimCuti(Request $req)
  {
    $a = Absen::where('user_id', $req->user()->id)->whereDate('created_at', Carbon::today())->first();
    if ($a) {
      $a->delete();
    }

    $file = $req->file('foto');
    if ($file) {
      $filename = 'cuti-' . uniqid() .'.'. $file->getClientOriginalExtension();
    }

    $cuti = new Cuti();
    $cuti->user_id = $req->user()->id;
    $cuti->jc = $req->jc;
    $cuti->date = $req->date;
    if($file) {
      $cuti->foto = $filename;
      $file->move(public_path('assets/images/cuti/'), $filename);
    }
    $cuti->save();

    return response()->json([
      'message' => 'berhasil'
    ], 200);
  }

  public function updateProfile(Request $req)
  {
    $file = $req->file('avatar');
    if ($file) {
      $filename = 'user-'.uniqid().'.'. $file->getClientOriginalExtension();
    }
    $u = User::find($req->user()->id);
    $u->email = $req->email;
    if ($req->password) {
      $u->password = bcrypt($req->password);
    }
    $u->nip = $req->nip;
    $u->nama = $req->nama;
    $u->jk = $req->jk;
    $u->seksi = $req->seksi;
    $u->jabatan = $req->jabatan;
    if ($file) {
      $u->foto = $filename;
      $file->move(public_path('assets/images/profiles/'), $filename);
    }
    $u->save();

    return response()->json([
      'message' => 'berhasil'
    ], 200);
  }
}
