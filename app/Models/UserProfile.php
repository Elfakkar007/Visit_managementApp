<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    protected $guarded = ['id'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function subsidiary(): BelongsTo { return $this->belongsTo(Subsidiary::class); }
    public function department(): BelongsTo { return $this->belongsTo(Department::class); }
    public function role(): BelongsTo { return $this->belongsTo(Role::class); }
    public function level(): BelongsTo { return $this->belongsTo(Level::class); }
}
