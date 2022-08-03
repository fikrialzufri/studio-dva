<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Anggota extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;
    protected $table = "anggota";
    protected $append = "email";

    public function setNoAnggotaAttribute($value)
    {
        $this->attributes['no_anggota'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

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
}
