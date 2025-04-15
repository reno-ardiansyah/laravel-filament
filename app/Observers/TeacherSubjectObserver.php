<?php

namespace App\Observers;

use App\Models\TeacherSubject;
use App\Traits\LogsModelChanges;

class TeacherSubjectObserver
{
    use LogsModelChanges;
    /**
     * Handle the TeacherSubject "created" event.
     */
    public function created(TeacherSubject $teacherSubject): void
    {
        $this->logAction($teacherSubject, 'created');
    }

    /**
     * Handle the TeacherSubject "updated" event.
     */
    public function updated(TeacherSubject $teacherSubject): void
    {
        $this->logAction($teacherSubject, 'update');
    }

    /**
     * Handle the TeacherSubject "deleted" event.
     */
    public function deleted(TeacherSubject $teacherSubject): void
    {
        $this->logAction($teacherSubject, 'delete');
    }

    /**
     * Handle the TeacherSubject "restored" event.
     */
    public function restored(TeacherSubject $teacherSubject): void
    {
        //
    }

    /**
     * Handle the TeacherSubject "force deleted" event.
     */
    public function forceDeleted(TeacherSubject $teacherSubject): void
    {
        //
    }
}
