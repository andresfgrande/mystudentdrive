<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = DB::table('users')->select('id')->first();

        DB::table('studies')->insert([
            'name' => 'Ingenieria Informática',
            'user_id' => $user->id,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('studies')->insert([
            'name' => 'Fotografia',
            'user_id' => 2,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('studies')->insert([
            'name' => 'ADE',
            'user_id' => $user->id,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('studies')->insert([
            'name' => 'Disseny Industrial',
            'user_id' => $user->id,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('studies')->insert([
            'name' => 'Medicina',
            'user_id' => $user->id,
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
