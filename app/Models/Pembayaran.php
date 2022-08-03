<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Pembayaran extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;
    protected $table = "pembayaran";


    public function getAnggotaAttribute()
    {
        if ($this->hasAnggota) {
            return $this->hasAnggota->nama;
        }
    }
    public function getTanggalAttribute()
    {
        if ($this->created_at) {
            return tanggal_indonesia($this->created_at);
        }
    }
    public function getBulanTampilAttribute()
    {
        if ($this->bulan) {
            return  bulan_indonesia(Carbon::create()->addMonths($this->bulan - 1)->year(date('Y')));
        }
    }

    public function hasAnggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }

    public function getNamaAttribute()
    {
        if ($this->hasAnggota) {
            return $this->hasAnggota->nama;
        }
    }
}
