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
        // When a breakdown is created:
        // 1. The broken unit becomes 'Breakdown'
        $breakdownLog->unit()->update(['status_operasional' => 'Breakdown']);

        // 2. If a spare unit is assigned, it becomes 'In Use'
        if ($breakdownLog->spare_unit_id) {
            $breakdownLog->spareUnit()->update(['status_operasional' => 'In Use']);
        }
    }

    /**
     * Handle the BreakdownLog "updated" event.
     */
    public function updated(BreakdownLog $breakdownLog): void
    {
        // Logic for closing a breakdown log
        if ($breakdownLog->wasChanged('status') && $breakdownLog->status === 'Closed') {
            // Restore the broken unit to 'Ready'
            $breakdownLog->unit()->update(['status_operasional' => 'Ready']);

            // Restore the spare unit to 'Ready' if it was assigned
            if ($breakdownLog->spare_unit_id) {
                $breakdownLog->spareUnit()->update(['status_operasional' => 'Ready']);
            }
        }
        
        // Handle changing spare units while status is STILL Open (extra guard)
        if ($breakdownLog->status === 'Open' && $breakdownLog->wasChanged('spare_unit_id')) {
            $oldSpareId = $breakdownLog->getOriginal('spare_unit_id');
            $newSpareId = $breakdownLog->spare_unit_id;
            
            // Release old spare unit
            if ($oldSpareId) {
                \App\Models\MasterUnit::where('id', $oldSpareId)->update(['status_operasional' => 'Ready']);
            }
            
            // Set new spare unit to In Use
            if ($newSpareId) {
                $breakdownLog->spareUnit()->update(['status_operasional' => 'In Use']);
            }
        }
    }

    /**
     * Handle the BreakdownLog "deleted" event.
     */
    public function deleted(BreakdownLog $breakdownLog): void
    {
        // If an open breakdown is deleted, restore statuses
        if ($breakdownLog->status === 'Open') {
            $breakdownLog->unit()->update(['status_operasional' => 'Ready']);
            
            if ($breakdownLog->spare_unit_id) {
                $breakdownLog->spareUnit()->update(['status_operasional' => 'Ready']);
            }
        }
    }
}
