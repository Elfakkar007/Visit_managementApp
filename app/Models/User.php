<?php

namespace App\Models;

// ... (bagian 'use' biarkan seperti aslinya)
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    // ... (properti $fillable, $hidden, casts() biarkan seperti aslinya)
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
    // ... (semua relasi seperti profile(), visitRequests(), approvals() biarkan seperti aslinya)
    public function profile(): HasOne {
        return $this->hasOne(UserProfile::class);
    }

    public function visitRequests(): HasMany {
        return $this->hasMany(VisitRequest::class, 'user_id');
    }

    public function approvals(): HasMany {
        return $this->hasMany(VisitRequest::class, 'approved_by');
    }

    

    public function getApprovers()
    {
        return app(\App\Services\WorkflowService::class)->findApproversFor($this);
    }
}