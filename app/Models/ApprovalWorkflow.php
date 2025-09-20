<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApprovalWorkflow extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function steps(): HasMany
    {
        return $this->hasMany(ApprovalWorkflowStep::class)->orderBy('step', 'asc');
    }

    public function conditions(): HasMany
    {
        return $this->hasMany(ApprovalWorkflowCondition::class);
    }
}