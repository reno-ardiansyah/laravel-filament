<?php

namespace App\Traits;

use App\Models\Logs;

trait LogsModelChanges
{
    public function logAction($model, string $action)
    {
        Logs::create([
            'loggable_id' => $model->id,
            'loggable_type' => get_class($model),
            'action' => $action,
            'changes' => json_encode($model->getChanges()), // bisa pakai $model->getOriginal() juga kalau mau before-after
            'message' => class_basename($model) . " {$action} with ID: {$model->id}",
            'user_id' => auth()->id(),
        ]);
    }
}
