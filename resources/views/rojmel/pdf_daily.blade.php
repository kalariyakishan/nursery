<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Rojmel - {{ $date }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; }
        .summary { margin-bottom: 20px; width: 100%; border-collapse: collapse; }
        .summary td { border: 1px solid #ddd; padding: 10px; text-align: center; }
        .summary .label { font-size: 9px; text-transform: uppercase; color: #666; margin-bottom: 5px; }
        .summary .value { font-size: 16px; font-weight: bold; }
        table.details { width: 100%; border-collapse: collapse; }
        table.details th { background: #f5f5f5; border: 1px solid #ddd; padding: 8px; font-size: 9px; text-transform: uppercase; }
        table.details td { border: 1px solid #ddd; padding: 8px; }
        .text-right { text-align: right; }
        .avak { color: green; }
        .javak { color: red; }
        .footer { position: fixed; bottom: 0; width: 100%; text-align: right; font-size: 8px; color: #aaa; }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Vrundavan Nursery</h1>
        <p>DAILY ROJMEL - {{ \Carbon\Carbon::parse($date)->format('d/m/Y (l)') }}</p>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="label">Opening Balance</div>
                <div class="value">₹ {{ number_format($stats->opening_balance, 2) }}</div>
            </td>
            <td>
                <div class="label">Total Avak (+)</div>
                <div class="value" style="color: green;">₹ {{ number_format($stats->total_avak, 2) }}</div>
            </td>
            <td>
                <div class="label">Total Javak (-)</div>
                <div class="value" style="color: red;">₹ {{ number_format($stats->total_javak, 2) }}</div>
            </td>
            <td style="background: #f0f0f0;">
                <div class="label">Closing Balance</div>
                <div class="value" style="font-size: 18px;">₹ {{ number_format($stats->closing_balance, 2) }}</div>
            </td>
        </tr>
    </table>

    <table class="details">
        <thead>
            <tr>
                <th width="15%">Time</th>
                <th width="45%">Description / Category</th>
                <th width="20%" class="text-right">Avak (+)</th>
                <th width="20%" class="text-right">Javak (-)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entries as $entry)
                <tr>
                    <td>{{ $entry->created_at->format('h:i A') }}</td>
                    <td>
                        <strong>{{ $entry->description ?: '-' }}</strong>
                        <br><small>{{ $entry->category }}</small>
                    </td>
                    <td class="text-right avak">{{ $entry->type == 'avak' ? number_format($entry->amount, 2) : '-' }}</td>
                    <td class="text-right javak">{{ $entry->type == 'javak' ? number_format($entry->amount, 2) : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Printed on: {{ date('d-m-Y h:i A') }} - Nursery Management System
    </div>
</body>
</html>
