<?php

namespace App;

use App\Contracts\ImageOwnerContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function imagesDir(): string
    {
        return self::BASE_DIR . DIRECTORY_SEPARATOR . $this->dir;
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'owner');
    }
}
