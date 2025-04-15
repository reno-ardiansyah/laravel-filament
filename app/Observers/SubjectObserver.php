<?php

namespace App\Observers;

use App\Models\Subject;
use App\Traits\LogsModelChanges;

class SubjectObserver
{
    use LogsModelChanges;
    /**
     * Handle the Subject "created" event.
     */
    public function created(Subject $subject): void
    {
        $this->logAction($subject, 'created');
    }

    /**
     * Handle the Subject "updated" event.
     */
    public function updated(Subject $subject): void
    {
        $this->logAction($subject, 'updated');
    }

    /**
     * Handle the Subject "deleted" event.
     */
    public function deleted(Subject $subject): void
    {
        $this->logAction($subject, 'deleted');
    }

    /**
     * Handle the Subject "restored" event.
     */
    public function restored(Subject $subject): void
    {
        //
    }

    /**
     * Handle the Subject "force deleted" event.
     */
    public function forceDeleted(Subject $subject): void
    {
        //
    }
}
