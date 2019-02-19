<?php

namespace Okipa\LaravelTable\Test\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'companies_test';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'owner_id',
        'turnover'
    ];
    
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
