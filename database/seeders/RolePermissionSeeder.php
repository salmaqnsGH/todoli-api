<?php

namespace Database\Seeders;

use App\Constants\Permission;
use App\Constants\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('role_has_permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // SUPER_ADMIN default permissions
        $superAdminRole = Role::where('id', UserRole::SUPER_ADMIN)
            ->where('guard_name', 'sanctum')
            ->firstOrFail();

        foreach (Permission::getDefaultSuperAdminPermissions() as $permissionId) {
            $permissionName = Permission::getName($permissionId);
            $superAdminRole->givePermissionTo($permissionName);
        }

        // USER_MEMBER default permissions
        $userMemberRole = Role::where('id', UserRole::USER_MEMBER)
            ->where('guard_name', 'sanctum')
            ->firstOrFail();

        foreach (Permission::getDefaultUserMemberPermissions() as $permissionId) {
            $permissionName = Permission::getName($permissionId);
            $userMemberRole->givePermissionTo($permissionName);
        }
    }
}
