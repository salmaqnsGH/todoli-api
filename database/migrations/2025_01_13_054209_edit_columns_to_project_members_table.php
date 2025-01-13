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
        Schema::table('project_members', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade')->after('role_type');
        });

        Schema::table('project_members', function (Blueprint $table) {
            $table->dropColumn('role_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_members', function (Blueprint $table) {
            // Recreate the old column
            $table->tinyInteger('role_type')->after('user_id')->nullable();

            // Drop the foreign key and new column
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
