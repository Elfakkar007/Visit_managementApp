<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    use HasFactory;

    // Izinkan pengisian massal untuk semua kolom
    protected $guarded = ['id'];

    // Satu tamu bisa memiliki banyak riwayat kunjungan
    public function visits(): HasMany
    {
        return $this->hasMany(GuestVisit::class);
    }
}