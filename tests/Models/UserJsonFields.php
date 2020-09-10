<?php

namespace Okipa\LaravelTable\Test\Models;

use Illuminate\Database\Eloquent\Model;

class UserJsonFields extends Model
{
    /** @var string */
    protected $table = 'users_json_test';

    /** @var array */
    protected $fillable = ['name'];

    /** @var array */
    protected $casts = ['name' => 'json'];
}
