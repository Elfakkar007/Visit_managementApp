<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

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
   

        public function getApprovers()
    {

        $workflow = \App\Models\ApprovalWorkflow::where('requester_level_id', $this->profile->level_id)->first();

        if (!$workflow) {
            return collect(); // Kembalikan koleksi kosong jika tidak ada aturan
        }

        // Cari semua user yang memiliki level approver yang sesuai
        $approvers = User::whereHas('profile', function ($query) use ($workflow) {
            $query->where('level_id', $workflow->approver_level_id);

            // Terapkan filter scope (department atau subsidiary)
            if ($workflow->scope === 'department') {
                $query->where('department_id', $this->profile->department_id);
            } elseif ($workflow->scope === 'subsidiary') {
                // Logika khusus untuk Pusat
                if ($this->profile->subsidiary->name === 'Pusat') {
                    $agroAnekaIds = \App\Models\Subsidiary::whereIn('name', ['Agro', 'Aneka'])->pluck('id');
                    $query->whereIn('subsidiary_id', $agroAnekaIds);
                } else {
                    $query->where('subsidiary_id', $this->profile->subsidiary_id);
                }
            }
        })->get();

        return $approvers;
    }
}
