<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rojmel Report - {{ $startDate }} to {{ $endDate }}</title>
    <style>
        body { font-family: sans-serif; font-size: 10px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; }
        table.details { width: 100%; border-collapse: collapse; }
        table.details th { background: #f5f5f5; border: 1px solid #ddd; padding: 6px; text-transform: uppercase; }
        table.details td { border: 1px solid #ddd; padding: 6px; }
        .text-right { text-align: right; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 8px; color: #aaa; }
        .summary-box { padding: 15px; border: 1px solid #333; margin-bottom: 20px; background: #fafafa; }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Vrundavan Nursery</h1>
        <p>ROJMEL RANGE REPORT: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    <div class="summary-box">
        <table width="100%">
            <tr>
                <td><strong>Total Avak (+)</strong>: ₹ {{ number_format($balances->sum('total_avak'), 2) }}</td>
                <td><strong>Total Javak (-)</strong>: ₹ {{ number_format($balances->sum('total_javak'), 2) }}</td>
                <td class="text-right"><strong>Net Change</strong>: ₹ {{ number_format($balances->sum('total_avak') - $balances->sum('total_javak'), 2) }}</td>
            </tr>
        </table>
    </div>

    <table class="details">
        <thead>
            <tr>
                <th>Date</th>
                <th class="text-right">Opening Balance</th>
                <th class="text-right">Day Avak (+)</th>
                <th class="text-right">Day Javak (-)</th>
                <th class="text-right">Closing Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($balances as $b)
                <tr>
                    <td>{{ $b->date->format('d/m/Y') }}</td>
                    <td class="text-right">{{ number_format($b->opening_balance, 2) }}</td>
                    <td class="text-right" style="color: green;">+ {{ number_format($b->total_avak, 2) }}</td>
                    <td class="text-right" style="color: red;">- {{ number_format($b->total_javak, 2) }}</td>
                    <td class="text-right" style="font-weight: bold;">{{ number_format($b->closing_balance, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Generated on: {{ date('d-m-Y h:i A') }} - Nursery Management System
    </div>
</body>
</html>
