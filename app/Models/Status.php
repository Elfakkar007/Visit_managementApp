<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    // Untuk memastikan Laravel tidak menganggap nama tabelnya 'statuses'
    protected $table = 'statuses';
    
    protected $guarded = ['id'];

    // Satu status bisa dimiliki oleh banyak request kunjungan
    public function visitRequests(): HasMany
    {
        return $this->hasMany(VisitRequest::class);
    }
}