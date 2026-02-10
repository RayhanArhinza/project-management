<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Mengecek apakah role memiliki izin berdasarkan prefix route.
     * Contoh: 'tasks.index' -> prefix = 'tasks'
     */
    public function hasPermissionByPrefix($routeName)
{
    if (!$this->permissions) {
        return false;
    }

    $prefix = explode('.', $routeName)[0];

    return in_array($prefix, $this->permissions);
}

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
