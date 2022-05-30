<?php

use App\Http\Controllers\Api\AduanController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\JenisController;
use App\Http\Controllers\Api\JenisAduanController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\PenunjukanPekerjaanController;
use App\Http\Controllers\Api\RekananController;
use App\Http\Controllers\Api\PelaksanaanPekerjaanController;
use App\Http\Controllers\Api\TagihanController;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;


Route::get(
    '/csrf-cookie',
    CsrfCookieController::class . '@show'
)->middleware('web')->name('auth.cookies');


Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('me', AuthController::class . '@me')->name('auth.me');
    Route::get('refresh', AuthController::class . '@refresh')->name('auth.refresh');

    Route::get('notification', [NotifikasiController::class, 'all'])->name('notification.api');
    Route::get('notification/{id}', [NotifikasiController::class, 'detail'])->name('notification.api.detail');
});
Route::post('login', [AuthController::class, 'login'])->name('auth.login');
