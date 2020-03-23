<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 10)->create();

        factory(User::class)->create([
            'name' => 'andres',
            'email' => 'andres@gmail.com',
            'surnames' => 'grande nunez',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),//'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ]);
    }
}
