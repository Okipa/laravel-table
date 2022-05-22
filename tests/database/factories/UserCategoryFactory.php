<?php

namespace Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;
use Tests\Models\UserCategory;

class UserCategoryFactory extends Factory
{
    /** @var string */
    protected $model = UserCategory::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->catchphrase(),
        ];
    }

    public function withUsers(Collection $users): self
    {
        $userIds = $users->pluck('id')->toArray();

        return $this->afterCreating(fn(UserCategory $category) => $category->users()->sync($userIds));
    }
}
