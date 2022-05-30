<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Wilayah;
use Illuminate\Database\Seeder;

class PerusahaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jabatanUmum = new Jabatan();
        $jabatanUmum->nama = 'Staf';
        $jabatanUmum->save();

        $jabatanUmum = new Jabatan();
        $jabatanUmum->nama = 'Owner';
        $jabatanUmum->save();
    }
}
