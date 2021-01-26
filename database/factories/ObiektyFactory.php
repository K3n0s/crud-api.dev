<?php

namespace Database\Factories;

use App\Models\Obiekty;
use App\Models\Historia;
use Illuminate\Database\Eloquent\Factories\Factory;

class ObiektyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Obiekty::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'numer' => $this->faker->randomDigit,
            'status' => Historia::STATUS_NOWY,
            'created_at' => now()
        ];
    }
}
