<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $class = DB::table('classes')->select('id')->first();

        DB::table('days')->insert([
            'class_id' => 1,
            'mon' => false,
            'tue' => true,
            'wed' => false,
            'thu' => true,
            'fri' => false,
            'sat'=> false,
            'sun'=> false,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('days')->insert([
            'class_id' => 1,
            'mon' => true,
            'tue' => false,
            'wed' => false,
            'thu' => true,
            'fri' => false,
            'sat'=> false,
            'sun'=> false,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('days')->insert([
            'class_id' => 2,
            'mon' => false,
            'tue' => true,
            'wed' => false,
            'thu' => true,
            'fri' => false,
            'sat'=> false,
            'sun'=> false,
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('days')->insert([
            'class_id' => 2,
            'mon' => false,
            'tue' => true,
            'wed' => true,
            'thu' => false,
            'fri' => false,
            'sat'=> false,
            'sun'=> false,
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
