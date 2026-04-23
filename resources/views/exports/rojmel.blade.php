<table>
    <thead>
        <tr>
            <th colspan="{{ $isSummarySheet ? 4 : 7 }}" style="text-align: center; font-size: 22pt; font-weight: bold; border: none; color: #15803d;">NEW VRUNDAVAN NURSERY</th>
        </tr>
        @if($isSummarySheet)
            <!-- Summary Sheet Header -->
            <tr>
                <th colspan="4" style="text-align: center; font-size: 16pt; font-weight: bold; border: none; color: #475569;">વાર્ષિક સારાંશ (Yearly Summary)</th>
            </tr>
            <tr style="height: 20px;"><th colspan="4" style="border: none;"></th></tr>
            <tr>
                <th style="border: 2pt solid #000000; text-align: center; font-weight: bold; background-color: #f1f5f9;">માસ</th>
                <th style="border: 2pt solid #000000; text-align: center; font-weight: bold; background-color: #dcfce7;">કુલ આવક (Income)</th>
                <th style="border: 2pt solid #000000; text-align: center; font-weight: bold; background-color: #fee2e2;">કુલ જાવક (Expense)</th>
                <th style="border: 2pt solid #000000; text-align: center; font-weight: bold; background-color: #f1f5f9;">સિલક (Balance)</th>
            </tr>
        @else
            <!-- Daily Detail Sheet Header -->
            <tr>
                <th colspan="7" style="text-align: center; font-size: 16pt; font-weight: bold; border: none; color: #475569;">આવક - જાવક રિપોર્ટ</th>
            </tr>
            @if(isset($dateRange) && $dateRange)
            <tr>
                <th colspan="7" style="text-align: center; font-size: 12pt; font-weight: bold; border: none; color: #64748b;">( {{ $dateRange }} સુધી )</th>
            </tr>
            @endif
            <tr style="height: 10px;"><th colspan="7" style="border: none;"></th></tr>
            <tr>
                <th rowspan="2" style="border: 2.5pt solid #000000; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f1f5f9;">તારીખ</th>
                <th colspan="2" style="border: 2.5pt solid #000000; text-align: center; font-weight: bold; background-color: #dcfce7; font-size: 12pt;">જમા</th>
                <th colspan="3" style="border: 2.5pt solid #000000; text-align: center; font-weight: bold; background-color: #fee2e2; font-size: 12pt;">ઉધાર</th>
                <th rowspan="2" style="border: 2.5pt solid #000000; text-align: center; vertical-align: middle; font-weight: bold; background-color: #f1f5f9; font-size: 12pt;">સિલક</th>
            </tr>
            <tr>
                <th style="border: 2.5pt solid #000000; text-align: center; font-weight: bold; background-color: #f8fafc;">વિગત</th>
                <th style="border: 2.5pt solid #000000; text-align: center; font-weight: bold; background-color: #f8fafc;">રૂપિયા</th>
                <th style="border: 2.5pt solid #000000; text-align: center; font-weight: bold; background-color: #f8fafc;">વિગત</th>
                <th style="border: 2.5pt solid #000000; text-align: center; font-weight: bold; background-color: #f8fafc;">રૂપિયા</th>
                <th style="border: 2.5pt solid #000000; text-align: center; font-weight: bold; background-color: #f8fafc;">કુલ ઉધાર</th>
            </tr>
        @endif
    </thead>
    <tbody>
        @php 
            $totalIn = 0; 
            $totalOut = 0; 
            $firstBalance = collect($balances)->first();
            $lastBalance = collect($balances)->last();
            $initialOpening = $firstBalance ? $firstBalance->opening_balance : 0;
            $finalClosing = $lastBalance ? $lastBalance->closing_balance : 0;
        @endphp

        @unless($isSummarySheet)
            <!-- Opening Balance for Daily Detail -->
            <tr>
                <td style="border: 2px solid #000000; text-align: center; font-weight: bold; background-color: #f1f5f9;">-</td>
                <td style="border: 2px solid #000000; text-align: left; font-weight: bold; background-color: #f1f5f9;">{{ str_replace('Rojmel ', '', $title ?? 'રીપોર્ટ') }} - શરૂઆતની સિલક (Opening)</td>
                <td style="border: 2px solid #000000; text-align: right; font-weight: bold; background-color: #f1f5f9;">{{ number_format($initialOpening, 0, '.', '') }}</td>
                <td style="border: 2px solid #000000; text-align: left; font-weight: bold; background-color: #f1f5f9;">-</td>
                <td style="border: 2px solid #000000; text-align: right; font-weight: bold; background-color: #f1f5f9;">-</td>
                <td style="border: 2px solid #000000; text-align: right; font-weight: bold; background-color: #f1f5f9;">-</td>
                <td style="border: 2px solid #000000; text-align: right; font-weight: bold; background-color: #f8fafc;">{{ number_format($initialOpening, 0, '.', '') }}</td>
            </tr>
        @endunless

        @foreach($balances as $b)
            @php 
                $isSummaryRow = !isset($b->date) || !is_object($b->date);
                $dateStr = !$isSummaryRow ? (is_string($b->date) ? $b->date : $b->date->format('Y-m-d')) : null;
                
                $avakEntries = $dateStr ? ($entries[$dateStr]->where('type', 'avak')->values() ?? collect()) : collect();
                $javakEntries = $dateStr ? ($entries[$dateStr]->where('type', 'javak')->values() ?? collect()) : collect();
                
                $dayAvak = data_get($b, 'total_avak') ?? data_get($b, 'income', 0);
                $dayJavak = data_get($b, 'total_javak') ?? data_get($b, 'expense', 0);
                
                $totalIn += (float)$dayAvak;
                $totalOut += (float)$dayJavak;
                $closingBalance = data_get($b, 'closing_balance', 0);
            @endphp
            
            @if($isSummarySheet)
                <!-- Row for Summary Sheet (Month-wise) -->
                <tr>
                    <td style="border: 1px solid #000000; text-align: center; font-weight: bold; padding: 5px;">{{ $b->month_name }}</td>
                    <td style="border: 1px solid #000000; text-align: right; color: #16a34a; font-weight: bold; padding: 5px;">{{ number_format($dayAvak, 0, '.', '') }}</td>
                    <td style="border: 1px solid #000000; text-align: right; color: #dc2626; font-weight: bold; padding: 5px;">{{ number_format($dayJavak, 0, '.', '') }}</td>
                    <td style="border: 1px solid #000000; text-align: right; font-weight: bold; padding: 5px; background-color: #f1f5f9;">{{ number_format($closingBalance, 0, '.', '') }}</td>
                </tr>
            @else
                <!-- Original logic for Detail/Monthly sheets -->
                @php $maxRows = max($avakEntries->count(), $javakEntries->count(), 1); @endphp
                @for($i = 0; $i < $maxRows; $i++)
                    @php 
                        $ae = $avakEntries->get($i);
                        $je = $javakEntries->get($i);
                    @endphp
                    <tr>
                        <td style="border: 1px solid #000000; text-align: center; vertical-align: middle;">
                            {{ ($i === 0 && isset($b->date) && is_object($b->date)) ? $b->date->format('j/n/Y') : ($i === 0 ? ($b->month_name ?? '') : '') }}
                        </td>
                        <td style="border: 1px solid #000000; text-align: left; padding: 4px;">
                            {{ $ae ? ($ae->description . ($ae->category ? " ({$ae->category})" : "")) : '' }}
                        </td>
                        <td style="border: 1px solid #000000; text-align: right; padding: 4px;">{{ $ae ? number_format($ae->amount, 0, '.', '') : '' }}</td>
                        <td style="border: 1px solid #000000; text-align: left; padding: 4px;">
                            {{ $je ? ($je->description . ($je->category ? " ({$je->category})" : "")) : '' }}
                        </td>
                        <td style="border: 1px solid #000000; text-align: right; padding: 4px;">{{ $je ? number_format($je->amount, 0, '.', '') : '' }}</td>
                        <td style="border: 1px solid #000000; text-align: right; font-weight: bold; vertical-align: bottom;">{{ $i === ($maxRows - 1) ? number_format($dayJavak, 0, '.', '') : '' }}</td>
                        <td style="border: 1px solid #000000; text-align: right; font-weight: bold; vertical-align: bottom; background-color: #f8fafc;">{{ $i === ($maxRows - 1) ? number_format($closingBalance, 0, '.', '') : '' }}</td>
                    </tr>
                @endfor
            @endif
        @endforeach

        @unless($isSummarySheet)
            <!-- Final Closing Balance for Daily Detail -->
            <tr>
                <td style="border: 2px solid #000000; text-align: center; font-weight: bold; background-color: #f1f5f9;">-</td>
                <td style="border: 2px solid #000000; text-align: left; font-weight: bold; background-color: #f1f5f9;">{{ str_replace('Rojmel ', '', $title ?? 'રીપોર્ટ') }} - આખર સિલક (Closing)</td>
                <td style="border: 2px solid #000000; text-align: right; font-weight: bold; background-color: #f1f5f9;">-</td>
                <td style="border: 2px solid #000000; text-align: left; font-weight: bold; background-color: #f1f5f9;">-</td>
                <td style="border: 2px solid #000000; text-align: right; font-weight: bold; background-color: #f1f5f9;">-</td>
                <td style="border: 2px solid #000000; text-align: right; font-weight: bold; background-color: #f1f5f9;">-</td>
                <td style="border: 2px solid #000000; text-align: right; font-weight: bold; background-color: #f1f5f9; color: #2563eb;">{{ number_format($finalClosing, 0, '.', '') }}</td>
            </tr>
        @endunless
    </tbody>
    <tfoot>
        @if($isSummarySheet)
            <tr style="height: 10px;"><td colspan="4" style="border: none;"></td></tr>
            <tr style="font-weight: bold; background-color: #e2e8f0;">
                <td style="border: 2px solid #000000; text-align: right;">કુલ સરવાળા (Total):</td>
                <td style="border: 2px solid #000000; text-align: right; color: #16a34a;">{{ number_format($totalIn, 0, '.', '') }}</td>
                <td style="border: 2px solid #000000; text-align: right; color: #dc2626;">{{ number_format($totalOut, 0, '.', '') }}</td>
                <td style="border: 2px solid #000000; text-align: right; background-color: #cbd5e1; color: #2563eb;">{{ number_format($finalClosing, 0, '.', '') }}</td>
            </tr>
        @else
            <tr style="height: 15px;"><td colspan="7" style="border: none;"></td></tr>
            <tr style="font-weight: bold; background-color: #e2e8f0;">
                <td style="border: 2px solid #000000; text-align: right; padding: 8px;">ગ્રાન્ડ ટોટલ (Grand Total):</td>
                <td colspan="2" style="border: 2px solid #000000; text-align: right; color: #16a34a;">{{ number_format($totalIn, 0, '.', '') }}</td>
                <td colspan="3" style="border: 2px solid #000000; text-align: right; color: #dc2626; padding: 8px;">{{ number_format($totalOut, 0, '.', '') }}</td>
                <td style="border: 2px solid #000000; text-align: right; background-color: #cbd5e1; padding: 8px; color: #2563eb;">{{ number_format($finalClosing, 0, '.', '') }}</td>
            </tr>
        @endif
    </tfoot>
</table>
