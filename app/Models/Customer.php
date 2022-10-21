<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    use Notifiable;

    protected $guard = 'customer';

    protected $fillable = [
        'firstname', 'lastname', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
