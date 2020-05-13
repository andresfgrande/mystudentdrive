<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $section = DB::table('sections')->select('id')->first();

        DB::table('files')->insert([
            'section_id' => 1,
            'name' => 'Tema1.pdf',
            'file_path' => '.../.../',
            'created_at' => date("Y-m-d H:i:s"),
            'access_code' => bcrypt('password')
        ]);
        DB::table('files')->insert([
            'section_id' => 3,
            'name' => 'Tema_luces_1.pdf',
            'file_path' => '.../.../',
            'created_at' => date("Y-m-d H:i:s"),
            'access_code' => bcrypt('password')
        ]);
    }
}
