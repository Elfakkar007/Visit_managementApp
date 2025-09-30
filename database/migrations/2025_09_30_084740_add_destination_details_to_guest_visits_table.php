<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_visits', function (Blueprint $table) {
            // Tambah kolom baru setelah 'visit_destination'
            $table->foreignId('destination_department_id')->nullable()->after('visit_destination')->constrained('departments');
            $table->string('destination_person_name')->nullable()->after('destination_department_id');
        });
    }

    public function down(): void
    {
        Schema::table('guest_visits', function (Blueprint $table) {
            $table->dropForeign(['destination_department_id']);
            $table->dropColumn(['destination_department_id', 'destination_person_name']);
        });
    }
};