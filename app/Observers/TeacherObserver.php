<?php

namespace App\Observers;

use App\Models\Teacher;
use App\Traits\LogsModelChanges;

class TeacherObserver
{
    use LogsModelChanges;
    /**
     * Handle the Teacher "created" event.
     */
    public function created(Teacher $teacher): void
    {
        $this->logAction($teacher, 'created');
    }

    /**
     * Handle the Teacher "updated" event.
     */
    public function updated(Teacher $teacher): void
    {
        $this->logAction($teacher, 'updated');
    }

    /**
     * Handle the Teacher "deleted" event.
     */
    public function deleted(Teacher $teacher): void
    {
        $this->logAction($teacher, 'deleted');
    }

    /**
     * Handle the Teacher "restored" event.
     */
    public function restored(Teacher $teacher): void
    {
        $this->logAction($teacher, 'restored');
    }

    /**
     * Handle the Teacher "force deleted" event.
     */
    public function forceDeleted(Teacher $teacher): void
    {
        $this->logAction($teacher, 'forceDeleted');
    }
}
