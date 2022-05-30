<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Traits\CrudTrait;

class PembayaranController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'pembayaran';
        $this->middleware('permission:view-' . $this->route, ['only' => ['index', 'show']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'Anggota',
                'alias'    => 'Nama Anggota',
            ],
            [
                'name'    => 'total_bayar',
                'input'    => 'rupiah',
                'alias'    => 'Total Bayar',
            ],
            [
                'name'    => 'tanggal',
                'alias'    => 'Tanggal Bayar',
            ]
        ];
    }
    public function configSearch()
    {
        return [

            [
                'name'    => 'anggota_id',
                'input'    => 'combo',
                'alias'    => 'Anggota',
                'value' => $this->combobox(
                    'Anggota'
                )
            ],
            [
                'name'    => 'created_at',
                'input'    => 'daterange',
                'alias'    => 'Tanggal',
            ],
        ];
    }
    public function configForm()
    {

        return [
            [
                'name'    => 'total_bayar',
                'input'    => 'rupiah',
                'alias'    => 'Total Bayar',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'anggota_id',
                'input'    => 'combo',
                'alias'    => 'Anggota',
                'value' => $this->combobox(
                    'anggota'
                ),
                'validasi'    => ['required'],
            ],
        ];
    }

    public function model()
    {
        return new Pembayaran();
    }
}
