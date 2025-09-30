<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('guest_visits', function (Blueprint $table) {
            // Tambah kolom untuk durasi notifikasi dalam jam, setelah nama orang yang dituju.
            // Diberi default 8 jam jika resepsionis tidak mengisi.
            $table->tinyInteger('notification_duration_hours')->unsigned()->default(8)->after('destination_person_name');
        });
    }

    public function down(): void
    {
        Schema::table('guest_visits', function (Blueprint $table) {
            $table->dropColumn('notification_duration_hours');
        });
    }
};