<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Task;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class UsersTableSeedeer extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superadmin = new Role();
        $superadmin->name = 'Superadmin';
        $superadmin->save();

        $AnggotaRole = new Role();
        $AnggotaRole->name = 'Anggota';
        $AnggotaRole->save();

        $superadmin = Role::where('slug', 'superadmin')->first();

        $superadminUser = new User();
        $superadminUser->name = 'Superadmin';
        $superadminUser->username = 'Superadmin';
        $superadminUser->email = 'Superadmin@admin.com';
        $superadminUser->password = bcrypt('secret');
        // $superadminUser->icon = 'default-icon.png';
        $superadminUser->save();

        $superadminUser->role()->attach($superadmin);

        $taskUser = new Task();
        $taskUser->name = 'User';
        $taskUser->description = 'Manajemen User';
        $taskUser->save();

        $taskRole = new Task();
        $taskRole->name = 'Roles';
        $taskRole->description = 'Manajemen Hak Akses ';
        $taskRole->save();

        $taskAnggota = new Task();
        $taskAnggota->name = 'Anggota';
        $taskAnggota->description = 'Manajemen Anggota';
        $taskAnggota->save();

        $taskJabatan = new Task();
        $taskJabatan->name = 'Jabatan';
        $taskJabatan->description = 'Manajemen Jabatan';
        $taskJabatan->save();

        $taskKaryawan = new Task();
        $taskKaryawan->name = 'Karyawan';
        $taskKaryawan->description = 'Manajemen Karyawan';
        $taskKaryawan->save();

        $taskAnggota = new Task();
        $taskAnggota->name = 'Anggota';
        $taskAnggota->description = 'Manajemen Anggota';
        $taskAnggota->save();

        $taskpembayaran = new Task();
        $taskpembayaran->name = 'Pembayaran';
        $taskpembayaran->description = 'Manajemen Pembayaran';
        $taskpembayaran->save();

        $tasks = Task::all();

        foreach ($tasks as $task) {
            $name = $task->name;
            $data = array(

                [
                    'name'    => 'View ' . $name,
                    'task_id' => $task->id
                ],
                [
                    'name'    => 'Create ' . $name,
                    'task_id' => $task->id
                ],
                [
                    'name'    => 'Edit ' . $name,
                    'task_id' => $task->id
                ],
                [
                    'name'    => 'Delete ' . $name,
                    'task_id' => $task->id
                ],
            );

            foreach ($data as $induk) {
                $Permission = Permission::Create($induk);
            }
        }
    }
}
