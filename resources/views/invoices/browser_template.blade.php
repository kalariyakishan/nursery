@php
if (!function_exists('gujaratiAmountInWords')) {
    function gujaratiAmountInWords($number) {
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

        $convert = function($n) use ($words, &$convert) {
            if ($n == 0) return '';
            if ($n < 100) return $words[$n];
            if ($n < 1000) return $words[floor($n/100)] . ' સો ' . $convert($n % 100);
            if ($n < 100000) return $words[floor($n/1000)] . ' હજાર ' . $convert($n % 1000);
            if ($n < 10000000) return $words[floor($n/100000)] . ' લાખ ' . $convert($n % 100000);
            return $words[floor($n/10000000)] . ' કરોડ ' . $convert($n % 10000000);
        };

        $res = $convert($whole) . ' રૂપિયા ';
        if ($fraction > 0) {
            $res .= 'અને ' . $words[$fraction] . ' પૈસા ';
        }
        return $res . 'પૂરા.';
    }
}
@endphp

<style>
    /* Professional Browser Print & Preview CSS */
    :root {
        --theme-color: #166534; /* Dark Green */
        --theme-light: rgba(22, 101, 52, 0.05);
        --border-color: #166534;
        --text-color: #000000;
        --muted-color: #4b5563;
    }

    .browser-invoice-wrapper {
        font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text-color);
        background: white;
        margin: 0 auto;
        box-sizing: border-box;
        position: relative;
        /* Default to A4 width for screen preview */
        width: 210mm;
        min-height: 297mm;
        padding: 5mm 10mm;
        font-size: 11pt;
        line-height: 1.5;
    }

    .browser-invoice-wrapper.a5 {
        width: 148mm;
        min-height: 210mm;
        padding: 5mm;
        font-size: 9pt;
    }

    .browser-invoice-wrapper.letter {
        width: 215.9mm;
        min-height: 279.4mm;
        padding: 10mm;
        font-size: 11pt;
    }

    .b-invoice-border {
        border: 2px solid var(--border-color);
        border-radius: 4px;
        padding: 15px;
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* Header Section */
    .b-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 2px solid var(--border-color);
        padding-bottom: 12px;
        margin-bottom: 12px;
    }

    .b-logo img {
        height: 80px;
        width: auto;
        object-fit: contain;
    }

    .b-company-info {
        text-align: center;
        flex-grow: 1;
        padding: 0 15px;
    }

    .b-company-title {
        font-family: 'Times New Roman', serif;
        font-size: 24pt;
        font-weight: 900;
        color: var(--theme-color);
        margin: 0 0 5px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .b-company-subtitle {
        font-size: 10.5pt;
        font-weight: 700;
        text-transform: uppercase;
        margin: 0;
        color: var(--muted-color);
    }

    .b-contact-info {
        text-align: right;
        font-size: 10.5pt;
        font-weight: 900;
        color: var(--theme-color);
        white-space: nowrap;
    }

    .b-company-address {
        background: var(--theme-color);
        color: white;
        padding: 5px 15px;
        font-size: 10.5pt;
        font-weight: bold;
        border-radius: 4px;
        text-align: center;
        margin-bottom: 15px;
    }

    /* Meta Details (Bill No, Date, Customer) */
    .b-meta-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .b-customer {
        flex: 1;
    }
    
    .b-customer-label {
        font-style: italic;
        font-weight: bold;
        margin-right: 5px;
    }

    .b-customer-name {
        font-size: 14pt;
        font-weight: bold;
        border-bottom: 1px dotted var(--border-color);
        display: inline-block;
        min-width: 250px;
    }

    .b-invoice-details {
        text-align: right;
        font-size: 11pt;
        font-weight: bold;
    }

    .b-invoice-details div {
        margin-bottom: 4px;
    }

    /* Table Section using Native CSS Pagination */
    .b-table-wrapper {
        flex-grow: 1;
    }

    .b-items-table {
        width: 100%;
        border-collapse: collapse;
        border: 2px solid var(--border-color);
    }

    /* This allows the table header to repeat on new pages automatically in print */
    .b-items-table thead {
        display: table-header-group;
    }
    /* This allows the table footer to repeat or stick to the bottom of the table */
    .b-items-table tfoot {
        display: table-footer-group;
    }
    /* Prevent rows from breaking cleanly across pages */
    .b-items-table tr {
        page-break-inside: avoid;
    }

    .b-items-table th {
        background: var(--theme-light);
        color: #000;
        padding: 8px 10px;
        font-size: 10.5pt;
        font-weight: 800;
        text-transform: uppercase;
        border: 1px solid var(--border-color);
        border-bottom: 2px solid var(--border-color);
    }

    .b-items-table td {
        padding: 8px 10px;
        border: 1px solid var(--border-color);
        font-size: 11pt;
        vertical-align: top;
    }
    
    .b-items-table td.b-col-rate, .b-items-table td.b-col-qty, .b-items-table td.b-col-amount {
        text-align: right;
        font-weight: bold;
    }

    .b-items-table td.b-col-sr {
        text-align: center;
        font-weight: bold;
        color: var(--muted-color);
    }

    .b-item-name {
        font-size: 1.1em;
        font-weight: bold;
        color: #000;
    }
    .b-item-desc {
        font-size: 0.85em;
        color: var(--muted-color);
        font-style: italic;
    }

    /* Footers and Totals */
    .b-tfoot-row td {
        padding: 6px 10px;
        font-size: 11pt;
        font-weight: bold;
    }
    
    .b-total-label {
        text-align: right;
        font-style: italic;
        text-transform: uppercase;
        border-right: 1px solid var(--border-color);
    }
    
    .b-total-val {
        text-align: right;
    }

    .b-grand-total {
        background: var(--theme-light);
        font-size: 14pt;
        font-weight: 900;
    }

    .b-grand-total td {
        padding: 10px;
        border-top: 2px solid var(--border-color);
    }

    /* Bottom Section */
    .b-bottom-section {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 10px;
        page-break-inside: avoid;
    }

    .b-notes-bank {
        width: 60%;
    }

    .b-box {
        border: 2px solid var(--border-color);
        border-radius: 6px;
        padding: 10px;
        margin-bottom: 10px;
        background: var(--theme-light);
    }

    .b-box-title {
        font-size: 10pt;
        font-weight: 900;
        text-decoration: underline;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .b-box-content {
        font-size: 10pt;
        font-weight: bold;
        line-height: 1.4;
    }

    .b-signature {
        width: 35%;
        text-align: right;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }

    .b-signature-company {
        font-weight: bold;
        font-style: italic;
        margin-bottom: 40px;
        color: var(--muted-color);
    }

    .b-signature-line {
        border-top: 1px solid var(--border-color);
        margin-bottom: 5px;
    }

    .b-signature-text {
        font-size: 10.5pt;
        font-weight: bold;
        text-transform: uppercase;
    }

    .new-label-stamp {
        display: inline-block;
        border: 2px solid var(--theme-color);
        border-radius: 50%;
        padding: 3px 12px;
        color: var(--theme-color);
        font-size: 0.4em;
        vertical-align: middle;
        transform: rotate(-12deg);
        margin-right: 6px;
        font-weight: 900;
        font-family: 'Times New Roman', serif;
        letter-spacing: 1.5px;
        line-height: 1;
        opacity: 0.9;
        position: relative;
        top: -6px;
    }

    .b-watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 70%;
        opacity: 0.1;
        z-index: 0;
        pointer-events: none;
    }

    @media print {
        .browser-invoice-wrapper {
            width: 100% !important;
            min-height: auto !important;
            padding: 0 !important;
            box-shadow: none !important;
            margin: 0 !important;
            border: none !important;
        }
        
        .b-invoice-border {
            border: none;
            padding: 0;
        }

        /* Essential for repeating headers on multiple pages */
        .b-items-table {
            page-break-inside: auto;
        }
        
        /* The header row repeating */
        .b-items-table thead {
            display: table-header-group;
        }
        
        .b-items-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        /* Ensure water marks print nicely */
        .b-watermark {
            display: none; /* Often watermarks interfere with multi-page prints in browsers, handle with caution or hide */
        }
    }
</style>

<div class="browser-invoice-wrapper" :class="paperSize">
    <div class="b-invoice-border">
        
        <!-- Watermark (Screen only, usually hidden in print to avoid overlap bugs on multipage) -->
        <img src="{{ asset('images/watermark.png') }}" class="b-watermark" alt="Watermark">

        <!-- Business Header -->
        <div class="b-header">
            <div class="b-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo">
            </div>
            <div class="b-company-info">
                <h1 class="b-company-title">
                    <span class="new-label-stamp">NEW</span> VRUNDAVAN NURSERY
                </h1>
                <p class="b-company-subtitle">Retailer & Wholesaler of All Fruit, Flower & Ornamental Plants</p>
            </div>
            <div class="b-contact-info">
                Mo. 6355151302<br>
                9925575862
            </div>
        </div>

        <div class="b-company-address">
            Gadu - Chorvad Circle, Porbandar Highway, Gadu (Sherbaug)-362255, Dist : Junagadh
        </div>

        <!-- Meta Details -->
        <div class="b-meta-container">
            <div class="b-customer">
                <span class="b-customer-label">M/s.</span>
                <div class="b-customer-name">
                    {{ $invoice->customer_name }} 
                    @if($invoice->phone) ({{ $invoice->phone }}) @endif
                </div>
            </div>
            <div class="b-invoice-details">
                <div>Bill No: <span style="color: black;">#{{ $invoice->invoice_no }}</span></div>
                <div>Date: <span style="color: black;">{{ $invoice->created_at->format('d/m/Y') }}</span></div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="b-table-wrapper">
            <table class="b-items-table relative z-10 w-full bg-transparent">
                <thead>
                    <tr>
                        <th width="8%">Sr.No.</th>
                        <th width="48%" style="text-align: left;">Particulars</th>
                        <th width="12%" style="text-align: right;">Qty</th>
                        <th width="15%" style="text-align: right;">Rate</th>
                        <th width="17%" style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $index => $item)
                        <tr>
                            <td class="b-col-sr">{{ $index + 1 }}</td>
                            <td>
                                <div class="b-item-name">{{ $item->product_name }}</div>
                                @php
                                    $details = [];
                                    if($item->height && $item->height !== '-') $details[] = $item->height;
                                    if($item->bag_size && $item->bag_size !== '-') $details[] = $item->bag_size;
                                @endphp
                                @if(count($details) > 0)
                                    <div class="b-item-desc">({{ implode(', ', $details) }})</div>
                                @endif
                            </td>
                            <td class="b-col-qty">{{ $item->quantity }}</td>
                            <td class="b-col-rate">₹{{ number_format($item->price, 2) }}</td>
                            <td class="b-col-amount">₹{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @php
                        $afterDiscount = $invoice->subtotal - $invoice->discount;
                    @endphp

                    @if($invoice->discount > 0 || $invoice->gst_amount > 0)
                        <tr class="b-tfoot-row">
                            <td colspan="4" class="b-total-label" style="opacity: 0.7;">Subtotal</td>
                            <td class="b-total-val">₹{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        @if($invoice->discount > 0)
                        <tr class="b-tfoot-row">
                            <td colspan="4" class="b-total-label" style="color: #bc1818;">Discount</td>
                            <td class="b-total-val" style="color: #bc1818;">- ₹{{ number_format($invoice->discount, 2) }}</td>
                        </tr>
                        @endif
                        
                        @if($invoice->gst_amount > 0)
                            @if($invoice->gst_type === 'exclusive')
                                <tr class="b-tfoot-row">
                                    <td colspan="4" class="b-total-label" style="opacity: 0.7;">Taxable Amount</td>
                                    <td class="b-total-val">₹{{ number_format($afterDiscount, 2) }}</td>
                                </tr>
                            @endif
                            <tr class="b-tfoot-row">
                                <td colspan="4" class="b-total-label" style="opacity: 0.7;">CGST ({{ number_format($invoice->gst_percentage / 2, 1) }}%)</td>
                                <td class="b-total-val">₹{{ number_format($invoice->cgst, 2) }}</td>
                            </tr>
                            <tr class="b-tfoot-row">
                                <td colspan="4" class="b-total-label" style="opacity: 0.7;">SGST ({{ number_format($invoice->gst_percentage / 2, 1) }}%)</td>
                                <td class="b-total-val">₹{{ number_format($invoice->sgst, 2) }}</td>
                            </tr>
                        @endif
                    @endif
                    
                    <tr class="b-tfoot-row b-grand-total">
                        <td colspan="4" class="b-total-label font-black text-lg py-3" style="border-right: none;">Grand Total</td>
                        <td class="b-total-val font-black text-xl py-3" style="border-left: 1px solid var(--border-color);">₹{{ number_format($invoice->total, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5" style="border-top: none; text-align: left; padding: 10px; font-weight: bold; font-style: italic;">
                            શબ્દોમાં: <span style="font-weight: normal; color: var(--muted-color);">{{ gujaratiAmountInWords($invoice->total) }}</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Bottom Section -->
        <div class="b-bottom-section">
            <div class="b-notes-bank">
                @if($invoice->notes)
                    <div class="b-box">
                        <div class="b-box-title">Extra Notes:</div>
                        <div class="b-box-content">{{ $invoice->notes }}</div>
                    </div>
                @endif
                <div class="b-box">
                    <div class="b-box-title">Bank Details:</div>
                    <div class="b-box-content">
                        <div>State Bank of India - Chorwad</div>
                        <div style="font-size: 11pt; font-weight: 900; color: var(--theme-color);">New Vrundavan Nursery</div>
                        <div style="opacity: 0.8; font-family: monospace;">A/c. No. 42910441064</div>
                        <div style="opacity: 0.8; font-family: monospace;">IFSC Code No. SBIN0060168</div>
                    </div>
                </div>
            </div>

            <div class="b-signature">
                <div class="b-signature-company">For, New Vrundavan Nursery</div>
                <div class="b-signature-line"></div>
                <div class="b-signature-text">Authorized Signature</div>
            </div>
        </div>

    </div>
</div>
