<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('visit_requests', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained('users'); 
            $table->string('destination');
            $table->text('purpose');
            $table->date('from_date');
            $table->date('to_date');

            
            $table->foreignId('status_id')->constrained('statuses');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable(); 
            $table->text('rejection_reason')->nullable(); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_requests');
    }
};
