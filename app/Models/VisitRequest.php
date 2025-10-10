<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity; 
use Spatie\Activitylog\LogOptions;


class VisitRequest extends Model
{
    use HasFactory, LogsActivity;
    protected $guarded = ['id'];
    protected $casts = [
        'processed_at' => 'datetime',
        'from_date' => 'datetime',
        'to_date' => 'datetime',
    ];
      public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status.name', 'destination', 'purpose'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function(string $eventName) {
                if ($eventName === 'updated') {
                    // Ambil status baru setelah perubahan
                    $newStatus = $this->getDirty()['status_id'] ?? null;
                    if ($newStatus) {
                        $statusName = Status::find($newStatus)->name;
                        return "Status permintaan #{$this->id} telah diubah menjadi {$statusName}";
                    }
                }
                return "Permintaan #{$this->id} dengan tujuan {$this->destination} telah di-{$eventName}";
            })
            ->dontSubmitEmptyLogs();
    }
    
    public function user(): BelongsTo { return $this->belongsTo(User::class, 'user_id'); } // Pembuat Request
    public function approver(): BelongsTo { return $this->belongsTo(User::class, 'approved_by'); } // Yang Approve
    public function status(): BelongsTo { return $this->belongsTo(Status::class); }
    public function approvalLogs(): HasMany
    {
        return $this->hasMany(\App\Models\ApprovalLog::class)->orderBy('created_at', 'asc');
    }
}
