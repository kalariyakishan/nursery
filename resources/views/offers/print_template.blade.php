@php
function gujaratiAmountInWordsPrint($number) {
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
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Noto+Sans+Gujarati:wght@400;600;700&display=swap');

.print-wrapper {
    font-family: 'Inter', 'Noto Sans Gujarati', sans-serif;
    color: #111827;
    background: #fff;
    margin: 0 auto;
    position: relative;
    box-sizing: border-box;
}

.print-wrapper * {
    box-sizing: border-box;
}

.print-wrapper.a4 { width: 210mm; min-height: auto; padding: 12mm; font-size: 12px; line-height: 1.35; }

/* Theme Colors */
:root {
    --brand-dark: #166534; /* Green 800 */
    --brand-light: #dcfce7; /* Green 100 */
    --border-color: #d1d5db; /* Gray 300 */
    --text-muted: #4b5563; /* Gray 600 */
    --bg-gray: #f9fafb; /* Gray 50 */
}

/* Watermark */
.pt-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 60%;
    opacity: 0.08;
    z-index: 0;
    pointer-events: none;
}

.pt-content {
    position: relative;
    z-index: 1;
}

/* Header Strip */
.pt-strip {
    height: 6px;
    background: var(--brand-dark);
    margin-bottom: 14px;
    border-radius: 4px;
}

/* Header Grid */
.pt-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid var(--border-color);
    padding-bottom: 12px;
    margin-bottom: 12px;
}

.pt-header-left {
    display: flex;
    align-items: center;
    gap: 16px;
}

.pt-logo {
    height: 75px;
    object-fit: contain;
}

.pt-brand-info {
    display: flex;
    flex-direction: column;
}

.pt-company-name {
    font-size: 22px;
    font-weight: 900;
    color: var(--brand-dark);
    text-transform: uppercase;
    letter-spacing: -0.5px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.pt-stamp {
    font-size: 10px;
    border: 2px solid var(--brand-dark);
    color: var(--brand-dark);
    border-radius: 12px;
    padding: 2px 8px;
    transform: rotate(-5deg);
    font-weight: bold;
}

.pt-subtitle {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 600;
    margin: 0 0 6px 0;
}

.pt-address {
    font-size: 10.5px;
    background: var(--brand-dark);
    color: white;
    padding: 5px 12px;
    border-radius: 4px;
    display: inline-block;
    font-weight: 600;
    white-space: nowrap;
}

.pt-header-right {
    text-align: right;
    font-size: 13px;
    color: var(--text-muted);
}

.pt-contact {
    font-weight: 800;
    color: var(--brand-dark);
    font-size: 15px;
    margin-bottom: 4px;
}

/* Meta Section (Invoice Details & Customer) */
.pt-meta {
    display: flex;
    justify-content: space-between;
    margin-bottom: 16px;
    gap: 16px;
}

.pt-bill-to {
    flex: 1;
    background: var(--bg-gray);
    padding: 12px;
    border-radius: 8px;
    border: 1px solid var(--border-color);
}

.pt-customer-name {
    font-size: 16px;
    font-weight: 800;
    margin: 0 0 4px 0;
    color: #000;
}

.pt-invoice-details {
    width: 280px;
}

.pt-detail-row {
    display: flex;
    justify-content: space-between;
    padding: 6px 0;
    border-bottom: 1px dashed var(--border-color);
    font-size: 12px;
}

.pt-detail-row:last-child {
    border-bottom: none;
}

.pt-detail-label {
    color: var(--text-muted);
    font-weight: 600;
}

.pt-detail-value {
    font-weight: 800;
    color: #000;
}

.pt-intro-text {
    margin-bottom: 16px;
    font-size: 12.5px;
    color: #111827;
    line-height: 1.6;
}
.pt-intro-greeting {
    font-weight: 800;
    margin-bottom: 4px;
}

/* Table Style */
.pt-table-container {
    margin-bottom: 16px;
}

.pt-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.pt-table th, .pt-table td {
    padding: 6px 10px;
    border: 1px solid var(--border-color);
    line-height: 1.35;
}

.pt-table th {
    background: #f2f2f2;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 10px;
    color: #374151;
    border-bottom: 1px solid #94a3b8;
    letter-spacing: 0.05em;
}

.pt-table td {
    vertical-align: top;
    line-height: 1.5;
}

.pt-table tr.pt-bcf-row td {
    background: #f8fafc;
    font-style: italic;
    color: var(--text-muted);
    font-weight: 600;
    text-align: right;
    border-bottom: 2px dashed #cbd5e1;
}

.col-sr { width: 5%; text-align: center; }
.col-desc { width: 55%; text-align: left; }
.col-qty { width: 10%; text-align: center; }
.col-size { width: 15%; text-align: center; }
.col-rate { width: 15%; text-align: right; }

.pt-item-name {
    font-weight: 700;
    color: #111827;
    font-size: 13px;
}

.pt-item-meta {
    font-size: 11px;
    color: var(--text-muted);
    white-space: nowrap;
}

/* Totals Area */
.pt-totals-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    page-break-inside: avoid;
    margin-top: 12px;
}

.pt-notes-area {
    flex: 1;
}

.pt-terms-list {
    margin-top: 8px;
    padding-left: 20px;
    font-size: 12px;
    color: var(--text-muted);
}
.pt-terms-list li {
    margin-bottom: 4px;
}

.pt-totals-box {
    width: 280px;
    margin-left: auto;
}

.pt-total-row {
    display: flex;
    justify-content: space-between;
    padding: 6px 0;
    font-size: 13px;
    border-bottom: 1px dashed #e5e7eb;
}
.pt-total-label { color: var(--text-muted); font-weight: 500; }
.pt-total-val { font-weight: 700; color: #111827; }

.pt-grand-total {
    border-top: 2px solid #111827;
    border-bottom: 2px solid #111827;
    margin-top: 4px;
    padding: 8px 0 !important;
    background: transparent !important;
    color: #000 !important;
}
.pt-grand-total .pt-total-label { color: #000 !important; font-weight: 900 !important; font-size: 15px !important; }
.pt-grand-total .pt-total-val { font-weight: 900 !important; color: #000 !important; font-size: 18px !important; }

/* Footer Section */
.pt-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 15px;
    page-break-inside: avoid;
}

.pt-page-num {
    font-size: 13px;
    color: var(--text-muted);
    font-weight: 600;
}

.pt-signature {
    text-align: center;
    width: 260px;
}

.pt-sig-for {
    font-weight: 800;
    color: var(--text-muted);
    margin-bottom: 30px;
    font-size: 13px;
}

.pt-sig-line {
    border-top: 1px dashed var(--brand-dark);
    margin-bottom: 10px;
}

.pt-sig-text {
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    color: #000;
}

@media print {
    body { margin: 0; background: white !important; }
    .print-wrapper { 
        box-shadow: none !important; 
        border: none !important; 
        margin: 0 !important; 
        width: 100% !important; 
        padding: 5mm !important; 
    }
    @page { margin: 8mm; size: A4; }
    
    .pt-page {
        page-break-after: always;
        position: relative;
    }
    
    .pt-page:last-child {
        page-break-after: auto;
    }

    .pt-table {
        page-break-inside: auto;
    }

    .pt-table tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }

    .pt-totals-wrapper, .pt-words, .pt-footer {
        page-break-inside: avoid;
    }

    .pt-strip { margin-bottom: 10px !important; }
    .pt-header { padding-bottom: 8px !important; margin-bottom: 8px !important; }
    .pt-meta { margin-bottom: 12px !important; }
    .pt-table-container { margin-bottom: 8px !important; }
    .pt-totals-wrapper { margin-top: 10px !important; }
}

.currency-txt { 
    font-family: 'Inter', sans-serif; 
    white-space: nowrap; 
    font-variant-numeric: tabular-nums;
}
.amount { text-align: right; }
</style>

<div class="print-wrapper a4">
    <img src="{{ asset('images/watermark.png') }}" class="pt-watermark" alt="Watermark">
    
    <div class="pt-content">
        @php
            $all_items = $offer->items;
            $pages = [];
            $current_page_items = [];
            $page_index = 0;
            
            $limit_first_page = 22; 
            $limit_other_page = 26; 
            $limit_first_with_footer = 16;
            $limit_other_with_footer = 20;

            foreach($all_items as $idx => $it) {
                $current_page_items[] = $it;
                
                $is_first = ($page_index == 0);
                $max_no_footer = $is_first ? $limit_first_page : $limit_other_page;
                
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

        @foreach($pages as $page_index => $p_items)
            <div class="pt-page">
                
                @if($page_index == 0)
                <div class="pt-strip"></div>
                <div class="pt-header">
                    <div class="pt-header-left">
                        <img src="{{ asset('images/logo.png') }}" class="pt-logo" alt="Logo">
                        <div class="pt-brand-info">
                            <h1 class="pt-company-name">
                                <span class="pt-stamp">NEW</span> VRUNDAVAN NURSERY
                            </h1>
                            <p class="pt-subtitle">Retailer & Wholesaler of All Fruit, Flower & Ornamental Plants</p>
                            <div><span class="pt-address">Gadu - Chorvad Circle, Porbandar Highway, Gadu (Sherbaug) Dist: Junagadh, Gujarat 362255</span></div>
                        </div>
                    </div>
                    <div class="pt-header-right">
                        <div class="pt-contact">6355151302 <br> 9925575862</div>
                    </div>
                </div>

                <div class="pt-meta">
                    <div class="pt-bill-to">
                        <div style="margin-bottom: 4px;">
                            <span style="color: var(--text-muted); font-size: 11px; text-transform: uppercase; font-weight: 700;">Party:</span> 
                            <span style="font-size: 15px; font-weight: 800; color: #000; margin-left: 4px;">{{ $offer->customer_name ?: '---' }}</span>
                        </div>
                    </div>
                    <div class="pt-invoice-details">
                        <div class="pt-detail-row">
                            <span class="pt-detail-label">Ref No:</span>
                            <span class="pt-detail-value">{{ $offer->reference_no ?: '__________________' }}</span>
                        </div>
                        <div class="pt-detail-row">
                            <span class="pt-detail-label">Subject:</span>
                            <span class="pt-detail-value">{{ $offer->subject ?: 'Plant Supply Offer' }}</span>
                        </div>
                        <div class="pt-detail-row">
                            <span class="pt-detail-label">Date:</span>
                            <span class="pt-detail-value">{{ $offer->created_at->format('d M, Y') }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-intro-text">
                    <div class="pt-intro-greeting">{{ $offer->greeting ?: 'Dear Sir/Madam,' }}</div>
                    <div>{{ $offer->intro_text ?: 'As per your requirement, we are pleased to offer the following plants for your consideration.' }}</div>
                </div>
                @endif
                
                @if($page_index > 0)
                <div class="pt-strip" style="height: 4px; background: var(--border-color); margin-bottom: 16px;"></div>
                <div style="display:flex; justify-content: space-between; margin-bottom: 16px; font-size: 13px; font-weight: 700; color: var(--text-muted);">
                    <div>Party: {{ $offer->customer_name ?: '---' }}</div>
                    <div>Page {{ $page_index + 1 }} of {{ $page_count }}</div>
                </div>
                @endif

                <div class="pt-table-container">
                    <table class="pt-table">
                        <thead>
                            <tr>
                                <th class="col-sr">Sr.</th>
                                <th class="col-desc">Description of Plants</th>
                                <th class="col-qty">Type</th>
                                <th class="col-size">Size</th>
                                <th class="col-rate">Rate (₹)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($page_index > 0 && $offer->show_total)
                                <tr class="pt-bcf-row">
                                    <td colspan="4">Brought Forward (B/F) &rarr;</td>
                                    <td class="currency-txt font-bold amount" style="color: #111827;">₹ {{ number_format($running_total, 2) }}</td>
                                </tr>
                            @endif

                            @foreach($p_items as $idx => $item)
                                @php
                                    $item_display_index = 0;
                                    for($i=0; $i<$page_index; $i++) $item_display_index += count($pages[$i]);
                                    $item_display_index += $idx + 1;
                                    $running_total += $item->rate;
                                @endphp
                                <tr>
                                    <td class="col-sr font-bold text-gray-500">{{ $item_display_index }}</td>
                                    <td class="col-desc">
                                        <span class="pt-item-name">{{ $item->plant_name ?: '-' }}</span>
                                    </td>
                                    <td class="col-qty font-bold">{{ $item->type_of_plant ?: '-' }}</td>
                                    <td class="col-size font-bold">
                                        @if($item->plant_size_feet) {{ $item->plant_size_feet }}ft @endif
                                        @if($item->plant_size_feet && $item->bag_size_inches) / @endif
                                        @if($item->bag_size_inches) {{ $item->bag_size_inches }}in @endif
                                        @if(!$item->plant_size_feet && !$item->bag_size_inches) - @endif
                                    </td>
                                    <td class="col-rate currency-txt amount font-bold">₹ {{ number_format($item->rate, 2) }}</td>
                                </tr>
                            @endforeach

                            @php 
                                $rowCount = count($p_items); 
                                $is_first = ($page_index == 0);
                                $is_last = ($page_index == $page_count - 1);
                                
                                if ($is_last) {
                                    $fill_limit = $rowCount;
                                } else {
                                    $fill_limit = $is_first ? $limit_first_page : $limit_other_page;
                                }
                            @endphp

                            @for($i = $rowCount; $i < $fill_limit; $i++)
                                <tr>
                                    <td>&nbsp;</td><td></td><td></td><td></td><td></td>
                                </tr>
                            @endfor

                            @if(!$is_last && $offer->show_total)
                                <tr class="pt-bcf-row">
                                    <td colspan="4">Carried Forward (C/F) &rarr;</td>
                                    <td class="currency-txt font-bold amount" style="color: #111827;">₹ {{ number_format($running_total, 2) }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if($page_index == $page_count - 1)
                    <div class="pt-totals-wrapper">
                        <div class="pt-notes-area">
                            @if($offer->terms && trim($offer->terms))
                                <div style="font-size: 11px; font-weight: 800; color: var(--brand-dark); text-transform: uppercase; border-bottom: 1px solid #dcfce7; padding-bottom: 4px; margin-bottom: 8px;">Terms & Conditions</div>
                                <ul class="pt-terms-list" style="margin-top: 0; padding-left: 16px;">
                                    @foreach(array_filter(explode("\n", $offer->terms), 'trim') as $ti => $term)
                                        <li style="margin-bottom: 4px;">{{ $term }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            <div style="font-style: italic; font-size: 11px; color: var(--text-muted); margin-top: 12px;">
                                We hope this offer meets your requirements. Looking forward to your confirmation.
                            </div>
                        </div>

                        <div class="pt-totals-box">
                            @if($offer->show_total)
                                <div class="pt-total-row pt-grand-total">
                                    <span class="pt-total-label">Grand Total</span>
                                    <span class="pt-total-val currency-txt">₹ {{ number_format($offer->total, 2) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-footer">
                        <div class="pt-page-num">
                            Page {{ $page_index + 1 }} of {{ $page_count }}
                        </div>
                        <div class="pt-signature">
                            <div class="pt-sig-for">For, New Vrundavan Nursery</div>
                            <div style="font-size: 10px; color: #ccc; margin-bottom: 5px;">(Authorized Signature)</div>
                            <div class="pt-sig-line"></div>
                            <div class="pt-sig-text">Authorized Signatory</div>
                        </div>
                    </div>
                @else
                    <div style="text-align: right; margin-top: 20px; font-size: 13px; color: var(--text-muted); font-weight: 700; font-style: italic;">
                        Continued on Page {{ $page_index + 2 }} &rarr;
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>
