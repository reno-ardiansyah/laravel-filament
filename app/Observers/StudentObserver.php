<?php

namespace App\Observers;

use App\Models\Student;
use App\Traits\LogsModelChanges;

class StudentObserver
{
    use LogsModelChanges;
    /**
     * Handle the Student "created" event.
     */
    public function created(Student $student): void
    {
        $this->logAction($student, 'created');
    }

    /**
     * Handle the Student "updated" event.
     */
    public function updated(Student $student): void
    {
        $this->logAction($student, 'updated');
    }

    /**
     * Handle the Student "deleted" event.
     */
    public function deleted(Student $student): void
    {
        $this->logAction($student, 'deleted');
    }

    /**
     * Handle the Student "restored" event.
     */
    public function restored(Student $student): void
    {
        $this->logAction($student, 'restored');
    }

    /**
     * Handle the Student "force deleted" event.
     */
    public function forceDeleted(Student $student): void
    {
        $this->logAction($student, 'forceDeleted');
    }
}
