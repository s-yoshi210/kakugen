<?php

namespace Database\Seeders;

use App\Models\Kakugen;
use Illuminate\Database\Seeder;

class KakugenTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Kakugen::factory(100)->create();
    }
}
