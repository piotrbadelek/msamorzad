<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ValentinesDayMessage>
 */
class ValentinesDayMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
			"recipient" => $this->faker->name(),
			"content" => $this->faker->text(random_int(32, 512)),
			"user_id" => random_int(1, 5)
        ];
    }
}
