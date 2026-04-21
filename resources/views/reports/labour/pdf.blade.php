<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Labour Report - {{ $month }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #1a1a1a; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; font-weight: bold; }
        .summary { margin-bottom: 20px; }
        .summary table { width: 100%; border-collapse: collapse; }
        .summary td { padding: 10px; border: 1px solid #eee; background: #f9f9f9; }
        .summary .label { font-weight: bold; color: #666; font-size: 10px; text-transform: uppercase; }
        .summary .value { font-size: 18px; font-weight: bold; color: #111; }
        table.details { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.details th { background: #f0f0f0; border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 10px; text-transform: uppercase; }
        table.details td { border: 1px solid #ddd; padding: 8px; }
        .total-row { font-weight: bold; background: #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 10px; color: #aaa; }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Vrundavan Nursery</h1>
        <p>LABOUR WAGES REPORT</p>
        <p style="font-size: 14px; margin-top: 5px; color: #333;">
            @if($month)
                {{ \Carbon\Carbon::parse($month.'-01')->format('F Y') }}
            @else
                {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
            @endif
        </p>
        @if($worker)
            <div style="margin-top: 10px; font-size: 16px; font-weight: bold; color: #4f46e5;">Worker: {{ $worker->name }}</div>
        @endif
    </div>

    <div class="summary">
        <table>
            <tr>
                <td>
                    <div class="label">Total Wages</div>
                    <div class="value">₹ {{ number_format($details->sum('wage_amount'), 2) }}</div>
                </td>
                <td>
                    <div class="label">Total Working Days</div>
                    <div class="value">{{ $details->groupBy('labour_entry_id')->count() }}</div>
                </td>
                <td>
                    <div class="label">Report Generated</div>
                    <div class="value" style="font-size: 14px;">{{ date('d-m-Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <table class="details">
        <thead>
            <tr>
                <th colspan="5" style="background: #eef2ff; color: #4f46e5; text-align: left;">DAILY EARNINGS</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Worker Name</th>
                <th>Work Type</th>
                <th>Attendance</th>
                <th class="text-right">Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($details as $detail)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($detail->entry->date)->format('d-m-Y') }}</td>
                    <td>{{ $detail->worker->name }}</td>
                    <td>{{ $detail->work_type }}</td>
                    <td class="text-center">
                        @if($detail->attendance_type == 'full') Full Day @elseif($detail->attendance_type == 'half') Half Day @else {{ $detail->hours }} Hours @endif
                    </td>
                    <td class="text-right">{{ number_format($detail->wage_amount, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="4" class="text-right">TOTAL EARNINGS</td>
                <td class="text-right">₹ {{ number_format($details->sum('wage_amount'), 2) }}</td>
            </tr>
        </tbody>
    </table>

    @if($advances->count() > 0)
    <table class="details" style="margin-top: 30px;">
        <thead>
            <tr>
                <th colspan="3" style="background: #fff1f2; color: #e11d48; text-align: left;">ADVANCES (UPAD) PAID</th>
            </tr>
            <tr>
                <th>Date</th>
                <th>Worker Name / Note</th>
                <th class="text-right">Advance Amount (₹)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($advances as $advance)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($advance->date)->format('d-m-Y') }}</td>
                    <td>{{ $advance->worker->name }} {{ $advance->note ? '('.$advance->note.')' : '' }}</td>
                    <td class="text-right" style="color: #e11d48;">{{ number_format($advance->amount, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2" class="text-right">TOTAL ADVANCE</td>
                <td class="text-right" style="color: #e11d48;">₹ {{ number_format($advances->sum('amount'), 2) }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <div style="margin-top: 40px; border: 2px solid #000; padding: 20px;">
        <h3 style="margin-top: 0; border-bottom: 1px solid #000; padding-bottom: 5px;">MONTHLY SETTLEMENT SUMMARY</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="font-size: 14px; font-weight: bold;">
                <td style="padding: 10px 0;">Total Earnings (+)</td>
                <td style="text-align: right; color: #16a34a;">₹ {{ number_format($details->sum('wage_amount'), 2) }}</td>
            </tr>
            <tr style="font-size: 14px; font-weight: bold;">
                <td style="padding: 10px 0;">Total Advance Paid (-)</td>
                <td style="text-align: right; color: #e11d48;">₹ {{ number_format($advances->sum('amount'), 2) }}</td>
            </tr>
            <tr style="font-size: 18px; font-weight: 900; background: #f0f0f0;">
                <td style="padding: 15px; border-top: 2px solid #000;">FINAL NET PAYABLE</td>
                <td style="text-align: right; padding: 15px; border-top: 2px solid #000;">₹ {{ number_format($details->sum('wage_amount') - $advances->sum('amount'), 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Generated by Nursery Management System
    </div>
</body>
</html>
