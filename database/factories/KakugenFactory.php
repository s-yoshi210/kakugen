<?php

namespace Database\Factories;

use App\Models\Kakugen;
use Illuminate\Database\Eloquent\Factories\Factory;

class KakugenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Kakugen::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'content' => $this->faker->realText(50),
            'person_name' => $this->faker->name,
        ];
    }
}
