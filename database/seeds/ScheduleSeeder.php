<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $period = DB::table('periods')->select('id')->first();

        DB::table('schedules')->insert([
            'period_id'=> 1,
            'name' => 'Horario 1er Semestre',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('schedules')->insert([
            'period_id'=> 3,
            'name' => 'Horario 1er sem fotos',
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
