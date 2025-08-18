<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
        
        public function profile(): HasOne {
        return $this->hasOne(UserProfile::class);
    }

    public function visitRequests(): HasMany {
        return $this->hasMany(VisitRequest::class, 'user_id');
    }

    public function approvals(): HasMany {
        return $this->hasMany(VisitRequest::class, 'approved_by');
    }

    // Helper untuk akses data profile dengan mudah
    public function getLevelNameAttribute() {
        return $this->profile->level->name;
    }
    public function getDepartmentIdAttribute() {
        return $this->profile->department_id;
    }
    public function getSubsidiaryIdAttribute() {
        return $this->profile->subsidiary_id;
    }
}
