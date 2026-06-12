<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CalculationFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'deceased_name' => $this->faker->name(),
            'total_assets' => $this->faker->randomFloat(2, 10000, 1000000),
            'heirs' => json_encode([
                [
                    'name' => $this->faker->name(),
                    'relationship' => 'spouse',
                    'share' => '1/4',
                    'share_value' => 0.25
                ],
                [
                    'name' => $this->faker->name(),
                    'relationship' => 'son',
                    'share' => 'residue',
                    'share_value' => 0.75
                ]
            ]),
            'distribution' => json_encode([
                [
                    'name' => $this->faker->name(),
                    'relationship' => 'spouse',
                    'share' => '1/4',
                    'amount' => $this->faker->randomFloat(2, 2500, 250000)
                ],
                [
                    'name' => $this->faker->name(),
                    'relationship' => 'son',
                    'share' => 'residue',
                    'amount' => $this->faker->randomFloat(2, 7500, 750000)
                ]
            ]),
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}