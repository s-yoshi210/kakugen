<?php

use Database\Seeders\UserTableSeeder;
use Database\Seeders\KakugenTableSeeder;
use Database\Seeders\MyKakugenTableSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(KakugenTableSeeder::class);
        $this->call(MyKakugenTableSeeder::class);
    }
}
