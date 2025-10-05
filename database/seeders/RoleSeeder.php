<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Role Admin
        DB::table('roles')->updateOrInsert(
            ['id' => 1],
            ['nama_role' => 'Administrator']
        );

        // Role Petugas PST
        DB::table('roles')->updateOrInsert(
            ['id' => 2],
            ['nama_role' => 'Petugas PST']
        );

        // Role Kepala
        DB::table('roles')->updateOrInsert(
            ['id' => 3],
            ['nama_role' => 'Kepala BPS']
        );
    }
}
