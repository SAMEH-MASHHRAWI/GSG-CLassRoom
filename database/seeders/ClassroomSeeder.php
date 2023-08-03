<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('classrooms')->insert([
            'name'=>'laravel',
            'section'=>'tecnical',
            'subject'=>'web',
            'room'=>'101',
            'code'=>'ssa1',
        ]);
    }
}
