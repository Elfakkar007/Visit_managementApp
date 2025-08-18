<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Satu departemen bisa dimiliki oleh banyak profil pengguna
    public function userProfiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }
}