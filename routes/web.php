<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\SettingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'index'])->name('home');

    //ACL -- Access Control List
    Route::resource('user', UserController::class);
    Route::resource('role', RoleController::class);
    Route::resource('task', TaskController::class);

    // ubah profile
    Route::get('/ubahuser', [UserController::class, 'ubah'])->name('user.ubah');
    Route::put('/simpanuser', [UserController::class, 'simpan'])->name('user.simpan');
    Route::put('/save-token', [UserController::class, 'token'])->name('user.token');
    Route::get('/user-notification', [UserController::class, 'notification'])->name('user.notification');


    // Karyawan

    Route::resource('jabatan', JabatanController::class);
    Route::resource('karyawan', KaryawanController::class);

    // anggota
    Route::resource('anggota', AnggotaController::class);

    // pembayaran
    Route::resource('pembayaran', PembayaranController::class);

    // Setting
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::post('/setting', [SettingController::class, 'store'])->name('setting.store');
});
