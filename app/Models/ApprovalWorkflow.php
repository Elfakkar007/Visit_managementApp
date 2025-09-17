<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApprovalWorkflow extends Model
{
    use HasFactory;

    // Izinkan pengisian massal untuk kolom ini
    protected $fillable = [
        'requester_level_id',
        'approver_level_id',
        'scope',
    ];

    /**
     * Mendefinisikan relasi ke Level pengaju (requester).
     */
    public function requesterLevel()
    {
        return $this->belongsTo(Level::class, 'requester_level_id');
    }

    /**
     * Mendefinisikan relasi ke Level penyetuju (approver).
     */
    public function approverLevel()
    {
        return $this->belongsTo(Level::class, 'approver_level_id');
    }

    public function requesterSubsidiary()
    {
        // Method ini memberitahu Laravel bahwa 'requester_subsidiary_id' terhubung ke model Subsidiary
        return $this->belongsTo(Subsidiary::class, 'requester_subsidiary_id');
    }
}