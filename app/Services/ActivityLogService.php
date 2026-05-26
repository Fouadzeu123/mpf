<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Contracts\Auth\Authenticatable;

class ActivityLogService
{
    public function log(string $action, ?Authenticatable $user = null, ?array $meta = null): void
    {
        ActivityLog::create([
            'user_id' => $user?->getAuthIdentifier() ?? auth()->id(),
            'action' => $action,
            'meta' => $meta,
        ]);
    }
}
