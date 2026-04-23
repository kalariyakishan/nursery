<table>
    <thead>
        <!-- Professional Header -->
        <tr>
            <th colspan="5" style="text-align: center; font-size: 22pt; font-weight: bold; border: none; color: #15803d;">NEW VRUNDAVAN NURSERY</th>
        </tr>
        <tr>
            <th colspan="5" style="text-align: center; font-size: 14pt; font-weight: bold; color: #475569;">મજૂરી પતાવટ રીપોર્ટ (Labour Settlement)</th>
        </tr>
        @if($worker)
        <tr>
            <th colspan="5" style="text-align: center; font-size: 11pt; font-weight: bold; color: #2563eb;">મજૂરનું નામ: {{ $worker->name }}</th>
        </tr>
        @endif
        @if($dateRange)
        <tr>
            <th colspan="5" style="text-align: center; font-size: 10pt; font-weight: bold; color: #1d4ed8;">ગાળો: {{ $dateRange }}</th>
        </tr>
        @endif
        <tr>
            <th colspan="5" style="text-align: center; font-size: 9pt; color: #6b7280;">જનરેટ તારીખ: {{ date('d/m/Y h:i A') }}</th>
        </tr>
        <tr><th></th></tr>

        <!-- Summary Section -->
        <tr>
            <th colspan="7" style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000000;">પતાવટનો સારાંશ (Settlement Summary)</th>
        </tr>
        <tr>
            <th style="background-color: #2563eb; color: #ffffff; font-weight: bold; border: 1px solid #000000;">મજૂરનું નામ</th>
            <th style="background-color: #2563eb; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: center;"> દિવસો</th>
            <th style="background-color: #2563eb; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">આગળની બાકી</th>
            <th style="background-color: #2563eb; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">કુલ મજૂરી (+)</th>
            <th style="background-color: #2563eb; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">કુલ ઉપાડ (-)</th>
            <th style="background-color: #2563eb; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">ચૂકવેલ (Paid)</th>
            <th style="background-color: #2563eb; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">ચૂકવવાપાત્ર બાકી</th>
        </tr>
    </thead>
    <tbody>
        @foreach($settlements as $s)
        <tr>
            <td style="border: 1px solid #cccccc;">{{ $s->worker->name }}</td>
            <td style="border: 1px solid #cccccc; text-align: center;">{{ $s->total_days }}</td>
            <td style="border: 1px solid #cccccc; text-align: right;">{{ number_format($s->opening_balance, 2, '.', '') }}</td>
            <td style="border: 1px solid #cccccc; text-align: right;">{{ number_format($s->total_earnings, 2, '.', '') }}</td>
            <td style="border: 1px solid #cccccc; text-align: right; color: #dc2626;">{{ number_format($s->total_advance, 2, '.', '') }}</td>
            <td style="border: 1px solid #cccccc; text-align: right; color: #2563eb;">{{ number_format($s->total_paid, 2, '.', '') }}</td>
            <td style="border: 1px solid #cccccc; text-align: right; font-weight: bold; color: #15803d;">{{ number_format($s->final_payable, 2, '.', '') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            <th colspan="4" style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000000;">મજૂરીની વિગતવાર એન્ટ્રી (Detailed Earnings)</th>
        </tr>
        <tr>
            <th style="background-color: #4b5563; color: #ffffff; font-weight: bold; border: 1px solid #000000;">તારીખ</th>
            <th style="background-color: #4b5563; color: #ffffff; font-weight: bold; border: 1px solid #000000;">મજૂરનું નામ</th>
            <th style="background-color: #4b5563; color: #ffffff; font-weight: bold; border: 1px solid #000000;">કામનો પ્રકાર</th>
            <th style="background-color: #4b5563; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">રકમ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($details as $d)
        <tr>
            <td style="border: 1px solid #cccccc;">{{ \Carbon\Carbon::parse($d->entry->date)->format('d/m/Y') }}</td>
            <td style="border: 1px solid #cccccc;">{{ $d->worker->name }}</td>
            <td style="border: 1px solid #cccccc;">{{ $d->work_type ?: '-' }}</td>
            <td style="border: 1px solid #cccccc; text-align: right;">{{ number_format($d->wage_amount, 2, '.', '') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<table>
    <thead>
        <tr><th></th></tr>
        <tr>
            <th colspan="4" style="background-color: #f3f4f6; font-weight: bold; border: 1px solid #000000;">ઉપાડની વિગતવાર એન્ટ્રી (Detailed Advances)</th>
        </tr>
        <tr>
            <th style="background-color: #dc2626; color: #ffffff; font-weight: bold; border: 1px solid #000000;">તારીખ</th>
            <th style="background-color: #dc2626; color: #ffffff; font-weight: bold; border: 1px solid #000000;">મજૂરનું નામ</th>
            <th style="background-color: #dc2626; color: #ffffff; font-weight: bold; border: 1px solid #000000;">નોંધ</th>
            <th style="background-color: #dc2626; color: #ffffff; font-weight: bold; border: 1px solid #000000; text-align: right;">રકમ (-)</th>
        </tr>
    </thead>
    <tbody>
        @foreach($advances as $a)
        <tr>
            <td style="border: 1px solid #cccccc;">{{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }}</td>
            <td style="border: 1px solid #cccccc;">{{ $a->worker->name }}</td>
            <td style="border: 1px solid #cccccc;">{{ $a->note ?: '-' }}</td>
            <td style="border: 1px solid #cccccc; text-align: right; color: #dc2626;">{{ number_format($a->amount, 2, '.', '') }}</td>
        </tr>
        @endforeach
        <tr><th></th></tr>
        <tr>
            <td colspan="4" style="text-align: right; font-style: italic; color: #9ca3af;">Authorised Signature: _______________________</td>
        </tr>
    </tbody>
</table>
