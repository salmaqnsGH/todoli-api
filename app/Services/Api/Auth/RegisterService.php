<?php

namespace App\Services\Api\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RegisterService
{
    public function createUser(array $data): User
    {
        return User::create([
            'organization_id' => $data['organization_id'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'image' => $data['image'] ?? null,
        ]);
    }

    public function assignRole(User $user, int $roleId)
    {
        $userMemberRole = Role::where('id', $roleId)
            ->where('guard_name', 'sanctum')
            ->firstOrFail();

        $user->assignRole($userMemberRole);
    }
}
