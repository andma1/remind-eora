<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    protected $fillable = [
        'name'
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
