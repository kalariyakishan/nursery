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
        font-size: 11pt;
        line-height: 1.5;
    }
    .invoice-wrapper.a5 {
        width: 148mm;
        min-height: 210mm;
        padding: 6mm;
        font-size: 10pt;
        line-height: 1.4;
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
        height: 100%;
        position: relative;
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
    .a5 .company-title { font-size: 18pt; }
    
    .company-subtitle {
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        margin: 0;
        letter-spacing: 1px;
    }
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
        font-size: 12px;
        font-weight: bold;
        border-bottom: 1px solid rgba(22, 101, 52, 0.3);
        padding-bottom: 5px;
    }
    .bill-details-table .highlight { color: #dc2626; margin-left: 5px; }
    .bill-details-table .dotted-line { border-bottom: 1px dotted rgba(22, 101, 52, 0.3); }

    .customer-table {
        margin-bottom: 8px;
        font-weight: bold;
    }
    .customer-name {
        border-bottom: 2px dotted rgba(22, 101, 52, 0.2);
        font-size: 16px;
        color: #166534;
        padding-bottom: 5px;
    }
    .a5 .customer-name { font-size: 14px; }

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
        font-size: 10px;
        text-transform: uppercase;
        border-bottom: 2px solid rgba(22, 101, 52, 0.3);
        border-right: 1px solid rgba(22, 101, 52, 0.3);
    }
    .items-table th:last-child { border-right: none; }
    
    .items-table td {
        padding: 5px 10px;
        border-right: 1px solid rgba(22, 101, 52, 0.3);
        word-break: break-word;
        page-break-inside: avoid;
        font-size: 10.5pt;
        line-height: 1.2;
    }
    .items-table td:last-child { border-right: none; }
    .a5 .items-table td, .a5 .items-table th { padding: 6px; }

    .items-table tr.empty-row td {
        color: transparent;
        height: 25px;
    }
    .a5 .items-table tr.empty-row td { height: 25px; }
    .a5 .extra-row-a5 { display: none; }


    .tfoot-label {
        padding: 8px 15px;
        text-align: right;
        font-style: italic;
        opacity: 0.6;
        text-transform: uppercase;
        border-right: 1px solid rgba(22, 101, 52, 0.3);
        border-top: 2px solid rgba(22, 101, 52, 0.3);
    }
    .tfoot-value {
        padding: 8px 15px;
        text-align: right;
        border-top: 2px solid rgba(22, 101, 52, 0.3);
    }
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
        font-size: 18px;
        border-right: 1px solid rgba(22, 101, 52, 0.3);
    }
    .total-value {
        text-align: right;
        font-size: 24px;
        color: #166534;
        background: rgba(22, 101, 52, 0.05);
        font-weight: 900;
    }
    
    /* Footer Elements */
    .footer-table { margin-top: auto; }
    .notes-box, .bank-box {
        border: 2px solid rgba(22, 101, 52, 0.3);
        padding: 10px;
        border-radius: 8px;
        background: rgba(22, 101, 52, 0.02);
        margin-bottom: 5px;
    }
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

    /* Print Overrides */
    @media print {
        @page { 
            size: A4; 
            margin: 5mm; 
        }

        /* Hide everything by default */
        body * {
            visibility: hidden !important;
        }

        /* Show ONLY the invoice content */
        .print-area, .print-area * {
            visibility: visible !important;
        }

        /* Position the print area at the very top-left of the page */
        .print-area {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Completely remove layout UI from print */
        header, nav, aside, .navbar, .sidebar, .topbar, .no-print, .menu, .search-bar, .icons, [x-data] button {
            display: none !important;
        }

        body { 
            margin: 0 !important; 
            padding: 0 !important; 
            background: #fff !important; 
            -webkit-print-color-adjust: exact; 
        }

        .invoice-wrapper { 
            margin: 0 !important; 
            padding: 0 !important;
            box-shadow: none !important; 
            width: 100% !important; 
            min-height: 0 !important;
            height: auto !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        .invoice-border {
            height: auto !important;
            border: 2px solid rgba(22, 101, 52, 0.2) !important;
        }

        table, tr, td {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* Ensure parent containers don't restrict visibility or cause extra pages */
        html, body, .min-h-screen, main, .max-w-\[1600px\], .printable-bill {
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
            visibility: visible !important;
            margin: 0 !important;
            padding: 0 !important;
        }
    }
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
                <td width="20%" style="vertical-align: top;">
                    <!-- Placeholder for logo if needed later -->
                </td>
                <td width="60%" style="text-align: center;">
                    <h1 class="company-title" style="margin-bottom: 5px; white-space: nowrap;">
                        <span style="font-size: 0.75em; vertical-align: middle;">NEW</span> VRUNDAVAN NURSERY
                    </h1>
                    <p class="company-subtitle" style="font-size: 10px; opacity: 0.8;">Retailer & Wholesaler of All Fruit, Flower & Ornamental Plants</p>
                </td>

                <td width="20%" style="vertical-align: top; text-align: right; white-space: nowrap;">
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
                <td width="30%">Bill No. <span class="highlight">#{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}</span></td>
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
                    @if($invoice->discount > 0)
                        <tr>
                            <td colspan="4" class="tfoot-label">Subtotal</td>
                            <td class="tfoot-value font-bold">₹{{ number_format($invoice->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="tfoot-label highlight-red">Discount</td>
                            <td class="tfoot-value highlight-red">- ₹{{ number_format($invoice->discount, 2) }}</td>
                        </tr>
                    @endif
                    <tr class="total-row">
                        <td colspan="2" class="total-label">Total</td>
                        <td colspan="3" class="total-value">₹{{ number_format($invoice->total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer -->
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
