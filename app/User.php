<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    protected $table='users';

    use HasApiTokens , Notifiable ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','verified','verification_token','role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const UNVERIFIED_USER='0';
    const VERIFIED_USER='1';

    const ADMIN_USER='true';
    const REGULAR_USER='false';

    public function isVerified(){
        return $this->verified==='1';
    }

    public function isAdmin(){
        return $this->role==='true';
    }

    static function generateVerificationToken(){
        return str::random(20);
    }

}
