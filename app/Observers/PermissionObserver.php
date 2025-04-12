<?php

namespace App\Observers;

use App\Traits\LogsModelChanges;
use Spatie\Permission\Models\Permission;


class PermissionObserver
{
    use LogsModelChanges;

    /**
     * Handle the Permission "created" event.
     */
    public function created(Permission $permission): void
    {
        $this->logAction($permission, 'created');
    }

    /**
     * Handle the Permission "updated" event.
     */
    public function updated(Permission $permission): void
    {
        $this->logAction($permission, 'updated');
    }

    /**
     * Handle the Permission "deleted" event.
     */
    public function deleted(Permission $permission): void
    {
        $this->logAction($permission, 'deleted');
    }

    /**
     * Handle the Permission "restored" event.
     */
    public function restored(Permission $permission): void
    {
        //
    }

    /**
     * Handle the Permission "force deleted" event.
     */
    public function forceDeleted(Permission $permission): void
    {
        //
    }
}
