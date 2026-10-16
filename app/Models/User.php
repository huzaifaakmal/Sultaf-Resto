<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users'; // or your custom table name if applicable
    protected $primaryKey = 'id_user'; // Your custom primary key from earlier context
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'name',
        'username',
        'email',
        'no_hp',
=======
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
>>>>>>> fda411f0813b9c7104ad865582a079f3a6221844
        'password',
        'role',
    ];

<<<<<<< HEAD
=======
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
>>>>>>> fda411f0813b9c7104ad865582a079f3a6221844
    protected $hidden = [
        'password',
        'remember_token',
    ];
<<<<<<< HEAD
}
=======

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
>>>>>>> fda411f0813b9c7104ad865582a079f3a6221844
