<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

        protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'extension_name',
        'email',
        'password',
        'password_plain',
        'role',
        'profile_image',
        'birthdate',
        'sex',
        'degree_program',
        'permanent_address',
        'current_address',
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'password_plain' => 'encrypted',
    ];
}