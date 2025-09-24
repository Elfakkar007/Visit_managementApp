<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $table = 'statuses';
    
    protected $guarded = ['id'];

    public function visitRequests(): HasMany
    {
        return $this->hasMany(VisitRequest::class);
    }

    /**
     * Scope baru untuk mengambil ID status berdasarkan namanya.
     * Ini membuat kode lebih bersih dan dinamis.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $name
     * @return int
     */
    public function scopeGetIdByName($query, $name)
    {
        // Cari status berdasarkan nama, jika tidak ada akan error (fail), lalu ambil ID-nya.
        return $query->where('name', $name)->firstOrFail()->id;
    }
}