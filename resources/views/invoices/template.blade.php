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
        min-height: calc(297mm - 24mm); /* A4 height minus padding */
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .a5 .invoice-border {
        min-height: calc(210mm - 12mm);
    }
    .company-title {
        font-family: 'Times New Roman', serif;
        font-size: 20pt;
        font-weight: 900;
        color: #166534;
        text-transform: uppercase;
        margin: 0 0 3px 0;
        letter-spacing: -1px;
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
    .bill-details-table .highlight { color: #dc2626; margin-left: 5px; }
    .bill-details-table .dotted-line { border-bottom: 1px dotted rgba(22, 101, 52, 0.3); }

    .customer-table {
        margin-bottom: 8px;
        font-weight: bold;
    }
    .customer-name {
        border-bottom: 2px dotted rgba(22, 101, 52, 0.2);
        font-size: 12pt;
        color: #166534;
        padding-bottom: 5px;
    }
    .a5 .customer-name { font-size: 10pt; }

    /* Items Table */
    .items-table-container {
        border: 2px solid rgba(22, 101, 52, 0.3);
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .items-table {
        table-layout: fixed;
        width: 100%;
        border-collapse: collapse;
    }
    .items-table th {
        background: rgba(22, 101, 52, 0.05);
        color: #166534;
        padding: 6px 10px;
        font-size: 10pt;
        text-transform: uppercase;
        border-bottom: 2px solid rgba(22, 101, 52, 0.3);
        border-right: 1px solid rgba(22, 101, 52, 0.3);
    }
    .a5 .items-table th { font-size: 8.5pt; padding: 4px 6px; }
    .items-table th:last-child { border-right: none; }
    
    .items-table td {
        padding: 5px 10px;
        border-right: 1px solid rgba(22, 101, 52, 0.3);
        word-break: break-word;
        page-break-inside: avoid;
        font-size: 10.5pt;
        line-height: 1.15;
    }
    .a5 .items-table td { font-size: 8.5pt; padding: 4px 6px; }
    .items-table td:last-child { border-right: none; }

    .items-table tr.empty-row td {
        color: transparent;
        height: 25px;
    }
    .a5 .items-table tr.empty-row td { height: 25px; }
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
    .highlight-red { color: #dc2626; opacity: 1; border-top: 1px solid rgba(22, 101, 52, 0.1); }
    
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
        color: #166534;
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
    .jurisdiction {
        text-align: center;
        border-top: 1px solid rgba(22, 101, 52, 0.1);
        padding-top: 10px;
        margin-top: 20px;
        font-size: 9px;
        font-weight: bold;
        text-transform: uppercase;
        opacity: 0.2;
        letter-spacing: 3px;
    }

    .watermark-box {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 70%;
        opacity: 0.15;
        z-index: -1;
        pointer-events: none;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .watermark-box img {
        width: 100%;
        pointer-events: none;
    }

    /* Note: Page-specific @media print logic is handled by the calling view (show.blade.php) 
       to ensure it works correctly with the dashboard layout. */
</style>

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
                         style="height: 90px; width: auto; object-fit: contain;" alt="Nursery Logo">
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


        <!-- Bill Details -->
        <table class="bill-details-table" width="100%">
            <tr>
                <td width="30%">Bill No. <span class="highlight">#{{ $invoice->invoice_no }}</span></td>
                <td width="40%" class="dotted-line"></td>
                <td width="30%" style="text-align: right;">Date : <span style="color: #166534;">{{ $invoice->created_at->format('d/m/Y') }}</span></td>
            </tr>
        </table>

        <!-- Customer -->
        <table class="customer-table" width="100%">
            <tr>
                <td width="5%" style="font-style: italic;">M/s.</td>
                <td width="95%" class="customer-name">
                    {{ $invoice->customer_name }} @if($invoice->phone) ({{ $invoice->phone }}) @endif
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <div class="items-table-container">
            <table class="items-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="10%">Sr.No.</th>
                        <th width="45%" style="text-align: left;">Particulars</th>
                        <th width="15%" style="text-align: center;">Quantity</th>
                        <th width="15%" style="text-align: right;">Rate</th>
                        <th width="15%" style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $rowCount = count($invoice->items); 
                    @endphp
                    @foreach($invoice->items as $index => $item)

                        <tr>
                            <td style="text-align: center; color: #64748b; font-weight: bold;">{{ $index + 1 }}</td>
                            <td>
                                <strong style="font-size: 1.1em; color: #166534;">{{ $item->product_name }}</strong>
                                @php
                                    $details = [];
                                    if($item->height && $item->height !== '-') $details[] = $item->height;
                                    if($item->bag_size && $item->bag_size !== '-') $details[] = $item->bag_size;
                                @endphp
                                @if(count($details) > 0)
                                    <span style="font-size: 0.8em; opacity: 0.6; margin-left: 4px;">({{ implode(', ', $details) }})</span>
                                @endif
                            </td>
                            <td style="text-align: center; font-weight: 900;">{{ $item->quantity }}</td>
                            <td style="text-align: right; font-weight: bold;">₹{{ number_format($item->price, 2) }}</td>
                            <td style="text-align: right; font-weight: 900;">₹{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                    @for($i = $rowCount; $i < 12; $i++)
                        <tr class="empty-row {{ $i >= 8 ? 'extra-row-a5' : '' }}">

                            <td style="text-align: center;">&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endfor
                </tbody>
                <tfoot>
                    @php
                        $afterDiscount = $invoice->subtotal - $invoice->discount;
                    @endphp

                    @if($invoice->discount > 0 || $invoice->gst_amount > 0)
                        <tr>
                            <td colspan="4" class="tfoot-label">Subtotal</td>
                            <td class="tfoot-value">₹{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        @if($invoice->discount > 0)
                        <tr>
                            <td colspan="4" class="tfoot-label highlight-red">Discount</td>
                            <td class="tfoot-value highlight-red">- ₹{{ number_format($invoice->discount, 2) }}</td>
                        </tr>
                        @endif
                        
                        @if($invoice->gst_amount > 0)
                            @if($invoice->gst_type === 'exclusive')
                                <tr>
                                    <td colspan="4" class="tfoot-label">Taxable Amount</td>
                                    <td class="tfoot-value">₹{{ number_format($afterDiscount, 2) }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="4" class="tfoot-label">CGST ({{ number_format($invoice->gst_percentage / 2, 1) }}%)</td>
                                <td class="tfoot-value">₹{{ number_format($invoice->cgst, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="tfoot-label">SGST ({{ number_format($invoice->gst_percentage / 2, 1) }}%)</td>
                                <td class="tfoot-value">₹{{ number_format($invoice->sgst, 2) }}</td>
                            </tr>
                        @endif
                    @endif
                    <tr class="total-row">
                        <td colspan="2" class="total-label">Total</td>
                        <td colspan="3" class="total-value">₹{{ number_format($invoice->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Bottom Section (Notes, Bank, Signature) -->
        <div style="margin-top: auto; width: 100%;">
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
                            <div style="font-size: 11px; font-weight: bold; font-style: italic; opacity: 0.6; margin-bottom: 30px;">
                                For, New Vrundavan Nursery.
                            </div>
                            <div class="signature-line"></div>
                            <div class="signature-text">Authorized Signature</div>
                        </div>
                    </td>
                </tr>
            </table>
            
            <div class="jurisdiction">Subject to Junagadh Jurisdiction</div>
        </div>
    </div>
</div>
