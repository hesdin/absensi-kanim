<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Models\Admin;
use App\Models\Cuti;
use App\Models\Time;
use Illuminate\Support\Carbon;

Route::get('/', function () {
  if (!Time::find(1)) {
    $t = new Time();
    $t->mulai = '08:00';
    $t->selesai = '09:00';
    $t->ket = "Masuk";
    $t->save();
  }
  if (!Time::find(2)) {
    $t = new Time();
    $t->mulai = '16:00';
    $t->selesai = '23:59';
    $t->ket = "Pulang";
    $t->save();
  }

  if (Admin::all()->count() < 1) {
    $a = new Admin();
    $a->nama = "Admin";
    $a->jk = "L";
    $a->foto = "profile.png";
    $a->email = "admin@localhost";
    $a->password = bcrypt("123");
    $a->save();
  }

    return redirect()->route('login');
});

Route::get('/admin', function () {
    return redirect()->route('admin.login');
});


Route::middleware(['guest:web'])->group(function(){
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginCheck'])->name('login-check');
});

Route::middleware(['auth:web'])->group(function(){
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/profile/{id}', [UserController::class, 'profileUpdate'])->name('profileUpdate');
    Route::get('/absen', [UserController::class, 'absen'])->name('absen');
    Route::post('/absen/{id}', [UserController::class, 'absenStore'])->name('absen.store');
    Route::put('/absen/{id}', [UserController::class, 'absenUpdate'])->name('absen.update');

    Route::get('/tabel-absen/{id}', [UserController::class, 'tabelAbsen'])->name('tabel.absen');
    Route::get('/rekap-absen/{id}', [UserController::class, 'rekapAbsen'])->name('rekap.absen');

    Route::get('/permohonan-cuti', [UserController::class, 'permohonanCuti'])->name('permohonan.cuti');
    Route::post('/permohonan-cuti/{id}', [UserController::class, 'permohonanCutiStore'])->name('permohonan.cuti.store');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::prefix('admin')->name('admin.')->group(function(){
    Route::middleware(['guest:admin'])->group(function(){
        Route::get('/login', [AuthController::class, 'loginAdmin'])->name('login');
        Route::post('/login', [AuthController::class, 'loginAdminCheck'])->name('login-check');

    });

    Route::middleware(['auth:admin'])->group(function(){
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/data-anggota', [AdminController::class, 'anggota'])->name('data-anggota');
        Route::get('/tambah-anggota', [AdminController::class, 'tambahAnggota']);
        Route::post('/tambah-anggota', [AdminController::class, 'storeAnggota'])->name('store-anggota');
        Route::get('/edit-anggota/{id}', [AdminController::class, 'editAnggota'])->name('edit-anggota');
        Route::put('/edit-anggota/{id}', [AdminController::class, 'updateAnggota'])->name('update-anggota');
        Route::delete('/delete-anggota/{id}', [AdminController::class, 'deleteAnggota'])->name('delete-anggota');

        Route::get('/konfirmasi-kehadiran', [AdminController::class, 'konfirmasiKehadiran'])->name('konfirmasi-kehadiran');
        Route::get('/konfirmasi-kehadiran/foto/{id}', [AdminController::class, 'fotoKehadiran'])->name('foto-kehadiran');
        Route::get('/terima-kehadiran/{status}/{id}', [AdminController::class, 'terimaKehadiran'])->name('terima-kehadiran');
        Route::delete('/tolak-kehadiran/{status}/{id}', [AdminController::class, 'tolakKehadiran'])->name('tolak-kehadiran');

        Route::get('/konfirmasi-pulang', [AdminController::class, 'konfirmasiPulang'])->name('konfirmasi-pulang');
        Route::get('/konfirmasi-pulang/foto/{id}', [AdminController::class, 'fotoPulang'])->name('foto-pulang');
        // Route::get('/terima-pulang/{id}', [AdminController::class, 'terimaPulang'])->name('terima-pulang');
        // Route::delete('/tolak-pulang/{id}', [AdminController::class, 'tolakPulang'])->name('tolak-pulang');

        Route::get('/konfirmasi-cuti', [AdminController::class, 'konfirmasiCuti'])->name('konfirmasi.cuti');
        Route::get('/konfirmasi-cuti/foto/{id}', [AdminController::class, 'fotoCuti'])->name('foto.cuti');
        Route::get('/terima-cuti/{id}', [AdminController::class, 'terimaCuti'])->name('terima.cuti');
        // Route::delete('/tolak-cuti/{id}', [AdminController::class, 'tolakCuti'])->name('tolak.cuti');
        Route::get('/tolak-cuti/{id}', [AdminController::class, 'tolakCuti'])->name('tolak.cuti');


        Route::get('/waktu-absensi', [AdminController::class, 'waktuAbsensi']);
        Route::put('/waktu-absensi/{id}', [AdminController::class, 'waktuAbsensiUpdate'])->name('update-waktu-absensi');

        Route::get('/tabel-kehadiran', [AdminController::class, 'tabelKehadiran']);
        Route::get('/tabel-kehadiran/detail/{id}', [AdminController::class, 'detailKehadiran'])->name('detail-kehadiran');

        Route::get('/rekap-kehadiran', [AdminController::class, 'rekapKehadiran']);
        Route::post('/rekap-kehadiran', [AdminController::class, 'rekapKehadiranData'])->name('rekap.kehadiran');

        // Export Excel

        Route::get('/export-absen/{tipe}/{bulan}', [AdminController::class, 'exportAbsen'])->name('export.absen');

        Route::post('/logout', [AuthController::class, 'logoutAdmin'])->name('logout');

    });
});
