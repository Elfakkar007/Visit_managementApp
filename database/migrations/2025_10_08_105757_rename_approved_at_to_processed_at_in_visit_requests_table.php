<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('visit_requests', function (Blueprint $table) {
            $table->renameColumn('approved_at', 'processed_at');
        });
    }

    public function down()
    {
        Schema::table('visit_requests', function (Blueprint $table) {
            $table->renameColumn('processed_at', 'approved_at');
        });
    }
};