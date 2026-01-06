<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\LaporanKerusakanController;
use App\Http\Controllers\LaporanPerbaikanController;
use App\Http\Controllers\RekapController;

/*
|--------------------------------------------------------------------------
| PUBLIC
|--------------------------------------------------------------------------
*/
// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| AUTHENTICATED
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // ================= DASHBOARD =================
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Admin')->group(function () {

        // USER
        Route::get('/admin/user', [UserController::class, 'index'])->name('admin.user');
        Route::post('/admin/user/store', [UserController::class, 'store'])->name('admin.user.store');
        Route::put('/admin/user/update/{id}', [UserController::class, 'update'])->name('admin.user.update');
        Route::delete('/admin/user/delete/{id}', [UserController::class, 'destroy'])->name('admin.user.delete');

        // BUS
        Route::get('/admin/bus', [BusController::class, 'index'])->name('admin.bus');
        Route::post('/admin/bus/store', [BusController::class, 'store'])->name('admin.bus.store');
        Route::put('/admin/bus/update/{id}', [BusController::class, 'update'])->name('admin.bus.update');
        Route::delete('/admin/bus/delete/{id}', [BusController::class, 'destroy'])->name('admin.bus.delete');
    });

    /*
    |--------------------------------------------------------------------------
    | LAPORAN KERUSAKAN (Teknisi, Dispatcher, Manajer)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Teknisi,Dispatcher,Manajer')->group(function () {

        Route::get('/laporan-kerusakan', [LaporanKerusakanController::class, 'index'])
            ->name('laporan_kerusakan.index');

        Route::get('/laporan-kerusakan/{id}', [LaporanKerusakanController::class, 'show'])
            ->name('laporan_kerusakan.show');

        Route::get('/laporan-perbaikan', [LaporanPerbaikanController::class, 'index'])
            ->name('laporan_perbaikan.index');

        Route::get('/laporan-perbaikan/{id}', [LaporanPerbaikanController::class, 'show'])
            ->name('laporan_perbaikan.show');
    });

    /*
    |--------------------------------------------------------------------------
    | TEKNISI
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Teknisi')->group(function () {

        Route::post('/laporan-kerusakan', [LaporanKerusakanController::class, 'store'])
            ->name('laporan.store');

        Route::put('/laporan-perbaikan/{id}/update-status', [LaporanPerbaikanController::class, 'updateStatus'])
            ->name('perbaikan.updateStatus');

        // Kirim hasil perbaikan
        Route::put('/laporan-perbaikan/{id}/hasil', [LaporanPerbaikanController::class, 'submitHasil'])
            ->name('perbaikan.submitHasil');
    });

    /*
    |--------------------------------------------------------------------------
    | DISPATCHER
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Dispatcher')->group(function () {

        Route::put('/laporan-kerusakan/{id}/validasi', [LaporanKerusakanController::class, 'validasi'])
            ->name('laporan.validasi');

        Route::put('/laporan-kerusakan/{id}/jadwalkan', [LaporanKerusakanController::class, 'jadwalkan'])
            ->name('laporan.jadwalkan');

         Route::post('/laporan-perbaikan', [LaporanPerbaikanController::class, 'store'])
            ->name('laporan-perbaikan.store');

        Route::put('/laporan-perbaikan/{id}/selesai', [LaporanPerbaikanController::class, 'setSelesai'])
            ->name('perbaikan.setSelesai');

         Route::get('/api/teknisi-schedule/{id}', [LaporanPerbaikanController::class,'teknisiSchedule'])
            ->middleware('auth');

    });

    /*
    |--------------------------------------------------------------------------
    | MANAJER
    |--------------------------------------------------------------------------
    */
        Route::middleware(['auth', 'role:Manajer'])->group(function () {

        Route::get('/rekap', [RekapController::class, 'index'])
            ->name('rekap.index');

        Route::get('/rekap/export/pdf', [RekapController::class, 'exportPdf'])
            ->name('rekap.export.pdf');

        Route::get('/rekap/export/csv', [RekapController::class, 'exportCsv'])
            ->name('rekap.export.csv');

        Route::get('/rekap/detail/{id}', [RekapController::class, 'detailRekap'])
            ->name('rekap.detail');


    });

    /*
    |--------------------------------------------------------------------------
    | LAPORAN PERBAIKAN (Dispatcher, Teknisi, Manajer)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:Dispatcher,Teknisi,Manajer')->group(function () {

        Route::get('/laporan-perbaikan', [LaporanPerbaikanController::class, 'index'])
            ->name('laporan_perbaikan.index');

        Route::get('/laporan-perbaikan/{id}', [LaporanPerbaikanController::class, 'show'])
            ->name('laporan_perbaikan.show');
    });

});
