<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $study = DB::table('studies')->select('id')->first();

        DB::table('academic_years')->insert([
            'study_id' => 1,
            'start_date'=> date("Y/m/d"),
            'end_date'=> date("Y/m/d",strtotime('+6 month')),
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('academic_years')->insert([
            'study_id' => 2,
            'start_date'=> date("Y/m/d"),
            'end_date'=> date("Y/m/d",strtotime('+6 month')),
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('academic_years')->insert([
            'study_id' => 2,
            'start_date'=> date("Y/m/d",strtotime('+7 month')),
            'end_date'=> date("Y/m/d",strtotime('+8 month')),
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('academic_years')->insert([
            'study_id' => 3,
            'start_date'=> date("Y/m/d",strtotime('+5 month')),
            'end_date'=> date("Y/m/d",strtotime('+6 month')),
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
