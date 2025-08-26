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
            $table->foreignId('guest_id')->constrained('guests')->onDelete('cascade');
            $table->uuid('uuid')->unique();
            
            // Kolom yang diisi oleh tamu
            $table->string('ktp_photo_path');

            // Kolom yang diisi resepsionis (INI KUNCINYA)
            $table->string('visit_destination')->nullable(); // Dibuat nullable dari awal
            
            // Kolom status dan waktu
            $table->enum('status', ['waiting_check_in', 'checked_in', 'checked_out']);
            $table->timestamp('time_in')->nullable();
            $table->foreignId('checked_in_by')->nullable()->constrained('users');
            $table->timestamp('time_out')->nullable();
            $table->foreignId('checked_out_by')->nullable()->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_visits');
    }
};