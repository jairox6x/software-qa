<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'name', 'email', 'password',
    ];
    
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
