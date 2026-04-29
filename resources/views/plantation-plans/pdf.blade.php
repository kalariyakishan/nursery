<!DOCTYPE html>
<html lang="gu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>વાવેતર યોજના રિપોર્ટ</title>
    <style>
        @font-face {
            font-family: 'NotoSansGujarati';
            src: url('{{ public_path('fonts/NotoSansGujarati-Regular.ttf') }}') format('truetype');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'NotoSansGujarati';
            src: url('{{ public_path('fonts/NotoSansGujarati-Bold.ttf') }}') format('truetype');
            font-weight: bold;
            font-style: normal;
        }
        body {
            font-family: 'NotoSansGujarati', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #1E1B4B;
            margin: 0 0 5px 0;
            font-size: 24px;
        }
        .header h2 {
            color: #4338CA;
            margin: 0 0 5px 0;
            font-size: 18px;
        }
        .header p {
            margin: 0;
            color: #6B7280;
            font-size: 14px;
        }
        .grid {
            width: 100%;
            margin-bottom: 20px;
        }
        .grid-item {
            display: inline-block;
            width: 48%;
            vertical-align: top;
        }
        .card {
            background-color: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }
        .card-title {
            font-weight: bold;
            color: #4F46E5;
            border-bottom: 1px solid #E5E7EB;
            padding-bottom: 5px;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .stat-row {
            margin-bottom: 8px;
            font-size: 14px;
        }
        .stat-label {
            font-weight: bold;
            color: #4B5563;
        }
        .highlight {
            font-size: 18px;
            font-weight: bold;
            color: #059669;
        }
        .map-container {
            text-align: center;
            margin: 20px 0;
            border: 2px solid #E5E7EB;
            padding: 5px;
            background: #fff;
        }
        .map-container img {
            max-width: 100%;
            height: auto;
        }
        .instructions {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            margin-top: 20px;
        }
        .instructions h3 {
            margin-top: 0;
            color: #B45309;
            font-size: 16px;
        }
        .instructions ul {
            margin: 0;
            padding-left: 20px;
        }
        .instructions li {
            margin-bottom: 5px;
            font-size: 14px;
            color: #78350F;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #9CA3AF;
            border-top: 1px solid #E5E7EB;
            padding-top: 10px;
        }
        .method-badge {
            background: #E0E7FF;
            color: #3730A3;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>New Vrundavan Nursery</h1>
        <h2>વાવેતર યોજના રિપોર્ટ</h2>
        <p>પ્લાન: <strong>{{ $plan->name }}</strong> | તારીખ: {{ $plan->created_at->format('d/m/Y') }}</p>
    </div>

    <div class="grid">
        <div class="grid-item">
            <div class="card">
                <div class="card-title">📍 જમીન વિગતો</div>
                <div class="stat-row">
                    <span class="stat-label">કુલ વિસ્તાર:</span> {{ number_format($plan->area, 2) }} ચોરસ મીટર
                </div>
                <div class="stat-row">
                    <span class="stat-label">એકર માં વિસ્તાર:</span> {{ number_format($plan->area * 0.000247105, 2) }} એકર
                </div>
                <div class="stat-row">
                    <span class="stat-label">સ્થળ:</span> નકશા મુજબ
                </div>
            </div>
            
            <div class="card">
                <div class="card-title">🌱 વાવેતર માહિતી</div>
                <div class="stat-row">
                    <span class="stat-label">વાવેતર પદ્ધતિ:</span> 
                    <span class="method-badge">
                        @if($plan->method == 'grid')
                            Grid (સીધી લાઈનમાં)
                        @elseif($plan->method == 'zigzag')
                            Zig-Zag (ઝિગ-ઝેગ)
                        @elseif($plan->method == 'random')
                            Random (રેન્ડમ)
                        @else
                            {{ ucfirst($plan->method) }}
                        @endif
                    </span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">લાઈન અંતર:</span> {{ $plan->row_spacing }} મીટર
                </div>
                <div class="stat-row">
                    <span class="stat-label">છોડ અંતર:</span> {{ $plan->plant_spacing }} મીટર
                </div>
            </div>
        </div>

        <div class="grid-item" style="margin-left: 2%;">
            <div class="card" style="border-color: #10B981; background-color: #ECFDF5;">
                <div class="card-title" style="color: #047857; border-bottom-color: #A7F3D0;">📊 કુલ ગણતરી</div>
                <div style="text-align: center; padding: 15px 0;">
                    <div style="color: #065F46; font-size: 14px; margin-bottom: 5px;">કુલ છોડ:</div>
                    <div class="highlight" style="font-size: 32px;">{{ number_format($plan->total_plants) }}</div>
                </div>
                <div style="text-align: center; border-top: 1px solid #A7F3D0; padding-top: 10px; margin-top: 10px;">
                    <span class="stat-label">ઘનતા:</span> {{ number_format($plan->total_plants / max($plan->area * 0.000247105, 0.0001)) }} છોડ/એકર
                </div>
            </div>

            @if($plan->irrigationPlan)
            <div class="card" style="border-color: #3B82F6; background-color: #EFF6FF; margin-top: 15px;">
                <div class="card-title" style="color: #1E40AF; border-bottom-color: #BFDBFE;">💧 સિંચાઈ યોજના</div>
                <div class="stat-row">
                    <span class="stat-label">સિંચાઈ પ્રકાર:</span> {{ ucfirst($plan->irrigationPlan->irrigation_type) }}
                </div>
                <div class="stat-row">
                    <span class="stat-label">મુખ્ય પાઇપ:</span> {{ number_format($plan->irrigationPlan->total_main_pipe_length) }} મીટર
                </div>
                <div class="stat-row">
                    <span class="stat-label">પેટા પાઇપ:</span> {{ number_format($plan->irrigationPlan->total_sub_pipe_length) }} મીટર
                </div>
                @if($plan->irrigationPlan->irrigation_type == 'drip')
                <div class="stat-row">
                    <span class="stat-label">કુલ ડ્રિપર:</span> <span style="font-weight: bold; color: #1D4ED8;">{{ number_format($plan->irrigationPlan->total_drippers) }}</span> ({{ $plan->irrigationPlan->drippers_per_plant }} ડ્રિપર/છોડ)
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    @if($mapBase64)
    <div class="map-container">
        <div class="card-title" style="border: none; text-align: left; padding-left: 10px;">🗺️ નકશો</div>
        <img src="{{ $mapBase64 }}" alt="Map Snapshot">
    </div>
    @endif

    <div class="instructions">
        <h3>📌 માર્ગદર્શન (મજૂરો માટે)</h3>
        <ul>
            <li>આ નકશા પ્રમાણે વાવેતર કરવું. નકશામાં દર્શાવેલ હદની અંદર જ છોડ રોપવા.</li>
            <li>દરેક છોડ વચ્ચે <strong>{{ $plan->plant_spacing }} મીટર</strong> અંતર રાખવું.</li>
            <li>દરેક લાઈન વચ્ચે <strong>{{ $plan->row_spacing }} મીટર</strong> અંતર રાખવું.</li>
            @if($plan->method == 'zigzag')
            <li>ઝિગ-ઝેગ પદ્ધતિમાં બીજી લાઈન પહેલી લાઈનના છોડની વચ્ચે આવે તે રીતે આગળ-પાછળ રાખવી.</li>
            @endif
            <li>જમીનના કોઈ પણ એક ખૂણા થી શરુ કરીને સીધી લાઈન પ્રમાણે વાવેતર કરવું.</li>
        </ul>
    </div>

    @if($plan->irrigationPlan)
    <div class="instructions" style="background-color: #EFF6FF; border-left-color: #3B82F6;">
        <h3 style="color: #1E40AF;">📌 સિંચાઈ માર્ગદર્શન</h3>
        <ul>
            <li>નકશામાં વાદળી લાઇન પ્રમાણે મુખ્ય અને પેટા પાઇપલાઇન નાખવી.</li>
            <li>W અક્ષર વાળું વાદળી ટપકું પાણીનો મુખ્ય સ્ત્રોત દર્શાવે છે, ત્યાંથી કનેક્શન લેવું.</li>
            @if($plan->irrigationPlan->irrigation_type == 'drip')
            <li>દરેક છોડ પાસે <strong>{{ $plan->irrigationPlan->drippers_per_plant }} ડ્રિપર</strong> મુકવા.</li>
            @endif
            <li>લાઇન ચાલુ કરીને લીકેજ ચેક કરી લેવું.</li>
        </ul>
    </div>
    @endif

    <div class="footer">
        Generated by New Vrundavan Nursery System | {{ date('d-M-Y h:i A') }}
    </div>

</body>
</html>
