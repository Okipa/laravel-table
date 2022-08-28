<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Tests\Database\Factories\UserCategoryFactory;

class UserCategory extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'user_categories';

    /** @var array<int, string> */
    protected $fillable = ['name', 'position'];

    protected static function newFactory(): UserCategoryFactory
    {
        return UserCategoryFactory::new();
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
