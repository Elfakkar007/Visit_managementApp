<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('approval_workflow_steps', function (Blueprint $table) {
            $table->string('scope')->default('department')->after('approver_id');
        });
    }

    public function down(): void
    {
        Schema::table('approval_workflow_steps', function (Blueprint $table) {
            $table->dropColumn('scope');
        });
    }
};