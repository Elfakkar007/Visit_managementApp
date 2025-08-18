<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subsidiary extends Model
{
    use HasFactory;

    // Izinkan pengisian massal untuk semua kolom
    protected $guarded = ['id'];

    // Definisikan relasi ke UserProfile
    public function userProfiles(): HasMany
    {
        return $this->hasMany(UserProfile::class);
    }
}