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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('priority_id')->constrained('task_priorities')->onDelete('restrict');
            $table->foreignId('status_id')->constrained('task_statuses')->onDelete('restrict');
            $table->string('name');
            $table->string('objective')->nullable();
            $table->text('description')->nullable();
            $table->string('additional_notes')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('name'); // For searching tasks by name
            $table->index('due_date'); // For date-based queries and sorting
            $table->index(['project_id', 'status_id']); // For filtering project tasks by status
            $table->index(['project_id', 'due_date']); // For project timeline views
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
