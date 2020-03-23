<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subject = DB::table('subjects')->select('id')->first();

        DB::table('sections')->insert([
            'subject_id' => 1,
            'name' => 'Prácticas',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('sections')->insert([
            'subject_id' => 1,
            'name' => 'Teoria',
            'created_at' => date("Y-m-d H:i:s")
        ]);
        DB::table('sections')->insert([
            'subject_id' => 3,
            'name' => 'Manuales del fotógrafo',
            'created_at' => date("Y-m-d H:i:s")
        ]);
    }
}
