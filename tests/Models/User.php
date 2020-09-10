<?php

namespace Okipa\LaravelTable\Test\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /** @var array */
    protected $table = 'users_test';

    /** @var array */
    protected $fillable = ['name', 'email', 'password'];

    /** @var array */
    protected $hidden = ['password'];
}
