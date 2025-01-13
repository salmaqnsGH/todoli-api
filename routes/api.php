<?php

use App\Constants\Permission;
use App\Http\Controllers\Api\Activity\ActivityController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Organization\OrganizationController;
use App\Http\Controllers\Api\Project\ProjectController;
use App\Http\Controllers\Api\Project\ProjectMemberController;
use App\Http\Controllers\Api\Project\ProjectPermissionController;
use App\Http\Controllers\Api\Task\TaskCommentController;
use App\Http\Controllers\Api\Task\TaskController;
use App\Http\Controllers\Api\Task\TaskPriorityController;
use App\Http\Controllers\Api\Task\TaskStatusController;
use App\Http\Controllers\Api\User\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::post('/signin', [AuthController::class, 'login']);
    Route::post('/signup', [AuthController::class, 'register']);
    Route::post('/forgot', [AuthController::class, 'forgotAccount']);
    Route::post('/password', [UserController::class, 'updatePassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/signout', [AuthController::class, 'logout']);

        Route::group(['prefix' => 'users'], function () {
            Route::get('/profile', [UserController::class, 'getProfile']);
            Route::post('/profile', [UserController::class, 'updateProfile']);
            Route::post('/password', [UserController::class, 'updatePassword']);
        });

        Route::group(['prefix' => 'organizations'], function () {
            Route::get('/', [OrganizationController::class, 'getPaginatedList'])->middleware('permission:'.pn(Permission::ORGANIZATION_VIEW_OWN));
            Route::get('/{id}', [OrganizationController::class, 'getDetail'])->middleware('permission:'.pn(Permission::ORGANIZATION_VIEW_OWN));
            Route::post('/', [OrganizationController::class, 'create'])->middleware('permission:'.pn(Permission::ORGANIZATION_ADD));
            Route::patch('/{id}', [OrganizationController::class, 'update'])->middleware('permission:'.pn(Permission::ORGANIZATION_EDIT_OWN));
            Route::delete('/{id}', [OrganizationController::class, 'softDelete'])->middleware('permission:'.pn(Permission::ORGANIZATION_DELETE_OWN));
        });

        Route::group(['prefix' => 'projects'], function () {
            Route::get('/', [ProjectController::class, 'getPaginatedList'])->middleware('permission:'.pn(Permission::PROJECT_VIEW_OWN));
            Route::get('/{id}', [ProjectController::class, 'getDetail'])->middleware('permission:'.pn(Permission::PROJECT_VIEW_OWN));
            Route::post('/', [ProjectController::class, 'create'])->middleware('permission:'.pn(Permission::PROJECT_ADD));
            Route::patch('/{id}', [ProjectController::class, 'update'])->middleware('permission:'.pn(Permission::PROJECT_EDIT_OWN));
            Route::delete('/{id}', [ProjectController::class, 'softDelete'])->middleware('permission:'.pn(Permission::PROJECT_DELETE_OWN));

            Route::group(['prefix' => '{id}/members'], function () {
                Route::get('/users', [ProjectMemberController::class, 'getMembers'])->middleware('permission:'.pn(Permission::PROJECT_MEMBER_VIEW));
                Route::post('/users/', [ProjectMemberController::class, 'add'])->middleware('permission:'.pn(Permission::PROJECT_MEMBER_ADD));
                Route::delete('/users/{userId}', [ProjectMemberController::class, 'softRemove'])->middleware('permission:'.pn(Permission::PROJECT_MEMBER_REMOVE_OWN));
            });

            Route::group(['prefix' => '{id}/permissions'], function () {
                Route::get('/users/{userId}', [ProjectPermissionController::class, 'getUserProjectPermissions'])->middleware('permission:'.pn(Permission::PROJECT_USER_PERMISSION_VIEW_OWN));
                Route::patch('/users/{userId}', [ProjectPermissionController::class, 'updateUserProjectPermissions'])->middleware('permission:'.pn(Permission::PROJECT_USER_PERMISSION_EDIT));
            });

            Route::group(['prefix' => '{id}/tasks'], function () {
                Route::get('/', [TaskController::class, 'getPaginatedList'])->middleware('permission:'.pn(Permission::TASK_VIEW_OWN));
                Route::get('/{taskId}', [TaskController::class, 'getDetail'])->middleware('permission:'.pn(Permission::TASK_VIEW_OWN));
                Route::post('/', [TaskController::class, 'create'])->middleware('permission:'.pn(Permission::TASK_ADD));
                Route::post('/{taskId}', [TaskController::class, 'update'])->middleware('permission:'.pn(Permission::TASK_EDIT_OWN));
                Route::delete('/{taskId}', [TaskController::class, 'softDelete'])->middleware('permission:'.pn(Permission::TASK_DELETE_OWN));
                Route::post('/{taskId}/assign-user', [TaskController::class, 'assignUser'])->middleware('permission:'.pn(Permission::TASK_USER_ASSIGN));
                Route::post('/{taskId}/set-status', [TaskController::class, 'setStatus'])->middleware('permission:'.pn(Permission::TASK_STATUS_SET_OWN));
                Route::post('/{taskId}/set-priority', [TaskController::class, 'setPriority'])->middleware('permission:'.pn(Permission::TASK_PRIORITY_SET_OWN));

                Route::group(['prefix' => '{taskId}/comments'], function () {
                    Route::get('/', [TaskCommentController::class, 'getPaginatedList'])->middleware('permission:'.pn(Permission::TASK_COMMENT_VIEW_OWN));
                    Route::get('/{commentId}', [TaskCommentController::class, 'getDetail'])->middleware('permission:'.pn(Permission::TASK_COMMENT_VIEW_OWN));
                    Route::post('/', [TaskCommentController::class, 'create'])->middleware('permission:'.pn(Permission::TASK_COMMENT_ADD));
                    Route::patch('/{commentId}', [TaskCommentController::class, 'update'])->middleware('permission:'.pn(Permission::TASK_COMMENT_EDIT_OWN));
                    Route::delete('/{commentId}', [TaskCommentController::class, 'softDelete'])->middleware('permission:'.pn(Permission::TASK_COMMENT_DELETE_OWN));
                });
            });
        });

        Route::group(['prefix' => 'task-priorities'], function () {
            Route::get('/', [TaskPriorityController::class, 'getPaginatedList'])->middleware('permission:'.pn(Permission::TASK_PRIORITY_VIEW));
            Route::get('/{id}', [TaskPriorityController::class, 'getDetail'])->middleware('permission:'.pn(Permission::TASK_PRIORITY_VIEW));
            Route::post('/', [TaskPriorityController::class, 'create'])->middleware('permission:'.pn(Permission::TASK_PRIORITY_ADD));
            Route::patch('/{id}', [TaskPriorityController::class, 'update'])->middleware('permission:'.pn(Permission::TASK_PRIORITY_EDIT_OWN));
            Route::delete('/{id}', [TaskPriorityController::class, 'softDelete'])->middleware('permission:'.pn(Permission::TASK_PRIORITY_DELETE_OWN));
        });

        Route::group(['prefix' => 'task-statuses'], function () {
            Route::get('/', [TaskStatusController::class, 'getPaginatedList'])->middleware('permission:'.pn(Permission::TASK_STATUS_VIEW));
            Route::get('/{id}', [TaskStatusController::class, 'getDetail'])->middleware('permission:'.pn(Permission::TASK_STATUS_VIEW));
            Route::post('/', [TaskStatusController::class, 'create'])->middleware('permission:'.pn(Permission::TASK_STATUS_ADD));
            Route::patch('/{id}', [TaskStatusController::class, 'update'])->middleware('permission:'.pn(Permission::TASK_STATUS_EDIT_OWN));
            Route::delete('/{id}', [TaskStatusController::class, 'softDelete'])->middleware('permission:'.pn(Permission::TASK_STATUS_DELETE_OWN));
        });

        Route::group(['prefix' => 'activities'], function () {
            Route::get('/user', [ActivityController::class, 'getUserActivityPaginatedList'])->middleware('permission:'.pn(Permission::ACTIVITY_VIEW_OWN));
        });
    });
});
