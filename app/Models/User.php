<?php

namespace App\Models;

use App\Traits\HasPermissionsTrait;
use App\Traits\UsesUuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Sanctum\Sanctum;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, UsesUuid, HasPermissionsTrait, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'slug',
        'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tokens()
    {
        return $this->morphMany(Sanctum::$personalAccessTokenModel, 'tokenable', "tokenable_type", "tokenable_uuid");
    }

    public function karyawan()
    {
        return $this->hasOne(Karyawan::class, 'user_id');
    }


    public function getIdWilayahAttribute()
    {
        if ($this->karyawan) {
            return $this->karyawan->id_wilayah;
        }
    }

    public function hasAnggota()
    {
        return $this->hasOne(Anggota::class, 'user_id');
    }

    public function getIdKaryawanAttribute()
    {
        if ($this->karyawan) {
            return $this->karyawan->id;
        }
    }
    public function getIdAnggotaAttribute()
    {
        if ($this->hasAnggota) {
            return $this->hasAnggota->id;
        }
    }
}
