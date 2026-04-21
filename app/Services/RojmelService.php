<?php

namespace App\Services;

use App\Models\RojmelEntry;
use App\Models\DailyBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RojmelService
{
    /**
     * Recalculate balances starting from a specific date.
     * 
     * @param string $startDate format YYYY-MM-DD
     */
    public function recalculateFrom(string $startDate)
    {
        $date = Carbon::parse($startDate);
        
        // Get the latest balance record BEFORE this date to get our initial opening balance
        $previousBalance = DailyBalance::where('date', '<', $startDate)
            ->orderBy('date', 'desc')
            ->first();

        $openingBalance = $previousBalance ? $previousBalance->closing_balance : 0;

        // Process all days from $startDate until today (or until there are no more entries/balances)
        // To be safe and efficient, we find all dates that have entries or existing balance records
        $datesToProcess = RojmelEntry::where('date', '>=', $startDate)
            ->pluck('date')
            ->merge(DailyBalance::where('date', '>=', $startDate)->pluck('date'))
            ->unique()
            ->sort()
            ->values();

        foreach ($datesToProcess as $processDate) {
            $processDateStr = Carbon::parse($processDate)->format('Y-m-d');
            
            $entries = RojmelEntry::where('date', $processDateStr)->get();
            $totalAvak = $entries->where('type', 'avak')->sum('amount');
            $totalJavak = $entries->where('type', 'javak')->sum('amount');
            
            $closingBalance = $openingBalance + $totalAvak - $totalJavak;

            DailyBalance::updateOrCreate(
                ['date' => $processDateStr],
                [
                    'opening_balance' => $openingBalance,
                    'total_avak' => $totalAvak,
                    'total_javak' => $totalJavak,
                    'closing_balance' => $closingBalance
                ]
            );

            // The closing balance of today is the opening balance of the next date in our list
            $openingBalance = $closingBalance;
        }

        // IMPORTANT: We must also handle the gap between dates if any
        // But since we are updating the chain, if there's a day with NO entries in the future, 
        // we might need to update its opening balance too if a record exists for it.
        // The loop above handles all dates that HAVE entries or existing balance records.
    }

    /**
     * Get current stats for a specific date.
     */
    public function getDailyStats(string $date)
    {
        // First ensure the balance record exists for this date
        $balance = DailyBalance::where('date', $date)->first();
        
        if (!$balance) {
            // If doesn't exist, it might be because no entries were made. 
            // We should still try to calculate it based on the previous day.
            $this->recalculateFrom($date);
            $balance = DailyBalance::where('date', $date)->first();
        }

        return $balance;
    }
}
