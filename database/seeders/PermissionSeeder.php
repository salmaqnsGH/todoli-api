<?php

namespace Database\Seeders;

use App\Constants\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use Spatie\Permission\Models\Permission as ModelsPermission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, truncate the permissions table to ensure clean state
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get all constants using reflection
        $reflection = new ReflectionClass(Permission::class);
        $constants = $reflection->getConstants();

        foreach ($constants as $key => $value) {
            ModelsPermission::create([
                'id' => $value,
                'name' => Permission::getName($value),
                'guard_name' => 'sanctum',
            ]);
        }
    }
}
