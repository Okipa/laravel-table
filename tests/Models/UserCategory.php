<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tests\Database\Factories\UserCategoryFactory;
use Tests\Database\Factories\UserFactory;

class UserCategory extends Authenticatable
{
    use HasFactory;

    /** @var string */
    protected $table = 'user_categories';

    /** @var array */
    protected $fillable = ['name'];

    protected static function newFactory(): UserCategoryFactory
    {
        return UserCategoryFactory::new();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
