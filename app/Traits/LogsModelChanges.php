<?php

namespace App\Traits;

use App\Models\Logs;
use Illuminate\Support\Str;

trait LogsModelChanges
{
    public function logAction($model, string $action)
    {
        $original = $model->getOriginal();
        $changes = $model->getChanges();

        $beforeAfter = [
            'before' => array_intersect_key($original, $changes),
            'after' => $changes,
        ];

        
        Logs::create([
            'loggable_id' => method_exists($model, 'getKey') && filled($model->getKey())
            ? $model->getKey()
            : $this->generateLoggableKey($model),
            'loggable_type' => get_class($model),
            'action' => $action,
            'changes' => json_encode($beforeAfter),
            'message' => class_basename($model) . " {$action} with ID: {$model->id}",
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(), 
        ]);
        
    }
}
