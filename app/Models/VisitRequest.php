<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class VisitRequest extends Model
{
    protected $guarded = ['id'];
    protected $casts = [
        'approved_at' => 'datetime',
        'from_date' => 'date',
        'to_date' => 'date',
    ];
    public function user(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); } // Pembuat Request
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); } // Yang Approve
    public function status(): BelongsTo { return $this->belongsTo(Status::class); }
}
