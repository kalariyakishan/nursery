<x-app-layout>
    <div x-data="offerSystem()" class="space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8">
            <div class="flex items-center gap-4">
                <div
                    class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-amber-500/20 rotate-3">
                    <span class="material-symbols-outlined text-[32px]">edit_note</span>
                </div>
                <div>
                    <h2 class="text-4xl font-black text-text-primary tracking-tighter leading-none">Edit Offer</h2>
                    <p class="text-[10px] uppercase tracking-[0.2em] text-amber-600 font-black mt-1 opacity-70">Modify
                        Existing Proposal</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('offers.index') }}"
                    class="secondary-btn h-12 px-6 rounded-xl border-border-light bg-white hover:bg-background shadow-sm group">
                    <span
                        class="material-symbols-outlined text-[20px] group-hover:rotate-[-45deg] transition-transform">history</span>
                    Recent Offers
                </a>
                <div
                    class="px-5 py-2.5 rounded-xl border border-border-light bg-white shadow-sm flex flex-col justify-center">
                    <span class="text-[9px] uppercase tracking-widest font-black text-text-secondary opacity-50">Offer
                        Date</span>
                    <input type="date" name="offer_date" form="offerForm" value="{{ $offer->created_at->format('Y-m-d') }}"
                        class="border-none p-0 bg-transparent focus:ring-0 text-sm font-bold text-text-primary">
                </div>
            </div>
        </div>

        <form id="offerForm" action="{{ route('offers.update', $offer) }}" method="POST"
            @submit.prevent="submitForm()">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 pb-32">
                <!-- LEFT SIDE: FORM -->
                <div class="xl:col-span-7 space-y-8">
                    <!-- HEADER DETAILS -->
                    <div class="card-surface p-8 shadow-xl shadow-primary/5">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-primary/10 rounded-lg text-primary">
                                <span class="material-symbols-outlined">description</span>
                            </div>
                            <h3 class="font-bold text-xl text-text-primary tracking-tight">Letter Header Details</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label
                                    class="text-[10px] uppercase tracking-wider font-black text-primary mb-1 block opacity-70 group-focus-within:opacity-100 transition-opacity">Reference
                                    No</label>
                                <input name="reference_no" x-model="referenceNo"
                                    class="input-field border-transparent bg-background/50 focus:bg-white"
                                    placeholder="Ambulagar/ENQ/2026-27/21">
                            </div>
                            <div class="group">
                                <label
                                    class="text-[10px] uppercase tracking-wider font-black text-primary mb-1 block opacity-70 group-focus-within:opacity-100 transition-opacity">Subject</label>
                                <input name="subject" x-model="subject"
                                    class="input-field border-transparent bg-background/50 focus:bg-white"
                                    placeholder="Plant Supply Offer">
                            </div>
                            <div class="group">
                                <label
                                    class="text-[10px] uppercase tracking-wider font-black text-primary mb-1 block opacity-70 group-focus-within:opacity-100 transition-opacity">Greeting</label>
                                <input name="greeting" x-model="greeting"
                                    class="input-field border-transparent bg-background/50 focus:bg-white"
                                    placeholder="Dear Sir/Madam,">
                            </div>
                            <div class="md:col-span-2 group">
                                <label
                                    class="text-[10px] uppercase tracking-wider font-black text-primary mb-1 block opacity-70 group-focus-within:opacity-100 transition-opacity">Intro
                                    Text</label>
                                <textarea name="intro_text" x-model="introText" rows="2"
                                    class="input-field border-transparent bg-background/50 focus:bg-white h-auto py-3"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- CUSTOMER DETAILS -->
                    <div class="card-surface p-8 shadow-xl shadow-primary/5">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="p-2 bg-emerald-100 rounded-lg text-emerald-700">
                                <span class="material-symbols-outlined">person_pin</span>
                            </div>
                            <h3 class="font-bold text-xl text-text-primary tracking-tight">Customer Details</h3>
                        </div>
                        <div class="space-y-6">
                            <div class="relative group">
                                <label
                                    class="text-[10px] uppercase tracking-wider font-black text-emerald-700 mb-1 block opacity-70 group-focus-within:opacity-100 transition-opacity">Party
                                    / Customer Name</label>
                                <div class="relative">
                                    <input name="customer_name" x-model="customerName"
                                        @input.debounce.300ms="searchCustomers()"
                                        class="input-field border-transparent bg-background/50 focus:bg-white font-bold pl-11 @error('customer_name') border-red-500 @enderror"
                                        required>
                                    <span
                                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-text-secondary/40">search</span>
                                </div>
                                @error('customer_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                                <!-- DROPDOWN -->
                                <div x-show="customers.length" x-transition
                                    class="absolute left-0 right-0 mt-2 bg-white border border-border-light rounded-xl shadow-2xl z-20 max-h-60 overflow-y-auto">
                                    <template x-for="(customer, i) in customers" :key="i">
                                        <button type="button" @click="selectCustomer(customer)"
                                            class="w-full text-left px-5 py-3 hover:bg-emerald-50 border-b border-border-light/50 last:border-0 transition-colors">
                                            <div class="font-bold text-text-primary" x-text="customer.name"></div>
                                            <div class="text-xs text-text-secondary flex items-center gap-2 mt-1">
                                                <span class="material-symbols-outlined text-[14px]">call</span> <span
                                                    x-text="customer.mobile || 'No contact'"></span>
                                                <span
                                                    class="material-symbols-outlined text-[14px] ml-2">location_on</span>
                                                <span x-text="customer.address || 'No address'"></span>
                                            </div>
                                        </button>
                                    </template>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="group">
                                    <label
                                        class="text-[10px] uppercase tracking-wider font-black text-emerald-700 mb-1 block opacity-70 group-focus-within:opacity-100 transition-opacity">Contact
                                        Number</label>
                                    <input name="phone" x-model="phone"
                                        class="input-field border-transparent bg-background/50 focus:bg-white">
                                </div>
                                <div class="group">
                                    <label
                                        class="text-[10px] uppercase tracking-wider font-black text-emerald-700 mb-1 block opacity-70 group-focus-within:opacity-100 transition-opacity">Full
                                        Address</label>
                                    <input name="address" x-model="address"
                                        class="input-field border-transparent bg-background/50 focus:bg-white">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- OFFER ITEMS -->
                    <div class="card-surface shadow-xl shadow-primary/5 flex flex-col">
                        <div
                            class="p-8 border-b border-border-light/50 flex items-center justify-between bg-white sticky top-0 z-10">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-amber-100 rounded-lg text-amber-700">
                                    <span class="material-symbols-outlined">eco</span>
                                </div>
                                <h3 class="font-bold text-xl text-text-primary tracking-tight">Offer Items</h3>
                            </div>
                        </div>

                        <div class="p-4 space-y-4">
                            <!-- TABLE HEADER (Desktop) -->
                            <div
                                class="hidden md:grid grid-cols-12 gap-3 px-4 py-2 text-[10px] uppercase tracking-widest font-black text-text-secondary opacity-50">
                                <div :class="(showType && (showSize || showBag)) ? 'col-span-4' : ((!showType && !(showSize || showBag)) ? 'col-span-8' : 'col-span-6')">Product / Plant Details</div>
                                <div x-show="showType" class="col-span-2">Type</div>
                                <div x-show="showSize || showBag" class="col-span-2">Size (Ft/In)</div>
                                <div class="col-span-2 text-right">Rate (₹)</div>
                                <div class="col-span-2"></div>
                            </div>

                            <div class="space-y-4">
                                <template x-for="(item, index) in items" :key="item.uid">
                                    <div
                                        class="group relative bg-background/30 rounded-2xl p-4 md:p-2 border border-transparent hover:border-primary/20 hover:bg-white hover:shadow-xl transition-all duration-300">
                                        <div class="grid grid-cols-12 gap-3 items-center">
                                            <!-- Product Selection & Name -->
                                            <div :class="(showType && (showSize || showBag)) ? 'col-span-12 md:col-span-4 space-y-2' : ((!showType && !(showSize || showBag)) ? 'col-span-12 md:col-span-8 space-y-2' : 'col-span-12 md:col-span-6 space-y-2')">
                                                <select
                                                    class="block w-full h-10 px-3 rounded-xl border-border-light text-sm focus:ring-primary/10"
                                                    @change="updateProduct(index, $event.target.value)">
                                                    <option value="">-- Quick Select --</option>
                                                    <template x-for="p in availableProducts" :key="p.id">
                                                        <option :value="p.id" :selected="item.product_id == p.id"
                                                            x-text="p.name"></option>
                                                    </template>
                                                </select>
                                                <input type="text" :name="'items['+index+'][plant_name]'"
                                                    x-model="item.plant_name" placeholder="Plant Name"
                                                    class="block w-full h-10 px-3 rounded-xl border-border-light text-sm font-bold placeholder:font-normal"
                                                    required>
                                            </div>

                                            <!-- Type -->
                                            <div x-show="showType" class="col-span-6 md:col-span-2">
                                                <input type="text" :name="'items['+index+'][type_of_plant]'"
                                                    x-model="item.type_of_plant" placeholder="Ex: Fruit"
                                                    class="block w-full h-10 px-3 rounded-xl border-border-light text-sm">
                                            </div>
                                            <!-- Size -->
                                            <div x-show="showSize || showBag" class="col-span-6 md:col-span-2 flex gap-1">
                                                <input x-show="showSize" type="text" :name="'items['+index+'][plant_size_feet]'"
                                                    x-model="item.plant_size_feet" placeholder="Ht"
                                                    class="block w-full h-10 px-2 rounded-xl border-border-light text-xs text-center"
                                                    title="Height (Feet)">
                                                <input x-show="showBag" type="text" :name="'items['+index+'][bag_size_inches]'"
                                                    x-model="item.bag_size_inches" placeholder="Bag"
                                                    class="block w-full h-10 px-2 rounded-xl border-border-light text-xs text-center"
                                                    title="Bag Size (Inches)">
                                            </div>

                                            <!-- Rate -->
                                            <div class="col-span-9 md:col-span-2 relative">
                                                <span
                                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary/50 text-xs font-bold">₹</span>
                                                <input type="number" min="0" step="0.01"
                                                    :name="'items['+index+'][rate]'" x-model.number="item.rate"
                                                    class="block w-full h-10 pl-7 pr-3 rounded-xl border-border-light text-sm text-right font-black"
                                                    required>
                                            </div>

                                            <!-- Actions -->
                                            <div class="col-span-3 md:col-span-2 flex justify-end px-2">
                                                <button type="button" @click="removeItem(index)"
                                                    class="w-10 h-10 rounded-xl flex items-center justify-center text-red-500 hover:bg-red-50 transition-colors"
                                                    title="Remove Item">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Hidden Inputs for logic -->
                                        <input type="hidden" :name="'items['+index+'][quantity]'" value="1">
                                        <input type="hidden" :name="'items['+index+'][variant]'" :value="item.variant">
                                    </div>
                                </template>
                            </div>

                            <div class="px-4 pb-4">
                                <button type="button" @click="addItem()"
                                    class="w-full py-4 bg-white border-2 border-dashed border-primary/20 text-primary rounded-2xl font-black flex items-center justify-center gap-3 hover:border-primary hover:bg-primary/5 transition-all active:scale-95 group shadow-sm">
                                    <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all">
                                        <span class="material-symbols-outlined text-[20px]">add</span>
                                    </div>
                                    Add New Plant Item
                                </button>
                            </div>
                        </div>

                        <div class="p-8 bg-background/30 border-t border-border-light/50 space-y-6">
                            <div class="space-y-4">
                                <div class="group">
                                    <label
                                        class="text-[10px] uppercase tracking-wider font-black text-primary mb-1 block opacity-70 group-focus-within:opacity-100 transition-opacity">Terms
                                        & Conditions</label>
                                    <textarea name="terms" x-model="terms" rows="12"
                                        class="input-field border-transparent bg-white shadow-sm focus:border-primary h-auto py-4 text-sm leading-relaxed"
                                        placeholder="Terms..."></textarea>
                                </div>
                                <div class="flex flex-wrap gap-4">
                                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-primary/10 shadow-sm w-fit">
                                        <input type="checkbox" name="show_total" x-model="showTotal" :value="showTotal ? 1 : 0"
                                            class="w-5 h-5 rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500">
                                        <div class="flex flex-col pr-4">
                                            <span class="text-xs font-black text-emerald-900 leading-none">Grand Total</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-primary/10 shadow-sm w-fit">
                                        <input type="checkbox" name="show_type" x-model="showType" :value="showType ? 1 : 0"
                                            class="w-5 h-5 rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500">
                                        <div class="flex flex-col pr-4">
                                            <span class="text-xs font-black text-emerald-900 leading-none">Show Type</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-primary/10 shadow-sm w-fit">
                                        <input type="checkbox" name="show_size" x-model="showSize" :value="showSize ? 1 : 0"
                                            class="w-5 h-5 rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500">
                                        <div class="flex flex-col pr-4">
                                            <span class="text-xs font-black text-emerald-900 leading-none">Show Size (Ft)</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl border border-primary/10 shadow-sm w-fit">
                                        <input type="checkbox" name="show_bag" x-model="showBag" :value="showBag ? 1 : 0"
                                            class="w-5 h-5 rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500">
                                        <div class="flex flex-col pr-4">
                                            <span class="text-xs font-black text-emerald-900 leading-none">Show Bag (In)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="discount" value="0">
                    <input type="hidden" name="subtotal" :value="subtotal">
                    <input type="hidden" name="total" :value="grandTotal">
                </div>

                <!-- RIGHT SIDE: LIVE PREVIEW -->
                <div class="xl:col-span-5">
                    <div class="sticky top-8">
                        <div class="flex items-center justify-between mb-4 px-2">
                            <h3 class="font-bold text-text-secondary uppercase tracking-widest text-xs">Live Letter
                                Preview</h3>
                            <span
                                class="px-2 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded uppercase">Premium
                                Design</span>
                        </div>

                        <div class="space-y-8 max-h-[85vh] overflow-y-auto pr-2 custom-scrollbar font-['Inter','Noto_Sans_Gujarati',sans-serif]">
                            <template x-for="(page, pageIdx) in pagedItems" :key="pageIdx">
                                <div
                                    class="card-surface bg-slate-500 p-8 min-h-[842px] flex flex-col items-center shadow-2xl overflow-hidden relative group/paper">
                                    <!-- Paper Texture -->
                                    <div class="absolute inset-0 opacity-[0.03] pointer-events-none"
                                        style="background-image: url('https://www.transparenttextures.com/patterns/natural-paper.png');">
                                    </div>

                                    <!-- THE ACTUAL LETTER -->
                                    <div
                                        class="bg-white w-full h-full shadow-2xl p-10 flex flex-col text-[12px] text-[#111827] leading-[1.35] relative">

                                        <!-- Header (Page 1 only) -->
                                        <template x-if="pageIdx === 0">
                                            <div class="relative mb-8 pb-4 border-b-2 border-emerald-800/20">
                                                <div class="flex justify-between items-start gap-4">
                                                    <img src="{{ asset('images/logo.png') }}"
                                                        class="w-16 h-16 object-contain" alt="Logo">
                                                    <div class="flex-1 text-center">
                                                        <h1
                                                            class="font-black text-3xl text-emerald-900 tracking-tight leading-none mb-2 flex items-center justify-center gap-2">
                                                            <span
                                                                class="inline-block px-1.5 py-0.5 border-2 border-emerald-900 text-xs font-black rounded-md rotate-[-5deg]">NEW</span>
                                                            VRUNDAVAN NURSERY
                                                        </h1>
                                                        <p
                                                            class="text-[11px] font-bold text-emerald-800/70 uppercase tracking-tighter">
                                                            Retailer & Wholesaler of All Fruit, Flower & Ornamental
                                                            Plants</p>
                                                    </div>
                                                    <div
                                                        class="text-right text-[11px] font-black text-emerald-900 leading-tight">
                                                        <p>Mo. 6355151302</p>
                                                        <p>Mo. 9925575862</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="mt-4 bg-emerald-900 text-white text-[11px] font-bold py-1.5 px-4 rounded-full text-center tracking-wide">
                                                    Gadu - Chorvad Circle, Porbandar Highway, Gadu (Sherbaug)-362255,
                                                    Dist : Junagadh
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Header (Page 2+) -->
                                        <template x-if="pageIdx > 0">
                                            <div
                                                class="flex justify-between items-center mb-8 pb-2 border-b border-gray-100 opacity-50 font-bold text-[11px] uppercase tracking-wider text-emerald-900">
                                                <div x-text="'Ref No: ' + (referenceNo || '---')"></div>
                                                <div x-text="'Party: ' + (customerName || '---')"></div>
                                            </div>
                                        </template>

                                        <!-- Metadata (Page 1 only) -->
                                        <template x-if="pageIdx === 0">
                                            <div class="grid grid-cols-2 gap-4 mb-6 text-[12px]">
                                                <div class="space-y-1">
                                                    <p><strong>Ref No:</strong> <span class="text-emerald-900 font-bold"
                                                            x-text="referenceNo || '__________________'"></span></p>
                                                    <p><strong>Subject:</strong> <span
                                                            class="text-emerald-900 font-bold"
                                                            x-text="subject || 'Plant Supply Offer'"></span></p>
                                                </div>
                                                <div class="text-right">
                                                    <p><strong>Date:</strong> <span
                                                            class="text-emerald-900 font-bold" x-text="offerDateFormatted"></span>
                                                    </p>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Body -->
                                        <div class="space-y-4">
                                            <template x-if="pageIdx === 0">
                                                <div>
                                                    <p class="font-bold" x-text="greeting || 'Dear Sir/Madam,'"></p>
                                                    <p
                                                        x-text="introText || 'As per your requirement, we are pleased to offer the following plants for your consideration.'">
                                                    </p>
                                                </div>
                                            </template>

                                            <table class="w-full border-collapse border border-gray-200">
                                                <thead>
                                                    <tr
                                                        class="bg-[#f2f2f2] text-[10px] uppercase font-bold text-[#374151] tracking-[0.05em]">
                                                        <th class="border border-[#94a3b8] p-2 text-center w-10">Sr</th>
                                                        <th class="border border-[#94a3b8] p-2 text-left">Description of
                                                            Plants</th>
                                                        <th x-show="showType" class="border border-[#94a3b8] p-2 text-center">Type</th>
                                                        <th x-show="showSize" class="border border-[#94a3b8] p-2 text-center">Size (Ft)</th>
                                                        <th x-show="showBag" class="border border-[#94a3b8] p-2 text-center">Bag (In)</th>
                                                        <th class="border border-[#94a3b8] p-2 text-right">Rate (₹)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Brought Forward Row -->
                                                    <template x-if="pageIdx > 0 && showTotal">
                                                        <tr class="bg-[#f8fafc] italic text-[12px] text-[#4b5563] font-semibold border-b-2 border-dashed border-[#cbd5e1]">
                                                            <td :colspan="(3 + (showType?1:0) + (showSize?1:0) + (showBag?1:0)) - 1"
                                                                class="p-1.5 text-right uppercase tracking-tighter">
                                                                Brought Forward (B/F)</td>
                                                            <td class="p-1.5 text-right font-bold text-[#111827]"
                                                                x-text="'₹' + items.slice(0, page[0].originalIndex).reduce((s, i) => s + Number(i.rate || 0), 0).toLocaleString(undefined, {minimumFractionDigits: 2})">
                                                            </td>
                                                        </tr>
                                                    </template>

                                                    <template x-for="(item, idx) in page" :key="idx">
                                                        <tr class="text-[12px]">
                                                            <td class="border border-gray-200 p-2 text-center font-bold text-gray-500"
                                                                x-text="item.originalIndex + 1"></td>
                                                            <td class="border border-gray-200 p-2">
                                                                <div class="text-[13px] font-bold text-[#111827]" x-text="item.plant_name || '-'"></div>
                                                            </td>
                                                            <td x-show="showType" class="border border-gray-200 p-2 text-center"
                                                                x-text="item.type_of_plant || '-'"></td>
                                                            <td x-show="showSize" class="border border-gray-200 p-2 text-center"
                                                                x-text="item.plant_size_feet || '-'"></td>
                                                            <td x-show="showBag" class="border border-gray-200 p-2 text-center"
                                                                x-text="item.bag_size_inches || '-'"></td>
                                                            <td class="border border-gray-200 p-2 text-right font-bold"
                                                                x-text="'₹ ' + Number(item.rate || 0).toLocaleString(undefined, {minimumFractionDigits: 2})">
                                                            </td>
                                                        </tr>
                                                    </template>

                                                    <!-- Carried Forward Row -->
                                                    <template x-if="pageIdx < pagedItems.length - 1 && showTotal">
                                                        <tr class="bg-[#f8fafc] italic text-[12px] text-[#4b5563] font-semibold border-b-2 border-dashed border-[#cbd5e1]">
                                                            <td :colspan="(3 + (showType?1:0) + (showSize?1:0) + (showBag?1:0)) - 1"
                                                                class="p-1.5 text-right uppercase tracking-tighter">
                                                                Carried Forward (C/F)</td>
                                                            <td class="p-1.5 text-right font-bold text-[#111827]"
                                                                x-text="'₹' + items.slice(0, page[page.length-1].originalIndex + 1).reduce((s, i) => s + Number(i.rate || 0), 0).toLocaleString(undefined, {minimumFractionDigits: 2})">
                                                            </td>
                                                        </tr>
                                                    </template>

                                                    <!-- Fill remaining space -->
                                                    <template x-if="page.length < (pageIdx === pagedItems.length - 1 ? page.length : (pageIdx === 0 ? 24 : 30))">
                                                        <template x-for="i in ((pageIdx === pagedItems.length - 1 ? page.length : (pageIdx === 0 ? 24 : 30)) - page.length)">
                                                            <tr class="h-7 border-b border-gray-100">
                                                                <td class="border-x border-gray-200 p-2"></td>
                                                                <td class="border-x border-gray-200 p-2"></td>
                                                                <template x-if="showType"><td class="border-x border-gray-200 p-2"></td></template>
                                                                <template x-if="showSize"><td class="border-x border-gray-200 p-2"></td></template>
                                                                <template x-if="showBag"><td class="border-x border-gray-200 p-2"></td></template>
                                                                <td class="border-x border-gray-200 p-2"></td>
                                                            </tr>
                                                        </template>
                                                    </template>
                                                </tbody>

                                                <template x-if="pageIdx === pagedItems.length - 1 && showTotal">
                                                    <tfoot>
                                                        <tr class="bg-gray-50 border-t-2 border-b-2 border-[#111827]">
                                                            <td :colspan="(3 + (showType?1:0) + (showSize?1:0) + (showBag?1:0)) - 1"
                                                                class="p-2 text-right uppercase tracking-widest text-[15px] font-black text-[#000]">
                                                                Grand Total</td>
                                                            <td class="p-2 text-right text-[#000] font-black text-[18px]"
                                                                x-text="'₹' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2})">
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </template>
                                            </table>

                                            <template x-if="pageIdx === pagedItems.length - 1">
                                                <div class="mt-6 text-[13px] text-[#4b5563]">
                                                    <template x-if="terms && terms.trim()">
                                                        <div class="mb-6">
                                                            <p
                                                                class="font-bold text-xs uppercase tracking-widest text-[#111827] border-b border-[#111827]/10 pb-1 mb-2">
                                                                Terms & Conditions</p>
                                                            <ul class="space-y-1">
                                                                <template
                                                                    x-for="(term, ti) in terms.split('\n').filter(t => t.trim())"
                                                                    :key="ti">
                                                                    <li class="flex gap-2">
                                                                        <span class="text-[#111827] font-bold"
                                                                            x-text="(ti+1) + '.'"></span>
                                                                        <span x-text="term"></span>
                                                                    </li>
                                                                </template>
                                                            </ul>
                                                        </div>
                                                    </template>
                                                    <p class="text-[12px] italic opacity-80">We hope this offer meets
                                                        your requirements. Looking forward to your confirmation.</p>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Footer -->
                                        <div class="mt-auto pt-10 flex justify-between items-end print:pt-16">
                                            <div class="text-[13px] font-semibold text-[#4b5563]"
                                                x-text="'Page ' + (pageIdx + 1) + ' of ' + pagedItems.length"></div>

                                            <template x-if="pageIdx === pagedItems.length - 1">
                                                <div class="text-center w-48">
                                                    <p class="font-bold text-[#4b5563] text-[13px] mb-8">For, New
                                                        Vrundavan Nursery</p>
                                                    <div class="border-t border-dashed border-[#111827] pt-2">
                                                        <p
                                                            class="text-[12px] font-bold uppercase text-[#000]">
                                                            Authorized Signatory</p>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    <footer
        class="fixed bottom-0 left-0 md:left-64 right-0 bg-white/80 backdrop-blur-md border-t border-border-light/50 p-6 flex justify-between items-center z-50 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
        <div class="hidden md:flex items-center gap-6">
            <div class="flex flex-col">
                <span class="text-[10px] uppercase tracking-widest font-black text-text-secondary opacity-50">Final
                    Amount</span>
                <span class="text-2xl font-black text-amber-600"
                    x-text="'₹' + grandTotal.toLocaleString(undefined, {minimumFractionDigits: 2})"></span>
            </div>
        </div>
        <div class="flex items-center gap-4 w-full md:w-auto">
            <button type="submit" form="offerForm"
                class="bg-amber-600 text-white font-black py-4 px-12 rounded-2xl shadow-xl shadow-amber-600/20 hover:shadow-2xl hover:shadow-amber-600/40 group w-full md:w-auto flex items-center justify-center gap-3 transition-all hover:scale-[1.02] active:scale-95">
                <span class="material-symbols-outlined group-hover:rotate-12 transition-transform">save</span>
                Update Offer Changes
            </button>
        </div>
    </footer>
    </div>

    <script>
        function offerSystem() {
            return {
                availableProducts: {!! json_encode($products) !!},
                customers: [],
                offerDate: {!! json_encode($offer->created_at->format('Y-m-d')) !!},
                referenceNo: {!! json_encode(old('reference_no', $offer->reference_no)) !!},
                subject: {!! json_encode(old('subject', $offer->subject)) !!},
                greeting: {!! json_encode(old('greeting', $offer->greeting)) !!},
                introText: {!! json_encode(old('intro_text', $offer->intro_text)) !!},
                customerName: {!! json_encode(old('customer_name', $offer->customer_name)) !!},
                phone: {!! json_encode(old('phone', $offer->phone)) !!},
                address: {!! json_encode(old('address', $offer->address)) !!},
                showTotal: @json(old('show_total', (bool)$offer->show_total)),
                showType: @json(old('show_type', (bool)$offer->show_type)),
                showSize: @json(old('show_size', (bool)$offer->show_size)),
                showBag: @json(old('show_bag', (bool)$offer->show_bag)),
                terms: {!! json_encode(old('terms', $offer->terms)) !!},
                discount: Number({!! json_encode(old('discount', $offer->discount)) !!}),
                items: [],
                init() {
                    const savedItems = {!! json_encode($offer->items) !!};
                    this.items = savedItems.map(i => ({
                        uid: Math.random().toString(36).substr(2, 9),
                        product_id: i.product_id || '',
                        variant_id: i.variant_id || '',
                        plant_name: i.plant_name || '',
                        type_of_plant: i.type_of_plant || '',
                        plant_size_feet: i.plant_size_feet || '',
                        bag_size_inches: i.bag_size_inches || '',
                        variant: i.variant || '',
                        quantity: Number(i.quantity || 1),
                        rate: Number(i.rate || 0),
                        availableVariants: []
                    }));

                    if (this.items.length === 0) {
                        this.items.push(this.newItem());
                    }

                    this.items.forEach((item, index) => {
                        if (!item.product_id) return;
                        const product = this.availableProducts.find(p => String(p.id) === String(item.product_id));
                        if (!product) return;
                        this.items[index].availableVariants = product.variants || [];
                    });
                },
                get offerDateFormatted() {
                   const d = new Date(this.offerDate);
                   return d.toLocaleDateString('en-GB');
                },
                newItem() {
                    return { uid: Math.random().toString(36).substr(2, 9), product_id: '', variant_id: '', plant_name: '', type_of_plant: '', plant_size_feet: '', bag_size_inches: '', variant: '', quantity: 1, rate: 0, availableVariants: [] };
                },
                addItem() { this.items.push(this.newItem()); },
                removeItem(index) {
                    if (this.items.length > 1) this.items.splice(index, 1);
                },
                lineTotal(item) {
                    return Number(item.quantity || 0) * Number(item.rate || 0);
                },
                updateProduct(index, productId) {
                    const product = this.availableProducts.find(p => String(p.id) === String(productId));
                    if (!product) return;
                    this.items[index].product_id = product.id;
                    this.items[index].plant_name = product.name || '';
                    this.items[index].availableVariants = product.variants || [];
                    if (this.items[index].availableVariants.length) {
                        this.updateVariant(index, this.items[index].availableVariants[0].id);
                    }
                },
                updateVariant(index, variantId) {
                    const variants = this.items[index].availableVariants || [];
                    const variant = variants.find(v => String(v.id) === String(variantId));
                    if (!variant) return;
                    this.items[index].variant_id = variant.id;
                    this.items[index].plant_size_feet = variant.height || '';
                    this.items[index].bag_size_inches = variant.bag_size || '';
                    this.items[index].rate = Number(variant.price || 0);
                    this.items[index].variant = `${variant.height || ''} / ${variant.bag_size || ''}`.trim();
                },
                get subtotal() {
                    return this.items.reduce((sum, item) => sum + this.lineTotal(item), 0);
                },
                get grandTotal() {
                    return Math.max(0, this.subtotal - Number(this.discount || 0));
                },
                get pagedItems() {
                    const firstPageLimit = 12;
                    const otherPageLimit = 22;
                    const pages = [];
                    let currentItems = [];
                    let limit = firstPageLimit;

                    this.items.forEach((item, index) => {
                        currentItems.push({ ...item, originalIndex: index });
                        if (currentItems.length >= limit) {
                            pages.push(currentItems);
                            currentItems = [];
                            limit = otherPageLimit;
                        }
                    });

                    if (currentItems.length > 0 || pages.length === 0) {
                        pages.push(currentItems);
                    }
                    return pages;
                },
                validate() {
                    if (!this.customerName.trim()) return false;
                    return this.items.every(i => i.plant_name && Number(i.quantity) > 0 && Number(i.rate) >= 0);
                },
                submitForm() {
                    if (this.validate()) {
                        document.getElementById('offerForm').submit();
                    } else {
                        alert('Please fill out all required fields correctly. Ensure Party Name and all Plant Details (Name, Rate) are entered.');
                    }
                },
                async searchCustomers() {
                    if (this.customerName.length < 2) {
                        this.customers = [];
                        return;
                    }
                    try {
                        const res = await fetch(`/api/customers/search?query=${encodeURIComponent(this.customerName)}`);
                        if (!res.ok) return;
                        this.customers = await res.json();
                    } catch (e) {
                        this.customers = [];
                    }
                },
                selectCustomer(customer) {
                    this.customerName = customer.name || '';
                    this.phone = customer.mobile || '';
                    this.address = customer.address || '';
                    this.customers = [];
                }
            }
        }
    </script>
</x-app-layout>
