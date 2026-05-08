<?php

namespace App\Helpers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Log;

class AuditLogger
{
    public static function log($category, $action, $details = null)
    {
        try {
            return AuditLog::create([
                'user_id' => auth()->id(),
                'category' => $category,
                'action' => $action,
                'details' => $details
            ]);
        } catch (\Exception $e) {
            Log::error('Audit Log Error: ' . $e->getMessage());
        }
    }
}