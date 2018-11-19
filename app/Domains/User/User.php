<?php

namespace App\Domains\User;

use App\Domains\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 * @package App\Domains\User
 * @property string name
 * @property string email
 * @property string password
 */
class User extends Authenticatable
{
    use Notifiable, Uuid, SoftDeletes;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
