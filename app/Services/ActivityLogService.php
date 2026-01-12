<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log an activity.
     */
    public function log(
        string $action,
        string $description,
        ?Model $model = null,
        ?array $metadata = null
    ): ActivityLog {
        $user = Auth::user();
        $candidate = Auth::guard('candidate')->user();

        return ActivityLog::create([
            'user_id' => $user?->id,
            'candidate_id' => $candidate?->id,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'route' => Request::route()?->getName() ?? Request::path(),
        ]);
    }

    /**
     * Log a login event.
     */
    public function logLogin($user, bool $success = true): void
    {
        $isCandidate = $user instanceof \App\Models\Candidate;
        
        ActivityLog::create([
            'user_id' => !$isCandidate ? $user?->id : null,
            'candidate_id' => $isCandidate ? $user?->id : null,
            'action' => $success ? 'login' : 'login_failed',
            'description' => $success 
                ? ($isCandidate ? "Candidate {$user->name} ({$user->email}) logged in successfully"
                   : "User {$user->name} ({$user->email}) logged in successfully")
                : "Failed login attempt for email: " . request()->email,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'route' => Request::route()?->getName() ?? Request::path(),
        ]);
    }

    /**
     * Log a logout event.
     */
    public function logLogout($user): void
    {
        $isCandidate = $user instanceof \App\Models\Candidate;
        
        ActivityLog::create([
            'user_id' => !$isCandidate ? $user?->id : null,
            'candidate_id' => $isCandidate ? $user?->id : null,
            'action' => 'logout',
            'description' => $isCandidate 
                ? "Candidate {$user->name} ({$user->email}) logged out"
                : "User {$user->name} ({$user->email}) logged out",
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'route' => Request::route()?->getName() ?? Request::path(),
        ]);
    }

    /**
     * Log a candidate activity.
     */
    public function logCandidateActivity(
        string $action,
        string $description,
        ?Model $model = null,
        ?array $metadata = null
    ): ActivityLog {
        $candidate = Auth::guard('candidate')->user();

        return ActivityLog::create([
            'candidate_id' => $candidate?->id,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'route' => Request::route()?->getName() ?? Request::path(),
        ]);
    }

    /**
     * Log a CRUD operation.
     */
    public function logCrud(string $action, Model $model, ?array $metadata = null): ActivityLog
    {
        $modelName = class_basename($model);
        $descriptions = [
            'create' => "Created {$modelName}: " . ($model->title ?? $model->name ?? "ID {$model->id}"),
            'update' => "Updated {$modelName}: " . ($model->title ?? $model->name ?? "ID {$model->id}"),
            'delete' => "Deleted {$modelName}: " . ($model->title ?? $model->name ?? "ID {$model->id}"),
            'view' => "Viewed {$modelName}: " . ($model->title ?? $model->name ?? "ID {$model->id}"),
        ];

        return $this->log(
            $action,
            $descriptions[$action] ?? "{$action} {$modelName}",
            $model,
            $metadata
        );
    }

    /**
     * Log a generic admin activity.
     */
    public function logActivity(string $action, string $description, ?array $metadata = null): ActivityLog
    {
        return $this->log($action, $description, null, $metadata);
    }
}


