<?php

namespace App\Observers;

use App\Models\User;
use App\Traits\LogsModelChanges;

class UserObserver
{
    use LogsModelChanges;
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->logAction($user, 'created');
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->logAction($user, 'updated');
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->logAction($user, 'deleted');
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $this->logAction($user, 'restored');
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        $this->logAction($user, 'force deleted');
    }
}
