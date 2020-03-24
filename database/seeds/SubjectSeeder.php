<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $period = DB::table('periods')->select('id')->first();

        DB::table('subjects')->insert([
            'period_id' => 1,
            'name' => 'Gestió de Projectes',
            'color' => '#cb3234',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('subjects')->insert([
            'period_id' => 1,
            'name' => 'English II',
            'color' => '#3b83bd',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('subjects')->insert([
            'period_id' => 3,
            'name' => 'Luces',
            'color' => '#008f39',
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
