<?php

namespace App;

use App\Models\Logs;

trait LogActivity
{
    public function logAction($action)
    {
        Logs::create([
            'loggable_id' => $this->id,
            'loggable_type' => self::class,
            'action' => $action,
            'changes' => json_encode($this->getChanges()),
            'message' => "Teacher {$action} with ID: {$this->id}",
            'user_id' => auth()->id(), 
        ]);
    }
}
