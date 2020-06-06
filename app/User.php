<?php

namespace App;

use App\Contracts\ImageOwnerContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements ImageOwnerContract
{
    use Notifiable;

    const DIR = 'files';

    const PARTICIPANTS_DIR = 'participants';

    protected $fillable = [
        'username',
        'email',
        'password',
        'api_token',
        'classroom_id'
    ];

    protected $hidden = [
        'password',
        'api_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function imagesDir(): string
    {
        abort_if(!$this->classroom, 409, 'User does not have classroom');

        return self::DIR . DIRECTORY_SEPARATOR . $this->classroom->dir . DIRECTORY_SEPARATOR . self::PARTICIPANTS_DIR;
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }
}
