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
    .offer-wrap { font-family: 'NotoSansGujarati', 'DejaVu Sans', sans-serif; color: #111; background: #fff; max-width: 210mm; margin: 0 auto; padding: 12mm; }
    .offer-head { border-bottom: 2px solid rgba(22, 101, 52, 0.2); padding-bottom: 12px; margin-bottom: 14px; }
    .company-title {
        font-family: 'Times New Roman', serif;
        font-size: 18pt;
        font-weight: 900;
        color: #166534;
        text-transform: uppercase;
        margin: 0 0 2px 0;
        letter-spacing: -0.5px;
    }
    .new-label-stamp {
        display: inline-block;
        border: 2px solid #166534;
        border-radius: 50%;
        padding: 3px 12px;
        color: #166534;
        font-size: 0.5em;
        vertical-align: middle;
        transform: rotate(-12deg);
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
    .company-subtitle { font-size: 10px; font-weight: bold; text-transform: uppercase; margin: 0; letter-spacing: 1px; }
    .company-address {
        background: #166534;
        color: #fff;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 700;
        margin-top: 8px;
        display: block;
        width: 100%;
        border-radius: 4px;
        letter-spacing: 0.2px;
        text-align: center;
    }
    .offer-meta { font-size: 13px; line-height: 1.8; margin-bottom: 8px; }
    .offer-table { width: 100%; border-collapse: collapse; margin-top: 14px; margin-bottom: 16px; }
    .offer-table th, .offer-table td { padding: 8px 6px; font-size: 12px; border-bottom: 1px solid #e5e7eb; }
    .offer-table th { text-align: left; background: transparent; font-weight: 700; border-top: 1px solid #d1d5db; }
    .amount { text-align: right; white-space: nowrap; }
    .closing { margin-top: 18px; line-height: 1.8; font-size: 13px; }
    .terms { margin-top: 10px; font-size: 12px; line-height: 1.7; }
    .signature { margin-top: 44px; text-align: right; font-size: 12px; }
    .page-break { page-break-after: always; }
    @media print {
        @page { size: A4; margin: 10mm; }
        .offer-wrap { padding: 0; }
    }
</style>

<script type="text/php">
    if (isset($pdf)) {
        $pdf->page_text(260, 810, "Page {PAGE_NUM} of {PAGE_COUNT}", 'helvetica', 9, array(0,0,0,0.5));
    }
</script>

<div class="offer-wrap">
    <div class="offer-head">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="20%" style="vertical-align: middle; text-align: left;">
                    <img src="{{ public_path('images/logo.png') }}" onerror="this.src='{{ asset('images/logo.png') }}'" style="height: 72px; width: auto; object-fit: contain;" alt="Nursery Logo">
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
                <td colspan="3" style="padding-top: 8px;">
                    <div class="company-address">Gadu - Chorvad Circle, Porbandar Highway, Gadu (Sherbaug)-362255, Dist : Junagadh</div>
                </td>
            </tr>
        </table>
    </div>

    <table width="100%" style="font-size: 12px; margin-bottom: 10px;">
        <tr>
            <td><strong>Offer No.:</strong> {{ $offer->offer_no }}</td>
            <td style="text-align: center;"><strong>Ref No.:</strong> {{ $offer->reference_no ?: '-' }}</td>
            <td style="text-align: right;"><strong>Date:</strong> {{ $offer->created_at->format('d/m/Y') }}</td>
        </tr>
    </table>

    <div class="offer-meta">
        <p><strong>To,</strong><br>{{ $offer->customer_name }}</p>
        @if($offer->address)<p><strong>Address:</strong> {{ $offer->address }}</p>@endif
        @if($offer->phone)<p><strong>Contact:</strong> {{ $offer->phone }}</p>@endif
        <p><strong>Subject:</strong> {{ $offer->subject ?: 'Plant Supply Offer' }}</p>
    </div>

    <p>{{ $offer->greeting ?: 'Dear Sir/Madam,' }}</p>
    <p>{{ $offer->intro_text ?: 'As per your requirement, we are pleased to offer the following plants for your consideration.' }}</p>

    @php
        $all_items = $offer->items;
        $first_page_limit = 12; // Adjusted for letter layout
        $other_page_limit = 22; 
        
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
        <div class="{{ ($page_index < $page_count - 1) ? 'page-break' : '' }}">
            @if($page_index > 0)
                <div style="height: 40px;"></div> {{-- Spacer for other pages header --}}
                <table width="100%" style="font-size: 11px; margin-bottom: 5px; opacity: 0.6;">
                    <tr>
                        <td>Offer No: {{ $offer->offer_no }}</td>
                        <td style="text-align: right;">{{ $offer->customer_name }}</td>
                    </tr>
                </table>
            @endif

            <table class="offer-table">
                <thead>
                    <tr>
                        <th style="width: 8%; text-align: center;">Sr No</th>
                        <th>Plant Name</th>
                        @if($offer->show_type)<th>Type of plant</th>@endif
                        @if($offer->show_size)<th style="text-align: center;">Plant Size Feet</th>@endif
                        @if($offer->show_bag)<th style="text-align: center;">Bag Size inches</th>@endif
                        <th class="amount">Rate (Rs)</th>
                    </tr>
                </thead>
                <tbody>
                    @if($page_index > 0 && $offer->show_total)
                        @php
                            $col_count = 3 + ($offer->show_type?1:0) + ($offer->show_size?1:0) + ($offer->show_bag?1:0);
                        @endphp
                        <tr style="background: #f9fafb;">
                            <td colspan="{{ $col_count - 1 }}" style="text-align: right; font-style: italic; color: #666;">Brought Forward (B/F)</td>
                            <td class="amount">₹{{ number_format($running_total, 2) }}</td>
                        </tr>
                    @endif

                    @foreach($p_items as $idx => $item)
                        @php
                            $display_idx = 0;
                            for($i=0; $i<$page_index; $i++) $display_idx += count($pages[$i]);
                            $display_idx += $idx + 1;
                            $running_total += (float)$item->rate;
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $display_idx }}</td>
                            <td>{{ $item->plant_name }}</td>
                            @if($offer->show_type)<td>{{ $item->type_of_plant ?: '-' }}</td>@endif
                            @if($offer->show_size)<td style="text-align: center;">{{ $item->plant_size_feet ?: '-' }}</td>@endif
                            @if($offer->show_bag)<td style="text-align: center;">{{ $item->bag_size_inches ?: '-' }}</td>@endif
                            <td class="amount">{{ number_format($item->rate, 2) }}</td>
                        </tr>
                    @endforeach

                    @if($page_index < $page_count - 1 && $offer->show_total)
                        @php
                            $col_count = 3 + ($offer->show_type?1:0) + ($offer->show_size?1:0) + ($offer->show_bag?1:0);
                        @endphp
                        <tr style="background: #f9fafb;">
                            <td colspan="{{ $col_count - 1 }}" style="text-align: right; font-style: italic; color: #666;">Carried Forward (C/F)</td>
                            <td class="amount">₹{{ number_format($running_total, 2) }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            @if($page_index == $page_count - 1)
                @if($offer->show_total)
                    <div style="text-align: right; font-size: 13px; line-height: 1.8; margin-top: 4px;">
                        <p><strong>Subtotal:</strong> ₹{{ number_format($offer->subtotal, 2) }}</p>
                        @if((float)$offer->discount > 0)
                            <p><strong>Discount:</strong> ₹{{ number_format($offer->discount, 2) }}</p>
                        @endif
                        <p style="font-size: 16px; border-top: 1px solid #eee; padding-top: 4px;"><strong>Final Total:</strong> ₹{{ number_format($offer->total, 2) }}</p>
                    </div>
                @endif

                <div class="closing">
                    <p>We hope this offer meets your requirements. Looking forward to your confirmation.</p>
                </div>

                @php
                    $terms = collect(preg_split("/\r\n|\n|\r/", (string) $offer->terms))
                        ->map(fn ($line) => trim($line))
                        ->filter()
                        ->values();
                @endphp
                @if($terms->isNotEmpty())
                    <div class="terms">
                        <p><strong>Terms & Conditions:</strong></p>
                        <ol style="margin: 6px 0 0 18px;">
                            @foreach($terms as $term)
                                <li>{{ preg_replace('/^\d+\.\s*/', '', $term) }}</li>
                            @endforeach
                        </ol>
                    </div>
                @endif

                <div class="signature">
                    <p>For, New Vrundavan Nursery</p>
                    <p style="margin-top: 35px; border-top: 1px dashed #999; display: inline-block; padding-top: 6px;">Authorized Signature</p>
                </div>
            @endif
        </div>
    @endforeach
</div>
