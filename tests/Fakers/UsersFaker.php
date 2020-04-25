<?php

namespace Okipa\LaravelTable\Test\Fakers;

use Hash;
use Okipa\LaravelTable\Test\Models\User;

trait UsersFaker
{
    public $clearPassword;
    public $data;

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
            'name'     => $this->faker->word(6),
            'email'    => $this->faker->email,
            'password' => Hash::make($this->clearPassword),
        ];
    }
}
