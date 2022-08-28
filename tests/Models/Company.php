<?php

namespace Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\Database\Factories\CompanyFactory;

class Company extends Model
{
    use HasFactory;

    /** @var string */
    protected $table = 'companies';

    /** @var array<int, string> */
    protected $fillable = ['owner_id', 'name', 'position'];

    protected static function newFactory(): CompanyFactory
    {
        return CompanyFactory::new();
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
