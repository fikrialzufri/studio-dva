<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

class Pendaftaran extends Model
{
    use HasFactory, UsesUuid, SoftDeletes;

    protected $table = 'pendaftaran';
    protected $guarded = ['id'];
    protected $appends = ['status_mobile'];
    protected $fillable = [
        'no_pendaftaran',
        'anggota_id',
    ];

    public function setNoPendaftaranAttribute($value)
    {
        $this->attributes['no_pendaftaran'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
