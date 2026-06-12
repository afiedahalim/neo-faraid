<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'message' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'is_public' => $this->faker->boolean(),
        ];
    }
}