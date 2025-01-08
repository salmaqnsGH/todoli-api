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
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('model_type');  // example: App\Models\Project
            $table->unsignedBigInteger('model_id');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('action')->unsigned();  // see: app/Constants/ActivityAction.php
            $table->json('data_changes')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id']); // Index for polymorphic relationship
            $table->index('user_id'); // For user activity history
            $table->index(['model_type', 'model_id', 'created_at']); // For recent activity feeds
            $table->index(['created_at', 'id']); // For pagination with time ordering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
