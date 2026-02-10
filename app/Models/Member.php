<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Karena ada password & rememberToken
use Illuminate\Notifications\Notifiable;

class Member extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'membership_id',
        'start_date',
        'end_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function membership()
    {
        return $this->belongsTo(Membership::class);
    }
    public function user()
    {
        return $this->hasOne(User::class);
    }
}
