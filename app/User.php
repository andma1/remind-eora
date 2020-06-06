<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

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
}
