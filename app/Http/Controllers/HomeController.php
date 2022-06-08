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
        $pembayaranSum = Pembayaran::sum('total_bayar');
        $listPembayaran = Pembayaran::limit(20)->orderBy('created_at', 'desc')->get();

        $month = [];
        $checkBayar = [];
        $PembayaranSetahun = [];
        $PembayaranSetahunGrafik = [];
        $PembayaranPerbulan = [];
        $PembayaranPerbulanGrafik = [];
        $getPembyaranPerbulan = [];

        $anggota_id = auth()->user()->id_anggota;
        $tahun = Carbon::now()->formatLocalized("%Y");

        $PembayaranSetahun =  Pembayaran::selectRaw('year(created_at) as tahun, bulan, count(*) as jumlah, anggota_id')
            ->whereYear('created_at', $tahun);

        if (Auth::user()->hasRole('anggota')) {
            $PembayaranSetahun = $PembayaranSetahun->where('anggota_id', $anggota_id);
        }
        $PembayaranSetahun = $PembayaranSetahun->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->get();

        $month = [];

        for ($m = 1; $m <= 12; $m++) {
            $month[] = bulan_indonesia(date('Y-m-d', mktime(0, 0, 0, $m, 1, date('Y'))));
        }

        foreach ($PembayaranSetahun as $index => $pembayaran) {

            $getPembyaranPerbulan[$pembayaran->bulan] = [
                'bulan' => $pembayaran->bulan,
                'nama' => Anggota::find($pembayaran->anggota_id) != null ? Anggota::find($pembayaran->anggota_id)->nama : '',
                'jumlah' => $pembayaran->jumlah,
            ];
        }

        // return $listPembayaran;

        foreach ($month as $key => $bulan) {

            if (!empty($getPembyaranPerbulan[$bulan])) {
                $PembayaranPerbulan[$key] = $getPembyaranPerbulan[$bulan];
                $PembayaranPerbulanGrafik[$key] = [
                    $bulan,
                    $getPembyaranPerbulan[$bulan]['jumlah']
                ];
            } else {
                $PembayaranPerbulan[$key] = [
                    'bulan' => $bulan,
                    'jumlah' => 0,
                ];
                $PembayaranPerbulanGrafik[$key] = [
                    $bulan, 0
                ];
            }
        };


        return view('home.index', compact(
            'title',
            'pegawai',
            'PembayaranPerbulan',
            'listPembayaran',
            'aduanPerbulanGrafik',
            'anggota',
            'month',
            'pembayaranSum',
            'rapat',
            'jenisRapat'
        ));
    }
}
