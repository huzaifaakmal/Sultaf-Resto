<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function member(): HasOne
    {
        return $this->hasOne(Member::class, 'id_user', 'id_user');
    }

    public function isMember(): bool
    {
        return $this->member !== null;
    }

    protected function points(): Attribute
    {
        return Attribute::get(fn () => (int) ($this->member?->total_poin ?? 0));
    }
}
