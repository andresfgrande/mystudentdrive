<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlannerEventSeeder extends Seeder
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

        DB::table('planner_events')->insert([
            'user_id' => 2,
            'subject_id' => 1,
            'name' => 'Examen de Ingles',
            'description' => 'Tema 1, 2 y 3.',
            'classroom'=>'Q3/1003',
            'time' => date("H:i:s"),
            'date' =>  date("Y-m-d"),
            'old_event'=> false,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('planner_events')->insert([
            'user_id' => 11,
            'subject_id' => 3,
            'name' => 'Examen de Luces',
            'description' => '1er tema.',
            'classroom'=>'Edifico A',
            'time' => date("H:i:s"),
            'date' =>  date("Y-m-d"),
            'old_event'=> false,
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
