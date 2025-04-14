<?php

namespace App\Observers;

use App\Models\Period;
use App\Traits\LogsModelChanges;

class PeriodObserver
{
    use LogsModelChanges;
    /**
     * Handle the Period "created" event.
     */
    public function created(Period $period): void
    {
        $this->logAction($period, 'created');
    }

    /**
     * Handle the Period "updated" event.
     */
    public function updated(Period $period): void
    {
        $this->logAction($period, 'updated');
    }

    /**
     * Handle the Period "deleted" event.
     */
    public function deleted(Period $period): void
    {
        $this->logAction($period, 'deleted');
    }

    /**
     * Handle the Period "restored" event.
     */
    public function restored(Period $period): void
    {
        //
    }

    /**
     * Handle the Period "force deleted" event.
     */
    public function forceDeleted(Period $period): void
    {
        //
    }
}
