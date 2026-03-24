<?php

namespace App\Observers;

use App\Models\BreakdownLog;

class BreakdownLogObserver
{
    /**
     * Handle the BreakdownLog "created" event.
     */
    public function created(BreakdownLog $breakdownLog): void
    {
        // Status tracking has been removed
    }

    /**
     * Handle the BreakdownLog "updated" event.
     */
    public function updated(BreakdownLog $breakdownLog): void
    {
        // Status tracking has been removed
    }

    /**
     * Handle the BreakdownLog "deleted" event.
     */
    public function deleted(BreakdownLog $breakdownLog): void
    {
        // Status tracking has been removed
    }
}
