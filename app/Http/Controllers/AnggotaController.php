<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Traits\CrudTrait;

class AnggotaController extends Controller
{
    use CrudTrait;

    public function __construct()
    {
        $this->route = 'anggota';
        $this->sort = 'nama';
        $this->plural = 'true';
        $this->upload = 'true';
        $this->manyToMany = ['role'];
        $this->relations = ['user'];
        $this->extraFrom = ['user'];
        $this->middleware('permission:view-' . $this->route, ['only' => ['index']]);
        $this->middleware('permission:create-' . $this->route, ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-' . $this->route, ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-' . $this->route, ['only' => ['delete']]);
    }

    public function configHeaders()
    {
        return [
            [
                'name'    => 'nama',
                'alias'    => 'Nama Anggota',
            ],
            [
                'name'    => 'nik',
                'alias'    => 'Nomor KTP',
            ],
            [
                'name'    => 'no_hp',
                'alias'    => 'No HP',
            ],
            [
                'name'    => 'alamat',
                'alias'    => 'Alamat',
            ],
        ];
    }
    public function configSearch()
    {
        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Anggota',
                'value'    => null
            ],
        ];
    }
    public function configForm()
    {

        return [
            [
                'name'    => 'nama',
                'input'    => 'text',
                'alias'    => 'Nama Anggota',
                'validasi'    => ['required', 'min:1'],
            ],
            [
                'name'    => 'nik',
                'input'    => 'text',
                'alias'    => 'Nomor KTP / NIK',
                'validasi'    => ['required', 'min:1', 'unique'],
            ],
            [
                'name'    => 'no_hp',
                'input'    => 'text',
                'alias'    => 'No HP',
                'validasi'    => ['required', 'min:1', 'unique'],
            ],
            [
                'name'    => 'alamat',
                'input'    => 'textarea',
                'alias'    => 'Alamat',
                'validasi'    => ['required'],
            ],
            [
                'name'    => 'username',
                'alias'    => 'Username',
                'validasi'    => ['required', 'unique', 'min:3', 'plural'],
                'extraForm' => 'user',
            ],
            [
                'name'    => 'password',
                'alias'    => 'Password',
                'input'    => 'password',
                'validasi'    => ['required', 'min:8'],
                'extraForm' => 'user',
            ],
            [
                'name'    => 'email',
                'alias'    => 'Email',
                'input'    => 'email',
                'validasi'    => ['required',  'plural', 'unique', 'email'],
                'extraForm' => 'user',
            ],
            [
                'name'    => 'role_id',
                'input'    => 'combo',
                'alias'    => 'Hak Akses',
                'value' => $this->combobox('Role', 'slug', 'anggota', '='),
                'validasi'    => ['required'],
                'extraForm' => 'user',
                'hasMany'    => ['role'],
            ]
        ];
    }

    public function model()
    {
        return new Anggota();
    }
}
