<!DOCTYPE html>
<html lang="gu">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Offer - {{ $offer->offer_no }}</title>
</head>

<body>
    @php
        $isPdf = true;
        if (!function_exists('gujaratiAmountInWordsUnified')) {
            function gujaratiAmountInWordsUnified($number)
            {
                if ($number == 0) return 'શૂન્ય';
                $number = round($number, 2);
                $whole = floor($number);
                $fraction = round(($number - $whole) * 100);
                $words = [
                    0 => '', 1 => 'એક', 2 => 'બે', 3 => 'ત્રણ', 4 => 'ચાર', 5 => 'પાંચ', 6 => 'છ', 7 => 'સાત', 8 => 'આઠ', 9 => 'નવ', 10 => 'દસ',
                    11 => 'અગિયાર', 12 => 'બાર', 13 => 'તેર', 14 => 'ચૌદ', 15 => 'પંદર', 16 => 'સોળ', 17 => 'સત્તર', 18 => 'અઢાર', 19 => 'ઓગણીસ', 20 => 'વીસ',
                    21 => 'એકવીસ', 22 => 'બાવીસ', 23 => 'તેવીસ', 24 => 'ચોવીસ', 25 => 'પચીસ', 26 => 'છવ્વીસ', 27 => 'સત્યાવીસ', 28 => 'અઠ્ઠાવીસ', 29 => 'ઓગણત્રીસ', 30 => 'ત્રીસ',
                    31 => 'એકત્રીસ', 32 => 'બત્રીસ', 33 => 'તેત્રીસ', 34 => 'ચોત્રીસ', 35 => 'પાંત્રીસ', 36 => 'છત્રીસ', 37 => 'સાડત્રીસ', 38 => 'આડત્રીસ', 39 => 'ઓગણચાળીસ', 40 => 'ચાલીસ',
                    41 => 'એકતાલીસ', 42 => 'બેતાલીસ', 43 => 'તેતાલીસ', 44 => 'ચોતાલીસ', 45 => 'પિસ્તતાલીસ', 46 => 'છેતાલીસ', 47 => 'સુડતાલીસ', 48 => 'અડતાલીસ', 49 => 'ઓગણપચાસ', 50 => 'પચાસ',
                    51 => 'એકાવન', 52 => 'બાવન', 53 => 'તેપન', 54 => 'ચોપન', 55 => 'પંચાવન', 56 => 'છપ્પન', 57 => 'સત્તાવન', 58 => 'અઠ્ઠાવન', 59 => 'ઓગણસાઠ', 60 => 'સાઠ',
                    61 => 'એકસાઠ', 62 => 'બાસઠ', 63 => 'ત્રેસઠ', 64 => 'ચોસઠ', 65 => 'પાસઠ', 66 => 'છાસઠ', 67 => 'સડસઠ', 68 => 'અડસઠ', 69 => 'ઓગણોસિત્તેર', 70 => 'સિત્તેર',
                    71 => 'એક્યોતેર', 72 => 'બોતેર', 73 => 'તોતેર', 74 => 'ચોતેર', 75 => 'પંચોતેર', 76 => 'છોતેર', 77 => 'સિત્યોતેર', 78 => 'ઈઠ્યોતેર', 79 => 'ઓગણાએંસી', 80 => 'એંસી',
                    81 => 'એક્યાસી', 82 => 'બ્યાસી', 83 => 'ત્યાસી', 84 => 'ચોર્યાસી', 85 => 'પંચાસી', 86 => 'છ્યાસી', 87 => 'સિત્યાસી', 88 => 'ઈઠ્યાસી', 89 => 'નેવ્યાસી', 90 => 'નેવું',
                    91 => 'એકાણું', 92 => 'બાણું', 93 => 'ત્રાણું', 94 => 'ચોરાણું', 95 => 'પંચાણું', 96 => 'છન્નું', 97 => 'સત્તાણું', 98 => 'અઠ્ઠાણું', 99 => 'નવાણું'
                ];
                $convert = function ($n) use ($words, &$convert) {
                    if ($n == 0) return '';
                    if ($n < 100) return $words[$n];
                    if ($n < 1000) return $words[floor($n / 100)] . ' સો ' . $convert($n % 100);
                    if ($n < 100000) return $words[floor($n / 1000)] . ' હજાર ' . $convert($n % 1000);
                    if ($n < 10000000) return $words[floor($n / 100000)] . ' લાખ ' . $convert($n % 100000);
                    return $words[floor($n / 10000000)] . ' કરોડ ' . $convert($n % 10000000);
                };
                $res = $convert($whole) . ' રૂપિયા ';
                if ($fraction > 0) {
                    $res .= 'અને ' . $words[$fraction] . ' પૈસા ';
                }
                return $res . 'પૂરા.';
            }
        }

        $logoPath = public_path('images/logo.png');
        $watermarkPath = public_path('images/watermark.png');
        $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
        $watermarkBase64 = file_exists($watermarkPath) ? base64_encode(file_get_contents($watermarkPath)) : '';
    @endphp

    <style>
        @font-face {
            font-family: 'NotoSansGujarati';
            src: url('{{ public_path('fonts/NotoSansGujarati-Regular.ttf') }}') format('truetype');
            font-weight: normal; font-style: normal;
        }
        @font-face {
            font-family: 'NotoSansGujarati';
            src: url('{{ public_path('fonts/NotoSansGujarati-Bold.ttf') }}') format('truetype');
            font-weight: bold; font-style: normal;
        }

        @page { size: a4; margin: 10mm; }

        .document-wrapper {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            color: #111827; background: #fff; margin: 0; padding: 0; width: 190mm; position: relative;
        }

        .gujarati { font-family: 'NotoSansGujarati', sans-serif; line-height: 1.4; }

        .document-wrapper.a4 { font-size: 11px; }

        .watermark-img {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            width: 70%; opacity: 0.06; z-index: 0;
        }

        .content-area { position: relative; z-index: 1; }

        .header-strip { height: 6px; background: #166534; margin-bottom: 5px; border-radius: 4px; }

        .header-table {
            width: 100%; border-bottom: 1px solid #d1d5db; padding-bottom: 5px; margin-bottom: 5px;
            border-collapse: collapse; table-layout: fixed;
        }
        .logo-cell { width: 15%; vertical-align: middle; }
        .brand-cell { width: 65%; text-align: center; vertical-align: middle; }
        .contact-cell { width: 20%; text-align: right; vertical-align: middle; font-size: 12px; font-weight: bold; color: #166534; }

        .company-name { font-size: 20px; font-weight: 900; color: #166534; text-transform: uppercase; margin: 0; white-space: nowrap; }
        .stamp-label {
            font-size: 10px; border: 2px solid #166534; color: #166534; border-radius: 12px;
            padding: 1px 6px; font-weight: bold; display: inline-block; margin-right: 5px; transform: rotate(-5deg);
        }
        .subtitle { font-size: 10px; color: #4b5563; font-weight: 600; margin: 0 0 2px 0; }
        .address-badge {
            font-size: 9px; background: #166534; color: white; padding: 2px 8px; border-radius: 4px;
            display: inline-block; font-weight: 600;
        }

        /* Items Table - Matching Preview Styling */
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .items-table th {
            background: #f3f4f6; padding: 8px 10px; border: 1px solid #d1d5db;
            font-size: 10px; text-transform: uppercase; font-weight: 800; color: #374151; text-align: left;
        }
        .items-table td { padding: 8px 10px; border: 1px solid #d1d5db; font-size: 12px; vertical-align: middle; }
        .col-sr { text-align: center; width: 30px; }
        .col-rate { text-align: right; font-weight: bold; width: 100px; }
        .item-name { font-weight: bold; color: #111827; font-size: 13px; }

        .grand-total-row { background: #f9fafb; font-weight: 900; color: #000; }
        .grand-total-label { padding: 10px; text-align: right; font-size: 15px; text-transform: uppercase; border: 2px solid #111827 !important; }
        .grand-total-val { padding: 10px; text-align: right; font-size: 18px; border: 2px solid #111827 !important; }

        .footer-table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .page-info-cell { vertical-align: bottom; font-size: 11px; color: #6b7280; font-weight: 800; }
        .signature-cell { width: 240px; text-align: center; vertical-align: bottom; }
        .sig-line { border-top: 2px solid #111827; margin: 30px 0 8px 0; }
        .sig-label { font-weight: 900; color: #111827; text-transform: uppercase; font-size: 12px; }

        .currency-sym { font-family: 'DejaVu Sans', sans-serif; font-weight: normal; }
        .clearfix::after { content: ""; clear: both; display: table; }
        .page-container { page-break-after: always; position: relative; width: 100%; }
        .page-container:last-child { page-break-after: avoid; }
    </style>

    <div class="document-wrapper a4">
        @if($watermarkBase64)
            <img src="data:image/png;base64,{{ $watermarkBase64 }}" class="watermark-img">
        @endif

        <div class="content-area">
            @php
                $all_items = $offer->items;
                $pages = [];
                $current_page_items = [];
                $page_index = 0;
                $limit_first = 18;
                $limit_other = 22;

                foreach ($all_items as $idx => $it) {
                    $current_page_items[] = $it;
                    if (count($current_page_items) >= ($page_index == 0 ? $limit_first : $limit_other) && ($idx + 1) < count($all_items)) {
                        $pages[] = $current_page_items;
                        $current_page_items = [];
                        $page_index++;
                    }
                }
                if (!empty($current_page_items) || empty($pages)) $pages[] = $current_page_items;
                $page_count = count($pages);
            @endphp

            @foreach($pages as $p_idx => $p_items)
                <div class="page-container" style="{{ $page_count > 1 ? 'min-height: 255mm;' : '' }}">
                    
                    <!-- KEEP HEADER (INVOICE STYLE) -->
                    @if($p_idx == 0)
                        <div class="header-strip"></div>
                        <table class="header-table">
                            <tr>
                                <td class="logo-cell">
                                    @if($logoBase64) <img src="data:image/png;base64,{{ $logoBase64 }}" style="height: 75px; width: auto;"> @endif
                                </td>
                                <td class="brand-cell">
                                    <h1 class="company-name"><span class="stamp-label">NEW</span> VRUNDAVAN NURSERY</h1>
                                    <p class="subtitle">Retailer & Wholesaler of All Fruit, Flower & Ornamental Plants</p>
                                    <div class="address-badge">Gadu - Chorvad Circle, Porbandar Highway, Gadu (Sherbaug) Dist: Junagadh, Gujarat 362255</div>
                                </td>
                                <td class="contact-cell">6355151302<br>9925575862</td>
                            </tr>
                        </table>

                        <div style="margin-bottom: 20px;">
                            <table width="100%" style="font-size: 13px;">
                                <tr>
                                    <td>
                                        <p><strong>To,</strong><br>{{ $offer->customer_name }}</p>
                                        @if($offer->address)<p><strong>Address:</strong> {{ $offer->address }}</p>@endif
                                        @if($offer->phone)<p><strong>Contact:</strong> {{ $offer->phone }}</p>@endif
                                    </td>
                                    <td style="text-align: right; vertical-align: top;">
                                        <p><strong>Offer No:</strong> #{{ $offer->offer_no }}</p>
                                        <p><strong>Date:</strong> {{ $offer->created_at->format('d M, Y') }}</p>
                                    </td>
                                </tr>
                            </table>
                            <div style="margin-top: 15px;">
                                <p><strong>Subject:</strong> {{ $offer->subject ?: 'Plant Supply Offer' }}</p>
                                <p style="margin-top: 10px;"><strong>{{ $offer->greeting ?: 'Dear Sir/Madam,' }}</strong></p>
                                <p>{{ $offer->intro_text ?: 'As per your requirement, we are pleased to offer the following plants for your consideration.' }}</p>
                            </div>
                        </div>
                    @else
                        <div class="header-strip" style="height: 4px; background: #d1d5db; margin-bottom: 10px;"></div>
                        <table width="100%" style="margin-bottom: 15px; font-size: 12px; font-weight: 800; color: #6b7280; border-bottom: 1px solid #d1d5db; padding-bottom: 5px;">
                            <tr><td>OFFER: #{{ $offer->offer_no }}</td><td style="text-align: right;">PAGE {{ $p_idx + 1 }} OF {{ $page_count }}</td></tr>
                        </table>
                    @endif

                    <table class="items-table">
                        <thead>
                            <tr>
                                <th class="col-sr">Sr</th>
                                <th>Description of Plants</th>
                                @if($offer->show_type)<th style="text-align: center;">Type</th>@endif
                                @if($offer->show_size)<th style="text-align: center;">Size (Ft)</th>@endif
                                @if($offer->show_bag)<th style="text-align: center;">Bag (In)</th>@endif
                                <th class="col-rate">Rate (<span class="currency-sym">&#8377;</span>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($p_items as $idx => $item)
                                @php
                                    $display_idx = 0;
                                    for ($i = 0; $i < $p_idx; $i++) $display_idx += count($pages[$i]);
                                    $display_idx += $idx + 1;
                                @endphp
                                <tr>
                                    <td class="col-sr">{{ $display_idx }}</td>
                                    <td><span class="item-name">{{ $item->plant_name }}</span></td>
                                    @if($offer->show_type)<td style="text-align: center;">{{ $item->type_of_plant ?: '-' }}</td>@endif
                                    @if($offer->show_size)<td style="text-align: center;">{{ $item->plant_size_feet ?: '-' }}</td>@endif
                                    @if($offer->show_bag)<td style="text-align: center;">{{ $item->bag_size_inches ?: '-' }}</td>@endif
                                    <td class="col-rate">{{ number_format($item->rate, 2) }}</td>
                                </tr>
                            @endforeach

                            @if($p_idx == $page_count - 1 && $offer->show_total)
                                @php $col_count = 2 + ($offer->show_type?1:0) + ($offer->show_size?1:0) + ($offer->show_bag?1:0) + 1; @endphp
                                <tr class="grand-total-row">
                                    <td colspan="{{ $col_count - 1 }}" class="grand-total-label">Grand Total</td>
                                    <td class="grand-total-val"><span class="currency-sym">&#8377;</span>{{ number_format($offer->total, 2) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    @if($p_idx == $page_count - 1)
                        <div style="margin-top: 15px;">
                            <div class="amount-in-words gujarati" style="text-align: left; margin-bottom: 10px;">શબ્દોમાં: {{ gujaratiAmountInWordsUnified($offer->total) }}</div>
                            
                            @if($offer->terms)
                                <div style="margin-top: 15px;">
                                    <strong style="color: #374151; text-transform: uppercase; font-size: 10px; border-bottom: 1px solid #eee; display: block; padding-bottom: 2px;">Terms & Conditions:</strong>
                                    <div style="font-size: 11px; color: #4b5563; margin-top: 5px;">{!! nl2br(e($offer->terms)) !!}</div>
                                </div>
                            @endif
                            <p style="font-style: italic; color: #6b7280; font-size: 11px; margin-top: 15px;">We hope this offer meets your requirements. Looking forward to your confirmation.</p>
                        </div>

                        <table class="footer-table">
                            <tr>
                                <td class="page-info-cell">PAGE {{ $p_idx + 1 }} OF {{ $page_count }}</td>
                                <td class="signature-cell">
                                    <div style="font-size: 14px; font-weight: 900; color: #111827;">For, New Vrundavan Nursery</div>
                                    <div style="font-size: 11px; color: #DBDBDB; margin: 15px 0 4px 0; font-weight: 700;" class="gujarati">(Authorized Signature)</div>
                                    <div class="sig-line" style="margin: 15px 0 5px 0;"></div>
                                    <div class="sig-label">Authorized Signatory</div>
                                </td>
                            </tr>
                        </table>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
