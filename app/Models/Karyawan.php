<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Karyawan extends Model
{
    use HasFactory, UsesUuid;
    protected $table = "karyawan";

    public function hasUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getUsernameAttribute()
    {
        if ($this->hasUser) {
            return $this->hasUser->username;
        }
    }

    public function getEmailAttribute()
    {
        if ($this->hasUser) {
            return $this->hasUser->email;
        }
    }

    public function setNamaAttribute($value)
    {
        $this->attributes['nama'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function hasJabatan()
    {
        return $this->belongsTo(Jabatan::class, 'jabatan_id');
    }

    public function getNamaJabatanAttribute()
    {
        if ($this->hasJabatan) {
            return $this->hasJabatan->nama;
        }
    }
}
