<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable;

    const VERIFIED_USER = true;
    const UNVERIFIED_USER = false;

    const ADMIN_USER = true;
    const REGULAR_USER = false;

    protected $table = 'users'; //declarada para que la herenden seller y buyer
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isVerified()
    {
        return $this->verified;
    }

    public function isAdmmin()
    {
        return $this->admin;
    }

    public static function generateVerificationCode()
    {
        return Str::random(40);
    }
}
