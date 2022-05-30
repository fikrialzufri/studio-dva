<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory, UsesUuid;
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

    public function hasAnggota()
    {
        return $this->belongsTo(Anggota::class, 'anggota_id');
    }
}
