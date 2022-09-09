<?php

namespace Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\Models\Company;
use Tests\Models\User;

class CompanyFactory extends Factory
{
    /** @var class-string<\Illuminate\Database\Eloquent\Model> */
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'owner_id' => User::inRandomOrder()->first()->id,
            'name' => $this->faker->company(),
            'position' => $this->faker->unique()->numberBetween(1, 100),
        ];
    }

    public function withOwner(User $user): self
    {
        return $this->afterCreating(fn (Company $company) => tap($company)->update(['owner_id' => $user->id]));
    }
}
