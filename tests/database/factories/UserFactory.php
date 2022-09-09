<?php

namespace Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Tests\Models\User;

class UserFactory extends Factory
{
    /** @var class-string<\Illuminate\Database\Eloquent\Model> */
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'email' => $this->faker->unique()->email(),
            'password' => Hash::make('secret'),
            'active' => $this->faker->boolean(),
            'email_verified_at' => $this->faker->dateTime(),
        ];
    }
}
