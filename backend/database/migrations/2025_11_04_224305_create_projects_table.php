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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['planned', 'in_progress', 'completed', 'archived'])->default('planned');
            $table->date('deadline')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Index for status-based project queries
            $table->index('status');
            // Index for getting projects by owner and status
            $table->index(['owner_id', 'status']);
            // Index for deadline queries (finding projects due soon or overdue)
            $table->index('deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
