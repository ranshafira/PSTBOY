<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['id' => 1, 'nama_role' => 'Admin'],
            ['id' => 2, 'nama_role' => 'Petugas PST'],
        ]);
    }
}