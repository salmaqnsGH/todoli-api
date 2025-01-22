<?php

namespace App\Http\Middleware;

use App\Services\PermissionService;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function __construct(
        private readonly PermissionService $permissionService,
    ) {}

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permissionNames): Response
    {
        $projectId = $request->route('projectId');

        // Split permissions by | if multiple permissions exist
        $permissions = explode('|', $permissionNames);

        // Check if user has ANY of the permissions
        foreach ($permissions as $permissionName) {
            if ($this->permissionService->hasPermission(
                user: $request->user(),
                permissionName: $permissionName,
                projectId: $projectId ? (int) $projectId : null,
            )) {
                return $next($request);
            }
        }

        throw new AuthorizationException;
    }
}
