@if(isset($isPdf) && $isPdf)
    @extends('layouts.print')
    @section('content')
        @include('offers.letter')
    @endsection
@else
    <x-app-layout>
        <style>
            @media print {
                @page { margin: 0; size: A4; }
                aside, header, .no-print { display: none !important; }
                body, main { margin: 0 !important; padding: 0 !important; background: transparent !important; }
                .print-page { page-break-after: always; box-shadow: none !important; border: none !important; margin: 0 !important; }
                .print-page:last-child { page-break-after: auto; }
            }
        </style>

        <div class="mb-6 flex flex-wrap gap-3 no-print max-w-[210mm] mx-auto">
            <a href="{{ route('offers.index') }}" class="secondary-btn">
                <span class="material-symbols-outlined">arrow_back</span>Back
            </a>
            <a href="{{ route('offers.pdf', $offer) }}" target="_blank" class="secondary-btn">
                <span class="material-symbols-outlined">picture_as_pdf</span>PDF
            </a>
            <button onclick="window.print()" class="primary-btn">
                <span class="material-symbols-outlined">print</span>Print
            </button>
        </div>

        @php
            $all_items = $offer->items;
            $pages = [];
            $current_page_items = [];
            $page_index = 0;
            
            // Increased limits to utilize the extra whitespace at the bottom
            $limit_first_page = 24; 
            $limit_other_page = 30; 
            $limit_first_with_footer = 18;
            $limit_other_with_footer = 24;

            foreach($all_items as $idx => $it) {
                $it->originalIndex = $idx;
                $current_page_items[] = $it;
                
                $is_first = ($page_index == 0);
                $max_no_footer = $is_first ? $limit_first_page : $limit_other_page;
                
                if (count($current_page_items) == $max_no_footer && ($idx + 1) < count($all_items)) {
                    $pages[] = $current_page_items;
                    $current_page_items = [];
                    $page_index++;
                }
            }
            
            // For the last batch of items (or if no items were mapped yet)
            $is_first = ($page_index == 0);
            $max_with_footer = $is_first ? $limit_first_with_footer : $limit_other_with_footer;
            
            if (count($current_page_items) > $max_with_footer) {
                // Doesn't fit with footer! Push current, then create a blank page for the footer.
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

        <div class="space-y-8 max-w-[210mm] mx-auto print:space-y-0 font-['Inter','Noto_Sans_Gujarati',sans-serif]">
            @foreach($pages as $pageIdx => $pageItems)
                <div class="print-page card-surface bg-slate-500 print:bg-transparent p-8 print:p-0 min-h-[842px] print:min-h-0 flex flex-col items-center shadow-2xl print:shadow-none relative group/paper">
                    <!-- Paper Texture -->
                    <div class="absolute inset-0 opacity-[0.03] print:hidden pointer-events-none" style="background-image: url('https://www.transparenttextures.com/patterns/natural-paper.png');"></div>
                    
                    <!-- THE ACTUAL LETTER -->
                    <div class="bg-white w-full h-full shadow-2xl print:shadow-none p-10 print:p-6 flex flex-col text-[12px] text-[#111827] leading-[1.35] relative print:box-border">
                        
                        <!-- Header -->
                        @if($pageIdx === 0)
                            <div class="relative mb-8 pb-4 border-b-2 border-emerald-800/20">
                                <div class="flex justify-between items-start gap-4">
                                    <img src="{{ asset('images/logo.png') }}" class="w-16 h-16 object-contain" alt="Logo">
                                    <div class="flex-1 text-center">
                                        <h1 class="font-black text-3xl text-emerald-900 tracking-tight leading-none mb-2 flex items-center justify-center gap-2">
                                            <span class="inline-block px-1.5 py-0.5 border-2 border-emerald-900 text-xs font-black rounded-md rotate-[-5deg]">NEW</span>
                                            VRUNDAVAN NURSERY
                                        </h1>
                                        <p class="text-[11px] font-bold text-emerald-800/70 uppercase tracking-tighter">Retailer & Wholesaler of All Fruit, Flower & Ornamental Plants</p>
                                    </div>
                                    <div class="text-right text-[11px] font-black text-emerald-900 leading-tight">
                                        <p>Mo. 6355151302</p>
                                        <p>Mo. 9925575862</p>
                                    </div>
                                </div>
                                <div class="mt-4 bg-emerald-900 text-white text-[11px] font-bold py-1.5 px-4 rounded-full text-center tracking-wide">
                                    Gadu - Chorvad Circle, Porbandar Highway, Gadu (Sherbaug)-362255, Dist : Junagadh
                                </div>
                            </div>
                        @else
                            <div class="flex justify-between items-center mb-8 pb-2 border-b border-gray-100 opacity-50 font-bold text-[11px] uppercase tracking-wider text-emerald-900">
                                <div>Ref No: {{ $offer->reference_no ?: '---' }}</div>
                                <div>Party: {{ $offer->customer_name ?: '---' }}</div>
                            </div>
                        @endif

                        <!-- Metadata -->
                        @if($pageIdx === 0)
                            <div class="grid grid-cols-2 gap-4 mb-6 text-[12px]">
                                <div class="space-y-1">
                                    <p><strong>Ref No:</strong> <span class="text-emerald-900 font-bold">{{ $offer->reference_no ?: '__________________' }}</span></p>
                                    <p><strong>Subject:</strong> <span class="text-emerald-900 font-bold">{{ $offer->subject ?: 'Plant Supply Offer' }}</span></p>
                                </div>
                                <div class="text-right">
                                    <p><strong>Date:</strong> <span class="text-emerald-900 font-bold">{{ $offer->created_at->format('d/m/Y') }}</span></p>
                                </div>
                            </div>
                        @endif

                        <!-- Body -->
                        <div class="space-y-4">
                            @if($pageIdx === 0)
                                <div>
                                    <p class="font-bold">{{ $offer->greeting ?: 'Dear Sir/Madam,' }}</p>
                                    <p>{{ $offer->intro_text ?: 'As per your requirement, we are pleased to offer the following plants for your consideration.' }}</p>
                                </div>
                            @endif
                            
                            <table class="w-full border-collapse border border-gray-200">
                                <thead>
                                    <tr class="bg-[#f2f2f2] text-[10px] uppercase font-bold text-[#374151] tracking-[0.05em]">
                                        <th class="border border-[#94a3b8] p-2 text-center w-10">Sr</th>
                                        <th class="border border-[#94a3b8] p-2 text-left">Description of Plants</th>
                                        <th class="border border-[#94a3b8] p-2 text-center">Type</th>
                                        <th class="border border-[#94a3b8] p-2 text-center">Size</th>
                                        <th class="border border-[#94a3b8] p-2 text-right">Rate (₹)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($pageIdx > 0 && $offer->show_total)
                                        <tr class="bg-[#f8fafc] italic text-[12px] text-[#4b5563] font-semibold border-b-2 border-dashed border-[#cbd5e1]">
                                            <td colspan="4" class="p-1.5 text-right uppercase tracking-tighter">Brought Forward (B/F)</td>
                                            <td class="p-1.5 text-right font-bold text-[#111827]">₹{{ number_format($running_total, 2) }}</td>
                                        </tr>
                                    @endif

                                    @foreach($pageItems as $item)
                                        @php
                                            $running_total += $item->rate;
                                        @endphp
                                        <tr class="text-[12px]">
                                            <td class="border border-gray-200 p-2 text-center font-bold text-gray-500">{{ $item->originalIndex + 1 }}</td>
                                            <td class="border border-gray-200 p-2">
                                                <div class="text-[13px] font-bold text-[#111827]">{{ $item->plant_name ?: '-' }}</div>
                                            </td>
                                            <td class="border border-gray-200 p-2 text-center">{{ $item->type_of_plant ?: '-' }}</td>
                                            <td class="border border-gray-200 p-2 text-center">
                                                @if($item->plant_size_feet) {{ $item->plant_size_feet }}ft @endif
                                                @if($item->plant_size_feet && $item->bag_size_inches) / @endif
                                                @if($item->bag_size_inches) {{ $item->bag_size_inches }}in @endif
                                                @if(!$item->plant_size_feet && !$item->bag_size_inches) - @endif
                                            </td>
                                            <td class="border border-gray-200 p-2 text-right font-bold">₹ {{ number_format($item->rate, 2) }}</td>
                                        </tr>
                                    @endforeach

                                    @if($pageIdx < $page_count - 1 && $offer->show_total)
                                        <tr class="bg-[#f8fafc] italic text-[12px] text-[#4b5563] font-semibold border-b-2 border-dashed border-[#cbd5e1]">
                                            <td colspan="4" class="p-1.5 text-right uppercase tracking-tighter">Carried Forward (C/F)</td>
                                            <td class="p-1.5 text-right font-bold text-[#111827]">₹{{ number_format($running_total, 2) }}</td>
                                        </tr>
                                    @endif

                                    @php
                                        // Dynamic empty rows filling based on the max limit for the current page type
                                        $rowCount = count($pageItems);
                                        $is_first = ($pageIdx == 0);
                                        $is_last = ($pageIdx == $page_count - 1);
                                        
                                        if ($is_last) {
                                            $fill_limit = $rowCount; // Do not fill extra rows on the last page to keep footer close
                                        } else {
                                            $fill_limit = $is_first ? $limit_first_page : $limit_other_page;
                                        }
                                        $empty_rows = $fill_limit - $rowCount;
                                    @endphp
                                    @if($empty_rows > 0)
                                        @for($i = 0; $i < $empty_rows; $i++)
                                            <tr class="h-7 border-b border-gray-100">
                                                <td class="border-x border-gray-200 p-2"></td><td class="border-x border-gray-200 p-2"></td><td class="border-x border-gray-200 p-2"></td><td class="border-x border-gray-200 p-2"></td><td class="border-x border-gray-200 p-2"></td>
                                            </tr>
                                        @endfor
                                    @endif
                                </tbody>
                                
                                @if($pageIdx === $page_count - 1 && $offer->show_total)
                                    <tfoot>
                                        <tr class="bg-gray-50 border-t-2 border-b-2 border-[#111827]">
                                            <td colspan="4" class="p-2 text-right uppercase tracking-widest text-[15px] font-black text-[#000]">Grand Total</td>
                                            <td class="p-2 text-right text-[#000] font-black text-[18px]">₹{{ number_format($offer->total, 2) }}</td>
                                        </tr>
                                    </tfoot>
                                @endif
                            </table>

                            @if($pageIdx === $page_count - 1)
                                <div class="mt-6 text-[13px] text-[#4b5563]">
                                    @if($offer->terms && trim($offer->terms))
                                        <div class="mb-6">
                                            <p class="font-bold text-xs uppercase tracking-widest text-[#111827] border-b border-[#111827]/10 pb-1 mb-2">Terms & Conditions</p>
                                            <ul class="space-y-1">
                                                @foreach(array_filter(explode("\n", $offer->terms), 'trim') as $ti => $term)
                                                    <li class="flex gap-2">
                                                        <span class="text-[#111827] font-bold">{{ $ti + 1 }}.</span>
                                                        <span>{{ $term }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <p class="text-[12px] italic opacity-80">We hope this offer meets your requirements. Looking forward to your confirmation.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Footer -->
                        <div class="mt-auto pt-10 flex justify-between items-end print:pt-16">
                            <div class="text-[13px] font-semibold text-[#4b5563]">Page {{ $pageIdx + 1 }} of {{ $page_count }}</div>
                            
                            @if($pageIdx === $page_count - 1)
                                <div class="text-center w-48">
                                    <p class="font-bold text-[#4b5563] text-[13px] mb-8">For, New Vrundavan Nursery</p>
                                    <div class="border-t border-dashed border-[#111827] pt-2">
                                        <p class="text-[12px] font-bold uppercase text-[#000]">Authorized Signatory</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-app-layout>
@endif
