<?php

namespace App\Observers;

use App\Models\Schedule;
use App\Traits\LogsModelChanges;

class ScheduleObserver
{
    use LogsModelChanges;
    
    /**
     * Handle the Schedule "created" event.
     */
    public function created(Schedule $schedule): void
    {
        $this->logAction($schedule, 'created');
    }

    /**
     * Handle the Schedule "updated" event.
     */
    public function updated(Schedule $schedule): void
    {
        $this->logAction($schedule, 'updated');
    }

    /**
     * Handle the Schedule "deleted" event.
     */
    public function deleted(Schedule $schedule): void
    {
        $this->logAction($schedule, 'deleted');
    }

    /**
     * Handle the Schedule "restored" event.
     */
    public function restored(Schedule $schedule): void
    {
        //
    }

    /**
     * Handle the Schedule "force deleted" event.
     */
    public function forceDeleted(Schedule $schedule): void
    {
        //
    }
}
