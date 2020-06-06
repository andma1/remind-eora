<?php

namespace App;

use App\Contracts\ImageOwnerContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model implements ImageOwnerContract
{
    const BASE_DIR = 'files/results';

    protected $fillable = [
        'name',
        'dir'
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function dir(): string
    {
        return self::BASE_DIR . '/' . $this->dir;
    }
}
