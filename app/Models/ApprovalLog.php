<?php

namespace App\Models;

use App\Models\Status;
use App\Models\User;
use App\Models\VisitRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalLog extends Model
{
    protected $guarded = ['id'];

    public function visitRequest(): BelongsTo
    {
        return $this->belongsTo(VisitRequest::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
}