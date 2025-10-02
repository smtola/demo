<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log(string $action, string $entityType = null, $entityId = null, array $data = [])
    {
        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => $action,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'data'        => $data,
            'performed_at'=> now(),
        ]);
    }
}
