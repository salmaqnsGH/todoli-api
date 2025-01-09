<?php

namespace Database\Seeders;

use App\Constants\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, truncate the roles table to ensure clean state
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Get all constants using reflection
        $reflection = new ReflectionClass(UserRole::class);
        $constants = $reflection->getConstants();

        foreach ($constants as $key => $value) {
            Role::create([
                'id' => $value,
                'name' => UserRole::getName($value),
                'guard_name' => 'sanctum',
            ]);
        }
    }
}
