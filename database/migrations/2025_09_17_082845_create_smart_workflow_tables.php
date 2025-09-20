<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_workflows', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama aturan, misal: "Approval Staff Produksi"
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('approval_workflow_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_workflow_id')->constrained()->onDelete('cascade');
            $table->integer('step'); // Urutan approval (1, 2, 3, dst.)
            $table->enum('approval_type', ['serial', 'parallel']); // Tipe approval di dalam satu step

            // --- Approver bisa berupa Level, Role, atau User spesifik ---
            $table->string('approver_type'); // 'level', 'role', atau 'user'
            $table->unsignedBigInteger('approver_id');

            $table->timestamps();
        });

        Schema::create('approval_workflow_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approval_workflow_id')->constrained()->onDelete('cascade');

            // --- Kondisi bisa berdasarkan Level, Role, atau User spesifik ---
            $table->string('condition_type'); // 'level', 'role', atau 'user'
            $table->unsignedBigInteger('condition_id');
            $table->string('condition_value'); // ID dari level/role/user
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_workflow_conditions');
        Schema::dropIfExists('approval_workflow_steps');
        Schema::dropIfExists('approval_workflows');
    }
};