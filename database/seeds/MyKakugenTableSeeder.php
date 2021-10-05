<?php

namespace Database\Seeders;

use App\Models\MyKakugen;
use Illuminate\Database\Seeder;

class MyKakugenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MyKakugen::factory()
            ->count(30)
            ->create();
    }
}
