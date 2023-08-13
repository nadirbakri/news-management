<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    use HasApiTokens, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'isAdmin',
    ];

    protected $hidden = [
        'password',
    ];

    public function newses(): HasMany
    {
        return $this->hasMany(News::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
