<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestVisit extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // --- TAMBAHKAN BAGIAN INI ---
    protected $casts = [
        'time_in' => 'datetime',
        'time_out' => 'datetime',
    ];
    // -------------------------

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_in_by');
    }
    
    public function checkedOutBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_out_by');
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}