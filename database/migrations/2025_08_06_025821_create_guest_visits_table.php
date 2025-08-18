<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained()->onDelete('cascade');
            $table->uuid('uuid')->unique();
            
            // --- PERUBAHAN DI SINI ---
            // 1. Kolom 'purpose' dihapus.
            // 2. 'destination_person' diubah menjadi lebih umum.
            $table->string('visit_destination'); // Bisa diisi nama orang atau nama departemen
            
            $table->timestamp('time_in')->nullable();
            $table->timestamp('time_out')->nullable();
            $table->enum('status', ['waiting_check_in', 'checked_in', 'checked_out'])->default('waiting_check_in');
            $table->foreignId('checked_in_by')->nullable()->constrained('users');
            $table->foreignId('checked_out_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_visits');
    }
};