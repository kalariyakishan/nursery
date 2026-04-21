<?php

namespace App\Observers;

use App\Models\LabourEntryDetail;

class LabourEntryDetailObserver
{
    private function syncTotals(LabourEntryDetail $detail)
    {
        $entry = $detail->entry;
        if ($entry) {
            $entry->total_workers = $entry->details()->count();
            $entry->total_amount = $entry->details()->sum('wage_amount');
            $entry->save();
        }
    }

    /**
     * Handle the LabourEntryDetail "saved" event.
     * Use saved to catch both created and updated.
     */
    public function saved(LabourEntryDetail $labourEntryDetail): void
    {
        $this->syncTotals($labourEntryDetail);
    }

    /**
     * Handle the LabourEntryDetail "deleted" event.
     */
    public function deleted(LabourEntryDetail $labourEntryDetail): void
    {
        $this->syncTotals($labourEntryDetail);
    }

    /**
     * Handle the LabourEntryDetail "restored" event.
     */
    public function restored(LabourEntryDetail $labourEntryDetail): void
    {
        $this->syncTotals($labourEntryDetail);
    }

    /**
     * Handle the LabourEntryDetail "force deleted" event.
     */
    public function forceDeleted(LabourEntryDetail $labourEntryDetail): void
    {
        $this->syncTotals($labourEntryDetail);
    }
}
