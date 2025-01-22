<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Constants\UserRole;
use App\Mail\Auth\CustomResetPassword;
use App\Mail\Auth\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * @mixin Builder
 *
 * @property int $id
 * @property int $organization_id
 * @property string $username
 * @property string $email
 * @property string $first_name
 * @property string|null $last_name
 * @property string|null $image
 * @property string|null $image_url
 * @property Carbon $email_verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Organization $organization
 * @property-read Project[] $project_permissions
 * @property-read Project[] $owned_projects
 * @property-read Project[] $team_member_projects
 * @property-read TaskComment[] $task_comments
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasRoles, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'organization_id',
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'image',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'image',
        'email_verified_at',
        'deleted_at',
        'laravel_through_key',
    ];

    /**
     * The attributes that are appended to the result.
     *
     * @var list<string>
     */
    protected $appends = ['image_url'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmail);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPassword($token));
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function project_permissions(): HasMany
    {
        return $this->hasMany(ProjectPermission::class);
    }

    public function owned_projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_members')
            ->withPivot('role_id')
            ->wherePivot('role_id', UserRole::USER_OWNER);
    }

    public function team_member_projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_members')
            ->withPivot('role_id')
            ->wherePivot('role_id', UserRole::USER_MEMBER);
    }

    public function task_comments(): HasMany
    {
        return $this->hasMany(TaskComment::class, 'user_id');
    }

    public function getImageUrlAttribute(): string
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter */
        $disk = Storage::disk('public');

        return $this->image
            ? $disk->url($this->image)
            : $this->getDefaultImageUrl();
    }

    private function getDefaultImageUrl(): string
    {
        return asset('images/default-profile.png');
    }
}
