<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $skladki = ["Rolety", "Samorząd", "Wycieczka", "doneky konga", "Yoshi-Plushie-Kaufen"];
        return [
            "amount" => rand(1, 200),
            "title" => $skladki[array_rand($skladki)],
            "classunit_id" => rand(1, 5),
            "deadline" => date('Y-m-d H:i:s',time() + 604800),
            "paid" => "[]"
        ];
    }
}
