<?php

namespace App\Http\Controllers;

use App\Models\Aduan;
use App\Models\Anggota;
use App\Models\PelaksanaanPekerjaan;
use App\Models\Pembayaran;
use App\Models\Rekanan;
use Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title =  "Dashboard";

        $anggota = 0;
        $pegawai = 0;
        $rapat = 0;
        $jenisRapat = 0;

        $start = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
        $end = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
        $tahun = Carbon::now()->formatLocalized("%Y");
        $aduanPerbulan = [];
        $getAduanPerbulan = [];
        $aduanPerbulanGrafik = [];

        // $aduan =  Aduan::selectRaw('year(created_at) as tahun, month(created_at) as bulan, count(*) as jumlah')
        //     ->whereYear('created_at', $tahun)
        //     ->groupBy('tahun', 'bulan')
        //     ->orderBy('tahun', 'desc')
        //     ->get();

        $anggota = Anggota::count();
        $pembayaran = Pembayaran::sum('total_bayar');

        return view('home.index', compact(
            'title',
            'pegawai',
            'aduanPerbulanGrafik',
            'anggota',
            'pembayaran',
            'rapat',
            'jenisRapat'
        ));
    }
}
