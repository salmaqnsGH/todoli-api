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
        Schema::table('users', function (Blueprint $table) {
            // Drop existing name column
            $table->dropColumn('name');

            // Add new columns
            $table->foreignId('organization_id')->after('id')->constrained('organizations')->onDelete('cascade');
            $table->string('username')->after('organization_id');
            $table->string('first_name')->after('username');
            $table->string('last_name')->after('first_name');
            $table->string('image')->nullable()->after('last_name');

            // Add soft deletes
            $table->softDeletes();

            $table->unique('username'); // Usernames should be unique
            $table->index(['first_name', 'last_name']); // For name searches
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove the soft deletes column
            $table->dropSoftDeletes();

            // Remove the newly added columns
            $table->dropForeign(['organization_id']);
            $table->dropColumn([
                'organization_id',
                'username',
                'first_name',
                'last_name',
                'image',
            ]);

            // Restore the original name column
            $table->string('name')->after('id');
        });
    }
};
