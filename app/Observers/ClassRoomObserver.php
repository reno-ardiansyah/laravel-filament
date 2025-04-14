<?php

namespace App\Observers;

use App\Models\ClassRoom;
use App\Traits\LogsModelChanges;

class ClassRoomObserver
{
    use LogsModelChanges;
    /**
     * Handle the ClassRoom "created" event.
     */
    public function created(ClassRoom $classRoom): void
    {
        $this->logAction($classRoom, 'created');
    }

    /**
     * Handle the ClassRoom "updated" event.
     */
    public function updated(ClassRoom $classRoom): void
    {
        $this->logAction($classRoom, 'updated');
    }

    /**
     * Handle the ClassRoom "deleted" event.
     */
    public function deleted(ClassRoom $classRoom): void
    {
        $this->logAction($classRoom, 'deleted');
    }

    /**
     * Handle the ClassRoom "restored" event.
     */
    public function restored(ClassRoom $classRoom): void
    {
        //
    }

    /**
     * Handle the ClassRoom "force deleted" event.
     */
    public function forceDeleted(ClassRoom $classRoom): void
    {
        //
    }
}
