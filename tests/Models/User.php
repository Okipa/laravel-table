<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tests\Database\Factories\UserFactory;

class User extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'users';

    /** @var array */
    protected $fillable = ['name', 'email', 'password'];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
