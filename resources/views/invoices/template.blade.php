@php
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
@endphp
<style>
    /* Base Reset for Print */
    .invoice-wrapper {
        font-family: 'Inter', 'Roboto', 'DejaVu Sans', sans-serif;
        background: #fff;
        margin: 0 auto;
        color: #000;
        box-sizing: border-box;
        position: relative;
    }
    
    .invoice-wrapper * {
        box-sizing: border-box;
    }

    /* Paper Sizes */
    .invoice-wrapper.a4 {
        width: 210mm;
        min-height: 297mm;
        padding: 12mm;
        font-size: 10.5pt;
        line-height: 1.35;
    }
    .invoice-wrapper.a5 {
        width: 148mm;
        min-height: 210mm;
        padding: 6mm;
        font-size: 8.5pt;
        line-height: 1.25;
    }

    .invoice-wrapper.letter {
        width: 215.9mm;
        min-height: 279.4mm;
        padding: 15mm;
        font-size: 11pt;
        line-height: 1.5;
    }

    /* Common layout elements */
    .invoice-border {
        border: 2px solid rgba(22, 101, 52, 0.2);
        padding: 10px;
        /* min-height removed to allow multiple pages */
        position: relative;
    }
    .a5 .invoice-border {
        min-height: calc(210mm - 12mm);
    }
    .company-title {
        font-family: 'Times New Roman', serif;
        font-size: 18pt;
        font-weight: 900;
        color: #166534;
        text-transform: uppercase;
        margin: 0 0 2px 0;
        letter-spacing: -0.5px;
    }
    .a5 .company-title { font-size: 15pt; }
    
    .new-label-stamp {
        display: inline-block;
        border: 2px solid #166534;
        border-radius: 50%;
        padding: 3px 12px;
        color: #166534;
        font-size: 0.5em;
        vertical-align: middle;
        transform: rotate(-12deg);
        -webkit-transform: rotate(-12deg);
        margin-right: 6px;
        font-weight: 900;
        font-family: 'Times New Roman', serif;
        letter-spacing: 1.5px;
        line-height: 1;
        opacity: 0.8;
        position: relative;
        top: -4px;
        box-shadow: 1px 1px 0px rgba(22, 101, 52, 0.1);
    }
    
    .company-subtitle {
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
        margin: 0;
        letter-spacing: 1px;
    }
    .a5 .company-subtitle { font-size: 8px; }
    .company-address {
        background: #166534;
        color: #fff;
        padding: 3px 10px;
        font-size: 10px;
        font-weight: bold;
        margin-top: 5px;
        display: block;
        width: 100%;
        border-radius: 4px;
        letter-spacing: 0.5px;
        text-align: center;
    }

    .bill-details-table {
        margin: 8px 0;
        font-size: 10.5pt;
        font-weight: bold;
        border-bottom: 1px solid rgba(22, 101, 52, 0.3);
        padding-bottom: 5px;
    }
    .a5 .bill-details-table { font-size: 9pt; }
    .bill-details-table .highlight { color: #000; margin-left: 5px; }
    .bill-details-table .dotted-line { border-bottom: 1px dotted rgba(22, 101, 52, 0.3); }

    .customer-table {
        margin-bottom: 8px;
        font-weight: bold;
    }
    .customer-name {
        border-bottom: 2px dotted rgba(22, 101, 52, 0.2);
        font-size: 12pt;
        color: #000;
        padding-bottom: 5px;
    }
    .a5 .customer-name { font-size: 10pt; }

    /* Items Table */
    .items-table-container {
        border: 2px solid rgba(22, 101, 52, 0.3);
        border-radius: 8px;
        margin-bottom: 8px;
    }
    .items-table {
        width: 100%;
        border-collapse: collapse;
        page-break-inside: auto; /* Allow table to break between pages */
    }
    .items-table th {
        background: rgba(22, 101, 52, 0.05);
        color: #000;
        padding: 6px 10px;
        font-size: 10pt;
        text-transform: uppercase;
        border-bottom: 2px solid rgba(22, 101, 52, 0.3);
        border-right: 1px solid rgba(22, 101, 52, 0.3);
    }
    
        .no-break { page-break-inside: avoid !important; }
    }
    
    .items-table th:last-child { border-right: none; }
    
    .items-table td {
        padding: 6px 10px;
        border-right: 1px solid rgba(22, 101, 52, 0.3);
        word-break: break-word;
        page-break-inside: avoid !important;
        font-size: 10.5pt;
        line-height: 1.3;
        vertical-align: top;
    }
    .items-table tr {
        page-break-inside: avoid !important;
        page-break-after: auto;
    }
    .a5 .items-table td { font-size: 8.5pt; padding: 4px 6px; }
    .items-table td:last-child { border-right: none; }

    .items-table tr.empty-row td {
        color: transparent;
        height: 20px;
    }
    .a5 .items-table tr.empty-row td { height: 18px; }
    .a5 .extra-row-a5 { display: none; }


    .tfoot-label {
        padding: 6px 15px;
        text-align: right;
        font-style: italic;
        opacity: 0.6;
        text-transform: uppercase;
        border-right: 1px solid rgba(22, 101, 52, 0.3);
        border-top: 2px solid rgba(22, 101, 52, 0.3);
        font-size: 9pt;
    }
    .a5 .tfoot-label { font-size: 7.5pt; padding: 4px 8px; }
    .tfoot-value {
        padding: 6px 15px;
        text-align: right;
        border-top: 2px solid rgba(22, 101, 52, 0.3);
        font-size: 10pt;
    }
    .a5 .tfoot-value { font-size: 8.5pt; padding: 4px 8px; }
    .highlight-red { color: #000; opacity: 1; border-top: 1px solid rgba(22, 101, 52, 0.1); }
    
    .total-row td {
        border-top: 2px solid rgba(22, 101, 52, 0.3);
        padding: 15px;
    }
    .total-label {
        text-align: right;
        text-transform: uppercase;
        font-style: italic;
        font-weight: 900;
        font-size: 14pt;
        border-right: 1px solid rgba(22, 101, 52, 0.3);
    }
    .a5 .total-label { font-size: 11pt; }
    .total-value {
        text-align: right;
        font-size: 16pt;
        color: #000;
        background: rgba(22, 101, 52, 0.05);
        font-weight: 900;
    }
    .a5 .total-value { font-size: 13pt; }
    
    /* Footer Elements */
    .footer-table { 
        margin-top: auto; 
        width: 100%;
    }
    .notes-box, .bank-box {
        border: 2px solid rgba(22, 101, 52, 0.3);
        padding: 8px 12px;
        border-radius: 8px;
        background: rgba(22, 101, 52, 0.02);
        margin-bottom: 5px;
        margin-top: 2px;
        font-size: 8.5pt;
    }
    .a5 .notes-box, .a5 .bank-box { font-size: 7pt; padding: 5px 8px; }
    .notes-header, .bank-header {
        font-size: 10px;
        font-weight: 900;
        text-decoration: underline;
        margin-bottom: 5px;
        text-transform: uppercase;
    }
    .signature-box {
        margin-top: 20px;
    }
    .signature-line {
        border-top: 1px solid rgba(22, 101, 52, 0.2);
        width: 200px;
        margin: 0 0 0 auto;
    }
    .signature-text {
        font-size: 10px;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: 5px;
        opacity: 0.4;
    }


    .watermark-box {
        position: absolute;
        top: 400px;
        left: 50%;
        transform: translateX(-50%);
        width: 70%;
        opacity: 0.25;
        z-index: -1;
        pointer-events: none;
    }
    .watermark-box img {
        width: 100%;
        pointer-events: none;
    }

    /* Note: Page-specific @media print logic is handled by the calling view (show.blade.php) 
       to ensure it works correctly with the dashboard layout. */
</style>

<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_text(260, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", 'helvetica', 10, array(0,0,0));
    }
</script>

<div class="invoice-wrapper {{ isset($isPdf) && $isPdf ? ($paperSize ?? 'a4') : '' }}" 
     @if(!isset($isPdf) || !$isPdf) :class="paperSize" @endif>

    <div class="invoice-border" style="position: relative; z-index: 1;">
        <div class="watermark-box">
            <img src="{{ public_path('images/watermark.png') }}" onerror="this.src='{{ asset('images/watermark.png') }}'" style="width: 100%;" alt="Watermark">
        </div>
        
        <!-- Header Section -->
        <table width="100%" cellpadding="0" cellspacing="0" style="border-bottom: 2px solid rgba(22, 101, 52, 0.2); padding-bottom: 12px; margin-bottom: 15px;">
            <tr>
                <td width="20%" style="vertical-align: middle; text-align: left;">
                    <img src="{{ public_path('images/logo.png') }}" onerror="this.src='{{ asset('images/logo.png') }}'" 
                         style="height: 70px; width: auto; object-fit: contain;" alt="Nursery Logo">
                </td>
                <td width="57%" style="text-align: center; vertical-align: middle;">
                    <h1 class="company-title" style="margin-bottom: 5px; white-space: nowrap;">
                        <span class="new-label-stamp">NEW</span> VRUNDAVAN NURSERY
                    </h1>
                    <p class="company-subtitle" style="font-size: 10px; opacity: 0.8;">Retailer & Wholesaler of All Fruit, Flower & Ornamental Plants</p>
                </td>

                <td width="20%" style="vertical-align: middle; text-align: right; white-space: nowrap;">
                    <div style="font-size: 10px; font-weight: 900; color: #166534; line-height: 1.4;">
                        Mo. 6355151302<br>
                        9925575862
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: center; padding-top: 10px;">
                    <div class="company-address">
                        Gadu - Chorvad Circle, Porbandar Highway, Gadu (Sherbaug)-362255, Dist : Junagadh
                    </div>
                </td>
            </tr>
        </table>


        <!-- Bill Details (Page 1 Only) -->
        <table class="bill-details-table" width="100%">
            <tr>
                <td width="30%">Bill No. <span class="highlight">#{{ $invoice->invoice_no }}</span></td>
                <td width="40%" class="dotted-line"></td>
                <td width="30%" style="text-align: right;">Date : <span style="color: #000;">{{ $invoice->created_at->format('d/m/Y') }}</span></td>
            </tr>
        </table>

        <!-- Customer (Page 1 Only) -->
        <table class="customer-table" width="100%">
            <tr>
                <td width="5%" style="font-style: italic;">M/s.</td>
                <td width="95%" class="customer-name">
                    {{ $invoice->customer_name }} @if($invoice->phone) ({{ $invoice->phone }}) @endif
                </td>
            </tr>
        </table>

        <!-- Items Table Container -->
        <div class="items-table-container">
            @php
                $all_items = $invoice->items;
                $first_page_limit = 20; // Safer limit
                $other_page_limit = 28; // Safer limit
                
                $pages = [];
                $current_page_items = [];
                $item_count = 0;
                $limit = $first_page_limit;
                
                foreach($all_items as $it) {
                    $current_page_items[] = $it;
                    $item_count++;
                    if ($item_count >= $limit) {
                        $pages[] = $current_page_items;
                        $current_page_items = [];
                        $item_count = 0;
                        $limit = $other_page_limit;
                    }
                }
                if (!empty($current_page_items)) {
                    $pages[] = $current_page_items;
                }
                
                $running_total = 0;
                $page_count = count($pages);
            @endphp

            @foreach($pages as $page_index => $p_items)
                <table class="items-table {{ ($page_index < $page_count - 1) ? 'page-break' : '' }}" width="100%" cellspacing="0" style="{{ $page_index > 0 ? 'margin-top: 25px;' : '' }}">
                    <thead>
                        <tr>
                            <th width="7%">Sr.No.</th>
                            <th width="49%" style="text-align: left;">Particulars</th>
                            <th width="10%" style="text-align: right;">Qty</th>
                            <th width="17%" style="text-align: right;">Rate</th>
                            <th width="17%" style="text-align: right;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($page_index > 0)
                            <tr style="background: rgba(0,0,0,0.02);">
                                <td colspan="4" style="text-align: right; font-weight: bold; font-style: italic; font-size: 9pt;">Brought Forward (B/F)</td>
                                <td style="text-align: right; font-weight: bold; font-size: 9pt;">₹{{ number_format($running_total, 2) }}</td>
                            </tr>
                        @endif

                        @foreach($p_items as $idx => $item)
                            @php
                                $item_display_index = 0;
                                for($i=0; $i<$page_index; $i++) $item_display_index += count($pages[$i]);
                                $item_display_index += $idx + 1;
                                $running_total += $item->total;
                            @endphp
                            <tr>
                                <td style="text-align: center; color: #64748b; font-weight: bold;">{{ $item_display_index }}</td>
                                <td>
                                    <strong style="font-size: 1.1em; color: #000;">{{ $item->product_name }}</strong>
                                    @php
                                        $details = [];
                                        if($item->height && $item->height !== '-') $details[] = $item->height;
                                        if($item->bag_size && $item->bag_size !== '-') $details[] = $item->bag_size;
                                    @endphp
                                    @if(count($details) > 0)
                                        <br>
                                        <span style="font-size: 0.85em; opacity: 0.7; color: #666; font-style: italic;">
                                            ({{ implode(', ', $details) }})
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align: right; font-weight: 900;">{{ $item->quantity }}</td>
                                <td style="text-align: right; font-weight: bold; white-space: nowrap !important;">₹{{ number_format($item->price, 2) }}</td>
                                <td style="text-align: right; font-weight: 900; white-space: nowrap !important;">₹{{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach

                        @if($page_index < $page_count - 1)
                            <tr style="background: rgba(0,0,0,0.02);">
                                <td colspan="4" style="text-align: right; font-weight: bold; font-style: italic; font-size: 9pt;">Carried Forward (C/F)</td>
                                <td style="text-align: right; font-weight: bold; font-size: 9pt;">₹{{ number_format($running_total, 2) }}</td>
                            </tr>
                        @else
                           {{-- Fill empty rows only on last page if needed or keep it clean --}}
                           @php $rowCount = count($p_items); @endphp
                           @for($i = $rowCount; $i < ($page_count == 1 ? 12 : 5); $i++)
                                <tr class="empty-row">
                                    <td style="text-align: center;">&nbsp;</td>
                                    <td></td><td></td><td></td><td></td>
                                </tr>
                           @endfor
                        @endif
                    </tbody>
                    
                        @if($page_index == $page_count - 1)
                            @php
                                $afterDiscount = $invoice->subtotal - $invoice->discount;
                            @endphp

                            @if($invoice->discount > 0 || $invoice->gst_amount > 0)
                                <tr class="no-break">
                                    <td colspan="4" class="tfoot-label">Subtotal</td>
                                    <td class="tfoot-value">₹{{ number_format($invoice->subtotal, 2) }}</td>
                                </tr>
                                @if($invoice->discount > 0)
                                <tr class="no-break">
                                    <td colspan="4" class="tfoot-label highlight-red">Discount</td>
                                    <td class="tfoot-value highlight-red">- ₹{{ number_format($invoice->discount, 2) }}</td>
                                </tr>
                                @endif
                                
                                @if($invoice->gst_amount > 0)
                                    @if($invoice->gst_type === 'exclusive')
                                        <tr class="no-break">
                                            <td colspan="4" class="tfoot-label">Taxable Amount</td>
                                            <td class="tfoot-value">₹{{ number_format($afterDiscount, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr class="no-break">
                                        <td colspan="4" class="tfoot-label">CGST ({{ number_format($invoice->gst_percentage / 2, 1) }}%)</td>
                                        <td class="tfoot-value">₹{{ number_format($invoice->cgst, 2) }}</td>
                                    </tr>
                                    <tr class="no-break">
                                        <td colspan="4" class="tfoot-label">SGST ({{ number_format($invoice->gst_percentage / 2, 1) }}%)</td>
                                        <td class="tfoot-value">₹{{ number_format($invoice->sgst, 2) }}</td>
                                    </tr>
                                @endif
                            @endif
                            <tr class="total-row no-break">
                                <td colspan="2" class="total-label" style="padding: 10px 15px; border-bottom: none;">Grand Total</td>
                                <td colspan="3" class="total-value" style="padding: 10px 15px; border-bottom: none;">₹{{ number_format($invoice->total, 2) }}</td>
                            </tr>
                            <tr class="no-break">
                                <td colspan="5" style="text-align: right; padding: 4px 15px 10px 15px; font-size: 10px; font-style: italic; color: #000; border-top: none;">
                                    <strong>શબ્દોમાં:</strong> {{ gujaratiAmountInWords($invoice->total) }}
                                    @if(!isset($isPdf) || !$isPdf)
                                        <div style="float: left; font-size: 10px; font-weight: bold; opacity: 0.6; font-style: normal;">Page {{ $page_index + 1 }} of {{ $page_count }}</div>
                                    @endif
                                </td>
                            </tr>
                        @else
                            <tr class="no-break">
                                <td colspan="5" style="text-align: left; padding: 5px 10px; font-size: 10px; font-weight: bold; opacity: 0.6; background: rgba(0,0,0,0.01);">
                                    Page {{ $page_index + 1 }} of {{ $page_count }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @endforeach
        </div>

        <!-- Bottom Section (Notes, Bank, Signature) -->
        <div class="no-break" style="margin-top: 20px; width: 100%;">
            <table class="footer-table" width="100%">
                <tr>
                    <td width="60%" style="vertical-align: bottom;">
                        @if($invoice->notes)
                            <div class="notes-box">
                                <div class="notes-header">Extra Notes:</div>
                                <div style="font-size: 11px; font-weight: bold;">{{ $invoice->notes }}</div>
                            </div>
                        @endif
                        <div class="bank-box" style="width: 80%;">
                            <div class="bank-header">Bank Details:</div>
                            <div style="font-size: 11px; font-weight: bold; line-height: 1.4;">
                                <div>State Bank of India - Chorwad</div>
                                <div>New Vrundavan Nursery</div>
                                <div style="opacity: 0.7;">A/c. No. 42910441064</div>
                                <div style="opacity: 0.7;">IFSC Code No. SBIN0060168</div>
                            </div>
                        </div>
                    </td>
                    <td width="40%" style="vertical-align: bottom; text-align: right;">
                        <div class="signature-box">
                            <div style="font-size: 11px; font-weight: bold; font-style: italic; opacity: 0.6; margin-bottom: 20px;">
                                For, New Vrundavan Nursery.
                            </div>
                            <div class="signature-line"></div>
                            <div class="signature-text">Authorized Signature</div>
                        </div>
                    </td>
                </tr>
            </table>
            

        </div>
    </div>
    
    {{-- Footer numbering removed from here as it is now inside the table loop --}}
</div>
