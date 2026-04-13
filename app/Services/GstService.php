<?php

namespace App\Services;

class GstService
{
    /**
     * Calculate GST details
     * 
     * @param float $amount Amount to calculate GST on (usually subtotal - discount)
     * @param float $percentage Total GST percentage (e.g., 18)
     * @param string $type exclusive / inclusive
     * @param array $options split percentages
     * @return array
     */
    public function calculate($amount, $percentage, $type = 'exclusive', $options = [])
    {
        $gstAmount = 0;
        $finalTotal = $amount;

        if ($percentage > 0) {
            if ($type === 'inclusive') {
                $gstAmount = $amount - ($amount / (1 + ($percentage / 100)));
                $finalTotal = $amount; 
            } else {
                $gstAmount = $amount * ($percentage / 100);
                $finalTotal = $amount + $gstAmount;
            }
        }

        // Use explicit percentages from options, or default to 50/50 split of the total percentage
        $cgstPercentage = isset($options['cgst_percentage']) ? (float)$options['cgst_percentage'] : ($percentage / 2);
        $sgstPercentage = isset($options['sgst_percentage']) ? (float)$options['sgst_percentage'] : ($percentage / 2);
        
        $cgst = 0;
        $sgst = 0;
        
        if ($gstAmount > 0) {
            // Calculate exact ratio based on percentages
            // (Amount * Individual %) / 100
            if ($type === 'inclusive') {
                // For inclusive, we need to extract tax based on individual components
                $basePrice = $amount / (1 + ($percentage / 100));
                $cgst = $basePrice * ($cgstPercentage / 100);
                $sgst = $basePrice * ($sgstPercentage / 100);
            } else {
                $cgst = $amount * ($cgstPercentage / 100);
                $sgst = $amount * ($sgstPercentage / 100);
            }
        }

        return [
            'gst_percentage' => (float)$percentage,
            'gst_amount' => round($gstAmount, 2),
            'cgst' => round($cgst, 2),
            'sgst' => round($sgst, 2),
            'cgst_percentage' => (float)$cgstPercentage,
            'sgst_percentage' => (float)$sgstPercentage,
            'gst_type' => $type,
            'total' => round($finalTotal, 2)
        ];
    }
}
