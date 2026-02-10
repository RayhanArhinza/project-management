<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'valid_days', 'price'
    ];

    protected $casts = [
        'valid_days' => 'integer',
        'price' => 'decimal:2',
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
