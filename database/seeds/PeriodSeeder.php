<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $academic_year = DB::table('academic_years')->select('id')->first();

        DB::table('periods')->insert([
            'academic_year_id' => 1,
            'start_date'=> date("Y/m/d"),
            'end_date'=> date("Y/m/d",strtotime('+3 month')),
            'name'=> 'Primer trimestre',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('periods')->insert([
            'academic_year_id' => 1,
            'start_date'=> date("Y/m/d",strtotime('+3 month')),
            'end_date'=> date("Y/m/d",strtotime('+6 month')),
            'name'=> 'segon trimestre',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('periods')->insert([
            'academic_year_id' => 2,
            'start_date'=> date("Y/m/d"),
            'end_date'=> date("Y/m/d",strtotime('+5 month')),
            'name'=> '1er Semestre',
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
