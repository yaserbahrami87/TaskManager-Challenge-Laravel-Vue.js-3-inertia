<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable=[
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token'
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
