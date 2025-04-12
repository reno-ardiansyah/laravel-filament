<?php

namespace App\Observers;

use App\Models\Config;
use App\Traits\LogsModelChanges;

class ConfigObserver
{
    use LogsModelChanges;
    /**
     * Handle the Config "created" event.
     */
    public function created(Config $config): void
    {
        $this->logAction($config, 'created');
    }

    /**
     * Handle the Config "updated" event.
     */
    public function updated(Config $config): void
    {
        $this->logAction($config, 'updated');
    }

    /**
     * Handle the Config "deleted" event.
     */
    public function deleted(Config $config): void
    {
        $this->logAction($config, 'deleted');
    }

    /**
     * Handle the Config "restored" event.
     */
    public function restored(Config $config): void
    {
        $this->logAction($config, 'restored');
    }

    /**
     * Handle the Config "force deleted" event.
     */
    public function forceDeleted(Config $config): void
    {
        $this->logAction($config, 'force deleted');
    }
}
