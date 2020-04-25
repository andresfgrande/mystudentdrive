<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = DB::table('users')->select('id')->first();
        $subject = DB::table('subjects')->select('id')->first();

        DB::table('tasks')->insert([
            'user_id' => 1,
            'subject_id' => 1,
            'description' => 'Apuntarse al SAF.',
            'date' =>  date("Y/m/d"),
            'hour' => date("H:i:s"),
            'is_done' => false,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('tasks')->insert([
            'user_id' => 11,
            'subject_id' => 3,
            'description' => 'Arreglar la cámara.',
            'date' =>  date("Y/m/d"),
            'hour' => date("H:i:s"),
            'is_done' => false,
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
