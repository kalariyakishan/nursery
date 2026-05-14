<!DOCTYPE html>
<html lang="gu">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Invoice - {{ $invoice->invoice_no }}</title>
</head>

<body>
    @php
        $isPdf = true;
        function gujaratiAmountInWordsUnified($number)
        {
            if ($number == 0)
                return 'શૂન્ય';
            $number = round($number, 2);
            $whole = floor($number);
            $fraction = round(($number - $whole) * 100);
            $words = [
                0 => '',
                1 => 'એક',
                2 => 'બે',
                3 => 'ત્રણ',
                4 => 'ચાર',
                5 => 'પાંચ',
                6 => 'છ',
                7 => 'સાત',
                8 => 'આઠ',
                9 => 'નવ',
                10 => 'દસ',
                11 => 'અગિયાર',
                12 => 'બાર',
                13 => 'તેર',
                14 => 'ચૌદ',
                15 => 'પંદર',
                16 => 'સોળ',
                17 => 'સત્તર',
                18 => 'અઢાર',
                19 => 'ઓગણીસ',
                20 => 'વીસ',
                21 => 'એકવીસ',
                22 => 'બાવીસ',
                23 => 'તેવીસ',
                24 => 'ચોવીસ',
                25 => 'પચીસ',
                26 => 'છવ્વીસ',
                27 => 'સત્યાવીસ',
                28 => 'અઠ્ઠાવીસ',
                29 => 'ઓગણત્રીસ',
                30 => 'ત્રીસ',
                31 => 'એકત્રીસ',
                32 => 'બત્રીસ',
                33 => 'તેત્રીસ',
                34 => 'ચોત્રીસ',
                35 => 'પાંત્રીસ',
                36 => 'છત્રીસ',
                37 => 'સાડત્રીસ',
                38 => 'આડત્રીસ',
                39 => 'ઓગણચાળીસ',
                40 => 'ચાલીસ',
                41 => 'એકતાલીસ',
                42 => 'બેતાલીસ',
                43 => 'તેતાલીસ',
                44 => 'ચોતાલીસ',
                45 => 'પિસ્તતાલીસ',
                46 => 'છેતાલીસ',
                47 => 'સુડતાલીસ',
                48 => 'અડતાલીસ',
                49 => 'ઓગણપચાસ',
                50 => 'પચાસ',
                51 => 'એકાવન',
                52 => 'બાવન',
                53 => 'તેપન',
                54 => 'ચોપન',
                55 => 'પંચાવન',
                56 => 'છપ્પન',
                57 => 'સત્તાવન',
                58 => 'અઠ્ઠાવન',
                59 => 'ઓગણસાઠ',
                60 => 'સાઠ',
                61 => 'એકસાઠ',
                62 => 'બાસઠ',
                63 => 'ત્રેસઠ',
                64 => 'ચોસઠ',
                65 => 'પાસઠ',
                66 => 'છાસઠ',
                67 => 'સડસઠ',
                68 => 'અડસઠ',
                69 => 'ઓગણોસિત્તેર',
                70 => 'સિત્તેર',
                71 => 'એક્યોતેર',
                72 => 'બોતેર',
                73 => 'તોતેર',
                74 => 'ચોતેર',
                75 => 'પંચોતેર',
                76 => 'છોતેર',
                77 => 'સિત્યોતેર',
                78 => 'ઈઠ્યોતેર',
                79 => 'ઓગણાએંસી',
                80 => 'એંસી',
                81 => 'એક્યાસી',
                82 => 'બ્યાસી',
                83 => 'ત્યાસી',
                84 => 'ચોર્યાસી',
                85 => 'પંચાસી',
                86 => 'છ્યાસી',
                87 => 'સિત્યાસી',
                88 => 'ઈઠ્યાસી',
                89 => 'નેવ્યાસી',
                90 => 'નેવું',
                91 => 'એકાણું',
                92 => 'બાણું',
                93 => 'ત્રાણું',
                94 => 'ચોરાણું',
                95 => 'પંચાણું',
                96 => 'છન્નું',
                97 => 'સત્તાણું',
                98 => 'અઠ્ઠાણું',
                99 => 'નવાણું'
            ];
            $convert = function ($n) use ($words, &$convert) {
                if ($n == 0)
                    return '';
                if ($n < 100)
                    return $words[$n];
                if ($n < 1000)
                    return $words[floor($n / 100)] . ' સો ' . $convert($n % 100);
                if ($n < 100000)
                    return $words[floor($n / 1000)] . ' હજાર ' . $convert($n % 1000);
                if ($n < 10000000)
                    return $words[floor($n / 100000)] . ' લાખ ' . $convert($n % 100000);
                return $words[floor($n / 10000000)] . ' કરોડ ' . $convert($n % 10000000);
            };
            $res = $convert($whole) . ' રૂપિયા ';
            if ($fraction > 0) {
                $res .= 'અને ' . $words[$fraction] . ' પૈસા ';
            }
            return $res . 'પૂરા.';
        }

        $logoPath = public_path('images/logo.png');
        $watermarkPath = public_path('images/watermark.png');
        $logoBase64 = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
        $watermarkBase64 = file_exists($watermarkPath) ? base64_encode(file_get_contents($watermarkPath)) : '';

        // Load Gujarati fonts as base64 for reliability
        $regularB64Path = public_path('fonts/regular_b64.txt');
        $boldB64Path = public_path('fonts/bold_b64.txt');
        $regularB64 = file_exists($regularB64Path) ? file_get_contents($regularB64Path) : '';
        $boldB64 = file_exists($boldB64Path) ? file_get_contents($boldB64Path) : '';
    @endphp

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

        @page {
            size: a4;
            margin: 10mm;
        }

        .document-wrapper {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            color: #111827;
            background: #fff;
            margin: 0;
            padding: 0;
            width: 190mm;
            position: relative;
        }

        .gujarati {
            font-family: 'NotoSansGujarati', sans-serif;
            line-height: 1.4;
        }

        .document-wrapper * {
            /* Resetting box-sizing as it can cause issues in some DomPDF versions */
        }

        /* Paper Dimensions are now handled by @page */
        .document-wrapper.a4 {
            font-size: 11px;
        }

        .document-wrapper.a5 {
            font-size: 10px;
        }

        .document-wrapper.letter {
            font-size: 11px;
        }

        /* Theme Colors */
        :root {
            --brand-dark: #166534;
            --brand-light: #dcfce7;
            --border-color: #d1d5db;
            --text-muted: #4b5563;
            --bg-gray: #f9fafb;
        }

        /* Watermark */
        .watermark-img {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            opacity: 0.06;
            z-index: 0;
        }

        .content-area {
            position: relative;
            z-index: 1;
        }

        /* Header Strip */
        .header-strip {
            height: 6px;
            background: #166534;
            margin-bottom: 5px;
            border-radius: 4px;
        }

        /* Header Table */
        .header-table {
            width: 100%;
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 5px;
            margin-bottom: 5px;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .logo-cell {
            width: 15%;
            vertical-align: middle;
        }

        .brand-cell {
            width: 65%;
            text-align: center;
            vertical-align: middle;
        }

        .contact-cell {
            width: 20%;
            text-align: right;
            vertical-align: middle;
            font-size: 12px;
            font-weight: bold;
            color: #166534;
        }

        .company-name {
            font-size: 20px;
            font-weight: 900;
            color: #166534;
            text-transform: uppercase;
            margin: 0;
            white-space: nowrap;
        }

        .stamp-label {
            font-size: 10px;
            border: 2px solid #166534;
            color: #166534;
            border-radius: 12px;
            padding: 1px 6px;
            font-weight: bold;
            display: inline-block;
            margin-right: 5px;
            transform: rotate(-5deg);
        }

        .subtitle {
            font-size: 10px;
            color: #4b5563;
            font-weight: 600;
            margin: 0 0 2px 0;
        }

        .address-badge {
            font-size: 9px;
            background: #166534;
            color: white;
            padding: 2px 8px;
            border-radius: 4px;
            display: inline-block;
            font-weight: 600;
        }

        /* Meta Table */
        .meta-table {
            width: 100%;
            margin-bottom: 10px;
            border-collapse: collapse;
            table-layout: auto;
        }

        .bill-to-box {
            background: #f9fafb;
            padding: 8px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            vertical-align: top;
            width: 55%;
        }

        .inv-details-box {
            vertical-align: top;
            padding-left: 15px;
            width: 45%;
        }

        .detail-row {
            border-bottom: 1px dashed #d1d5db;
            padding: 6px 0;
            width: 100%;
        }

        .detail-label {
            font-weight: 700;
            color: #4b5563;
            font-size: 11px;
            text-transform: uppercase;
        }

        .detail-value {
            font-weight: 900;
            color: #111827;
            float: right;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: auto;
        }

        .items-table th {
            background: #f3f4f6;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            font-size: 10px;
            text-transform: uppercase;
            font-weight: 800;
            color: #374151;
            text-align: left;
        }

        .items-table td {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            font-size: 12px;
            vertical-align: top;
        }

        .col-sr {
            text-align: center;
        }

        .col-qty {
            text-align: center;
            font-weight: bold;
        }

        .col-rate {
            text-align: right;
        }

        .col-amt {
            text-align: right;
            font-weight: bold;
        }

        .item-name {
            font-weight: bold;
            color: #111827;
            font-size: 13px;
            word-wrap: break-word;
        }

        .item-meta {
            font-size: 11px;
            color: #4b5563;
            font-style: italic;
            margin-top: 2px;
        }

        /* Totals */
        .totals-area {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .notes-cell {
            width: 60%;
            vertical-align: top;
            font-size: 11px;
            font-style: italic;
            color: #4b5563;
        }

        .totals-cell {
            width: 40%;
            vertical-align: top;
        }

        .summary-row {
            padding: 6px 0;
            border-bottom: 1px dashed #e5e7eb;
            width: 100%;
        }

        .summary-label {
            font-weight: 700;
            color: #4b5563;
        }

        .summary-val {
            font-weight: 800;
            color: #111827;
            float: right;
        }

        .grand-total-row {
            background: #166534;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            margin-top: 8px;
            width: 100%;
        }

        .grand-total-label {
            font-size: 14px;
            font-weight: 900;
        }

        .grand-total-val {
            font-size: 18px;
            font-weight: 900;
            float: right;
        }

        .amount-in-words {
            text-align: right;
            margin-top: 10px;
            font-size: 11px;
            font-weight: 800;
            font-style: italic;
            color: #111827;
        }

        /* Footer */
        .footer-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .page-info-cell {
            vertical-align: bottom;
            font-size: 11px;
            color: #6b7280;
            font-weight: 800;
        }

        .signature-cell {
            width: 240px;
            text-align: center;
            vertical-align: bottom;
        }

        .sig-line {
            border-top: 2px solid #111827;
            margin: 30px 0 8px 0;
        }

        .sig-label {
            font-weight: 900;
            color: #111827;
            text-transform: uppercase;
            font-size: 12px;
        }

        /* Utilities */
        .currency-sym {
            font-family: 'DejaVu Sans', sans-serif;
            font-weight: normal;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        @page {
            margin: 10mm;
        }

        .page-container {
            page-break-after: always;
            position: relative;
            width: 100%;
        }

        .page-container:last-child {
            page-break-after: avoid;
        }
    </style>

    <div class="document-wrapper {{ $paperSize ?? 'a4' }}">
        @if($watermarkBase64)
            <img src="data:image/png;base64,{{ $watermarkBase64 }}" class="watermark-img">
        @endif

        <div class="content-area">
            @php
                $all_items = $invoice->items;
                $pages = [];
                $current_page_items = [];
                $page_index = 0;

                $limit_first = 21;
                $limit_other = 23;
                $limit_first_with_footer = 12;
                $limit_other_with_footer = 15;

                foreach ($all_items as $idx => $it) {
                    $current_page_items[] = $it;
                    $is_first = ($page_index == 0);
                    $max_no_footer = $is_first ? $limit_first : $limit_other;

                    if (count($current_page_items) == $max_no_footer && ($idx + 1) < count($all_items)) {
                        $pages[] = $current_page_items;
                        $current_page_items = [];
                        $page_index++;
                    }
                }

                $is_first = ($page_index == 0);
                $max_with_footer = $is_first ? $limit_first_with_footer : $limit_other_with_footer;

                if (count($current_page_items) > $max_with_footer) {
                    $pages[] = $current_page_items;
                    $pages[] = [];
                } else {
                    if (count($current_page_items) > 0 || count($pages) == 0) {
                        $pages[] = $current_page_items;
                    }
                }

                $running_total = 0;
                $page_count = count($pages);
            @endphp

            @foreach($pages as $p_idx => $p_items)
                <div class="page-container" style="{{ $page_count > 1 ? 'min-height: 255mm;' : '' }}">
                    @if($p_idx == 0)
                        <div class="header-strip"></div>
                        <table class="header-table">
                            <tr>
                                <td class="logo-cell">
                                    @if($logoBase64)
                                        <img src="data:image/png;base64,{{ $logoBase64 }}" style="height: 75px; width: auto;">
                                    @endif
                                </td>
                                <td class="brand-cell">
                                    <h1 class="company-name"><span class="stamp-label">NEW</span> VRUNDAVAN NURSERY</h1>
                                    <p class="subtitle">Retailer & Wholesaler of All Fruit, Flower & Ornamental Plants</p>
                                    <div class="address-badge">Gadu - Chorvad Circle, Porbandar Highway, Gadu (Sherbaug) Dist:
                                        Junagadh, Gujarat 362255</div>
                                </td>
                                <td class="contact-cell">
                                    6355151302<br>9925575862
                                </td>
                            </tr>
                        </table>

                        <table class="meta-table">
                            <tr>
                                <td class="bill-to-box">
                                    <div
                                        style="font-weight: 800; color: #4b5563; font-size: 10px; margin-bottom: 5px; text-transform: uppercase;">
                                        Bill To:</div>
                                    <div style="font-size: 18px; font-weight: 900; color: #111827;">
                                        {{ $invoice->customer_name }}</div>
                                    @if($invoice->phone)
                                        <div style="font-size: 13px; color: #4b5563; font-weight: 700; margin-top: 2px;">Mobile: +91
                                            {{ $invoice->phone }}</div>
                                    @endif
                                </td>
                                <td class="inv-details-box">
                                    <div class="detail-row clearfix">
                                        <span class="detail-label">Invoice No:</span>
                                        <span class="detail-value">#{{ $invoice->invoice_no }}</span>
                                    </div>
                                    <div class="detail-row clearfix">
                                        <span class="detail-label">Date:</span>
                                        <span class="detail-value">{{ $invoice->created_at->format('d M, Y') }}</span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    @else
                        <div class="header-strip" style="height: 4px; background: #d1d5db; margin-bottom: 10px;"></div>
                        <table width="100%"
                            style="margin-bottom: 15px; font-size: 12px; font-weight: 800; color: #6b7280; border-bottom: 1px solid #d1d5db; padding-bottom: 5px;">
                            <tr>
                                <td>INVOICE: #{{ $invoice->invoice_no }}</td>
                                <td style="text-align: right;">PAGE {{ $p_idx + 1 }} OF {{ $page_count }}</td>
                            </tr>
                        </table>
                    @endif

                    <table class="items-table">
                        <thead>
                            <tr>
                                <th class="col-sr">Sr.</th>
                                <th>Particulars / Description</th>
                                <th class="col-qty">QTY</th>
                                <th class="col-rate">RATE</th>
                                <th class="col-amt">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($p_idx > 0)
                                <tr style="background: #f8fafc; font-style: italic; font-weight: bold; color: #166534;">
                                    <td colspan="4" style="text-align: right; padding-right: 15px;">Brought Forward (B/F) &rarr;
                                    </td>
                                    <td class="col-amt"><span class="currency-sym">&#8377;</span>
                                        {{ number_format($running_total, 2) }}</td>
                                </tr>
                            @endif

                            @foreach($p_items as $idx => $item)
                                @php
                                    $item_total = $item->total;
                                    $running_total += $item_total;
                                    $display_idx = 0;
                                    for ($i = 0; $i < $p_idx; $i++)
                                        $display_idx += count($pages[$i]);
                                    $display_idx += $idx + 1;
                                @endphp
                                <tr>
                                    <td class="col-sr">{{ $display_idx }}</td>
                                    <td>
                                        <span class="item-name">{{ $item->product_name }}</span>
                                        @php $details = []; @endphp
                                        @if($item->height && $item->height !== '-')
                                        @php $details[] = 'H: ' . $item->height; @endphp @endif
                                        @if($item->bag_size && $item->bag_size !== '-')
                                        @php $details[] = 'Bag: ' . $item->bag_size; @endphp @endif
                                        @if(count($details) > 0)
                                            <span class="item-meta"> - {{ implode(' | ', $details) }}</span>
                                        @endif
                                    </td>
                                    <td class="col-qty">{{ $item->quantity }}</td>
                                    <td class="col-rate"><span class="currency-sym">&#8377;</span>
                                        {{ number_format($item->price, 2) }}</td>
                                    <td class="col-amt"><span class="currency-sym">&#8377;</span>
                                        {{ number_format($item_total, 2) }}</td>
                                </tr>
                            @endforeach

                            @php $is_last = ($p_idx == $page_count - 1); @endphp
                            @if(!$is_last)
                                <tr style="background: #f8fafc; font-style: italic; font-weight: bold; color: #166534;">
                                    <td colspan="4" style="text-align: right; padding-right: 15px;">Carried Forward (C/F) &rarr;
                                    </td>
                                    <td class="col-amt"><span class="currency-sym">&#8377;</span>
                                        {{ number_format($running_total, 2) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>

                    @if($is_last)
                        <table class="totals-area">
                            <tr>
                                <td class="notes-cell">
                                    @if($invoice->notes)
                                        <div style="margin-top: 10px; padding-right: 20px;">
                                            <strong
                                                style="color: #374151; text-transform: uppercase; font-size: 10px;">Note:</strong><br>
                                            {{ $invoice->notes }}
                                        </div>
                                    @endif
                                </td>
                                <td class="totals-cell">
                                    <div class="summary-row clearfix">
                                        <span class="summary-label">Subtotal</span>
                                        <span class="summary-val"><span class="currency-sym">&#8377;</span>
                                            {{ number_format($invoice->subtotal, 2) }}</span>
                                    </div>
                                    @if($invoice->discount > 0)
                                        <div class="summary-row clearfix"
                                            style="color: #dc2626; background: #fef2f2; padding: 4px 6px; margin: 2px -6px; border-radius: 4px;">
                                            <span class="summary-label" style="color: #dc2626;">Discount</span>
                                            <span class="summary-val">- <span class="currency-sym">&#8377;</span>
                                                {{ number_format($invoice->discount, 2) }}</span>
                                        </div>
                                    @endif
                                    @if($invoice->gst_amount > 0)
                                        <div class="summary-row clearfix">
                                            <span class="summary-label">CGST</span>
                                            <span class="summary-val"><span class="currency-sym">&#8377;</span>
                                                {{ number_format($invoice->cgst, 2) }}</span>
                                        </div>
                                        <div class="summary-row clearfix">
                                            <span class="summary-label">SGST</span>
                                            <span class="summary-val"><span class="currency-sym">&#8377;</span>
                                                {{ number_format($invoice->sgst, 2) }}</span>
                                        </div>
                                    @endif
                                    <div class="grand-total-row clearfix">
                                        <span class="grand-total-label">Grand Total</span>
                                        <span class="grand-total-val"><span class="currency-sym">&#8377;</span>
                                            {{ number_format($invoice->total, 2) }}</span>
                                    </div>
                                    <div class="amount-in-words gujarati" style="font-family: 'NotoSansGujarati', sans-serif !important;">
                                        શબ્દોમાં: {{ gujaratiAmountInWordsUnified($invoice->total) }}
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <table class="footer-table">
                            <tr>
                                <td class="page-info-cell">
                                    PAGE {{ $p_idx + 1 }} OF {{ $page_count }}
                                </td>
                                <td class="signature-cell">
                                    <div style="font-size: 14px; font-weight: 900; color: #111827;">For, New Vrundavan Nursery
                                    </div>
                                    <div style="font-size: 11px; color: #DBDBDB; margin: 15px 0 4px 0; font-weight: 700;"
                                        class="gujarati">(Authorized Signature)</div>
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