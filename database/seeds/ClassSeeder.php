<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schedule = DB::table('schedules')->select('id')->first();
        $subject = DB::table('subjects')->select('id')->first();

        DB::table('classes')->insert([
            'subject_id' => 2,
            'schedule_id' => 1,
            'name' => 'Teoria Inglés',
            'classroom'=>'Q3/1003',
            'start_time' => date("H:i:s"),
            'end_time' => date("H:i:s",strtotime('+2 hours')),
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('classes')->insert([
            'subject_id' => 3,
            'schedule_id' => 2,
            'name' => 'Teoria Ángulos',
            'classroom'=>'Sala 1',
            'start_time' => date("H:i:s"),
            'end_time' => date("H:i:s",strtotime('+1 hours')),
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
