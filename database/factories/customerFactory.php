<?php

namespace Database\Factories;

use App\Models\customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\customer>
 */
class customerFactory extends Factory
{
    protected $model = customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'pincode' => $this->faker->postcode(),
            'country' => $this->faker->country()
        ];
    }
}
