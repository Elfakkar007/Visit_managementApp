<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('visit_requests', function (Blueprint $table) {
            // Mengubah nama kolom dari 'rejection_reason' menjadi 'approver_note'
            $table->renameColumn('rejection_reason', 'approver_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visit_requests', function (Blueprint $table) {
            // Mengembalikan nama kolom jika migrasi di-rollback
            $table->renameColumn('approver_note', 'rejection_reason');
        });
    }
};