<?php

namespace Okipa\LaravelTable\Test\Fakers;

use Illuminate\Support\Facades\Hash;
use Okipa\LaravelTable\Test\Models\User;

trait UsersFaker
{
    /** @var string $clearPassword */
    public $clearPassword;

    public function createMultipleUsers(int $count)
    {
        for ($ii = 0; $ii < $count; $ii++) {
            $this->createUniqueUser();
        }

        return app(User::class)->all();
    }

    public function createUniqueUser()
    {
        $databaseUser = app(User::class)->create($this->generateFakeUserData());

        return app(User::class)->find($databaseUser->id);
    }

    public function generateFakeUserData()
    {
        $this->clearPassword = $this->faker->word;

        return [
            'name' => str_replace(['\''], '', $this->faker->unique()->name()),
            'email' => $this->faker->unique()->email(),
            'password' => Hash::make($this->clearPassword),
        ];
    }
}
