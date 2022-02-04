<?php

namespace Okipa\LaravelTable\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Okipa\LaravelTable\Tests\Database\Factories\UserFactory;

class User extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'users';

    /** @var array<int, string> */
    protected $fillable = ['name', 'email', 'password'];

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
