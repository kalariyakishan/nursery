<table>
    <thead>
        <!-- Professional Header -->
        <tr>
            <th colspan="6" style="text-align: center; font-size: 22pt; font-weight: bold; border: none; color: #15803d;">NEW VRUNDAVAN NURSERY</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-size: 14pt; font-weight: bold; color: #2563eb;">{{ $worker->name }} ની હાજરી તથા ઉપાડ</th>
        </tr>
        <tr>
            <th colspan="6" style="text-align: center; font-size: 11pt; font-weight: bold; color: #4b5563;">{{ $period }}</th>
        </tr>
        <tr><th></th></tr>

        @if($isSummary)
            <!-- Summary Mode Header -->
            <tr>
                <th style="background-color: #2563eb; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">માસ / વર્ષ</th>
                <th style="background-color: #2563eb; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">આગળની બાકી</th>
                <th style="background-color: #2563eb; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">હાજરી (મજૂરી)</th>
                <th style="background-color: #2563eb; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">ઉપાડ</th>
                <th style="background-color: #2563eb; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">ચૂકવેલ (Paid)</th>
                <th style="background-color: #2563eb; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">કુલ બાકી</th>
            </tr>
        @else
            <!-- Daily Mode Header -->
            <tr>
                <th style="background-color: #4b5563; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;">તારીખ</th>
                <th style="background-color: #4b5563; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">હાજરી (રકમ)</th>
                <th style="background-color: #4b5563; color: #ffffff; font-weight: bold; border: 1px solid #000000;">કામની વિગત</th>
                <th style="background-color: #4b5563; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">ઉપાડ (રકમ)</th>
                <th style="background-color: #4b5563; color: #ffffff; font-weight: bold; border: 1px solid #000000;">નોંધ</th>
                <th style="background-color: #4b5563; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">સિલક</th>
            </tr>
        @endif
    </thead>
    <tbody>
        @php 
            $totalWage = 0; 
            $totalUpad = 0; 
            $runningBalance = $openingBalance ?? 0;
        @endphp

        @unless($isSummary)
            <!-- Opening Balance Row for Daily Mode -->
            <tr>
                <td style="border: 1px solid #cccccc; background-color: #f8fafc; font-weight: bold;" colspan="2">આગળની બાકી (Opening Balance)</td>
                <td style="border: 1px solid #cccccc; background-color: #f8fafc;" colspan="3"></td>
                <td style="border: 1px solid #cccccc; background-color: #f8fafc; text-align: right; font-weight: bold;">{{ number_format($openingBalance ?? 0, 2, '.', '') }}</td>
            </tr>
        @endunless
        @foreach($rows as $row)
            @php 
                if ($isSummary) {
                    $totalWage += $row->earnings;
                    $totalUpad += ($row->upad + $row->paid);
                } else {
                    $totalWage += $row->wage;
                    $totalUpad += $row->upad;
                    $runningBalance += ($row->wage - $row->upad);
                }
            @endphp
            <tr>
                @if($isSummary)
                    <td style="border: 1px solid #cccccc; text-align: center;">{{ $row->month_name }}</td>
                    <td style="border: 1px solid #cccccc; text-align: right;">{{ number_format($row->opening_balance, 2, '.', '') }}</td>
                    <td style="border: 1px solid #cccccc; text-align: right;">{{ number_format($row->earnings, 2, '.', '') }}</td>
                    <td style="border: 1px solid #cccccc; text-align: right; color: #dc2626;">{{ number_format($row->upad, 2, '.', '') }}</td>
                    <td style="border: 1px solid #cccccc; text-align: right; color: #2563eb;">{{ number_format($row->paid, 2, '.', '') }}</td>
                    <td style="border: 1px solid #cccccc; text-align: right; font-weight: bold; color: #2563eb;">{{ number_format($row->balance, 2, '.', '') }}</td>
                @else
                    <td style="border: 1px solid #cccccc; text-align: center;">{{ $row->date->format('d/m/Y') }}</td>
                    <td style="border: 1px solid #cccccc; text-align: right; color: #16a34a;">{{ $row->wage > 0 ? number_format($row->wage, 2, '.', '') : '' }}</td>
                    <td style="border: 1px solid #cccccc; font-size: 9pt;">{{ $row->work_type }}</td>
                    <td style="border: 1px solid #cccccc; text-align: right; color: #dc2626;">{{ $row->upad > 0 ? number_format($row->upad, 2, '.', '') : '' }}</td>
                    <td style="border: 1px solid #cccccc; font-size: 9pt; color: #d97706;">{{ $row->upad_note }}</td>
                    <td style="border: 1px solid #cccccc; text-align: right; font-weight: bold;">{{ number_format($runningBalance, 2, '.', '') }}</td>
                @endif
            </tr>
        @endforeach

        @unless($isSummary)
            <!-- Closing Balance Row for Daily Mode -->
            <tr>
                <td style="border: 1px solid #cccccc; background-color: #f8fafc; font-weight: bold;" colspan="2">કુલ બાકી (Closing Balance)</td>
                <td style="border: 1px solid #cccccc; background-color: #f8fafc;" colspan="3"></td>
                <td style="border: 1px solid #cccccc; background-color: #f8fafc; text-align: right; font-weight: bold; color: #2563eb;">{{ number_format($runningBalance, 2, '.', '') }}</td>
            </tr>
        @endunless
    </tbody>
    <tfoot>
        <tr style="font-weight: bold;">
            <td style="text-align: right; border: 1px solid #000000; background-color: #f3f4f6;">કુલ (Total):</td>
            <td style="text-align: right; border: 1px solid #000000; background-color: #f3f4f6; color: #16a34a;">{{ number_format($totalWage, 2, '.', '') }}</td>
            <td style="background-color: #f3f4f6; border: 1px solid #000000;"></td>
            <td style="text-align: right; border: 1px solid #000000; background-color: #f3f4f6; color: #dc2626;">{{ number_format($totalUpad, 2, '.', '') }}</td>
            <td style="background-color: #f3f4f6; border: 1px solid #000000;"></td>
            <td style="text-align: right; border: 1px solid #000000; background-color: #f3f4f6; color: #2563eb;">
                {{ $isSummary ? number_format($totalWage - $totalUpad, 2, '.', '') : number_format($runningBalance, 2, '.', '') }}
            </td>
        </tr>
    </tfoot>
</table>
