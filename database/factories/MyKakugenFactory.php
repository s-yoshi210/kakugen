<?php

namespace Database\Factories;

use App\Models\Kakugen;
use App\Models\MyKakugen;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MyKakugenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MyKakugen::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'kakugen_id' => Kakugen::factory(),
            'favorite' => $this->faker->boolean(10),
            'comment' => $this->faker->realText(200)
        ];
    }
}
