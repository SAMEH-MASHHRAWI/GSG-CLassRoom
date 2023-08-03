<?php

namespace Database\Seeders;

use table;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'sameh',
            'email' => 'samehmash@mash.com',
            'password' => Hash::make('password'),
        ]);
    }
}
