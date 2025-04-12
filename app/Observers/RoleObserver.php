<?php

namespace App\Observers;

use App\Traits\LogsModelChanges;
use Spatie\Permission\Models\Role;

class RoleObserver
{
    use LogsModelChanges;
    
    /**
     * Handle the Role "created" event.
     */
    public function created(Role $role): void
    {
        $this->logAction($role, 'created');
    }

    /**
     * Handle the Role "updated" event.
     */
    public function updated(Role $role): void
    {
        $this->logAction($role, 'updated');
    }

    /**
     * Handle the Role "deleted" event.
     */
    public function deleted(Role $role): void
    {
        $this->logAction($role, 'deleted');
    }

    /**
     * Handle the Role "restored" event.
     */
    public function restored(Role $role): void
    {
        //
    }

    /**
     * Handle the Role "force deleted" event.
     */
    public function forceDeleted(Role $role): void
    {
        //
    }
}
