<x-app-layout>
    <div x-data="invoiceSystem()">
        <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter">નવું ટેક્સ ઇન્વોઇસ (New
                    POS Invoice)</h2>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em]">SaaS Billing
                        System</span>
                    <span class="w-1 h-1 bg-border-light rounded-full"></span>
                    <span class="text-xs font-bold text-primary">INV-{{ date('Y') }}-XXXX</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-4">
                <button type="button" @click="resetForm()"
                    class="bg-white text-red-600 px-6 py-3 rounded-lg border border-red-100 font-bold text-sm flex items-center gap-2 hover:bg-red-50 transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">delete</span>
                    રીસેટ
                </button>
                <div
                    class="px-6 py-3 bg-white rounded-lg border border-border-light shadow-subtle flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px] text-primary">calendar_today</span>
                    <input type="date" name="invoice_date" form="invoiceForm" value="{{ date('Y-m-d') }}"
                        class="border-none p-0 text-xs font-bold focus:ring-0 cursor-pointer bg-transparent">
                </div>
            </div>
        </div>

        <form id="invoiceForm" action="{{ route('invoices.store') }}" method="POST" autocomplete="off" @submit.prevent="if(validate() && !isSubmitting) { isSubmitting = true; localStorage.removeItem(draftKey); $el.submit(); }">
            @csrf
            <div class="grid grid-cols-12 gap-8 pb-32">
                <!-- Left Section: Customer Info + Product Selector -->
                <div class="col-span-12 lg:col-span-4 space-y-6">
                    <div class="card-surface p-6 shadow-premium">
                        <h3 class="text-lg font-bold text-text-primary gujarati-text flex items-center gap-2 mb-6">
                            <span class="material-symbols-outlined text-primary text-[20px]"
                                style="font-variation-settings: 'FILL' 1;">person</span>
                            ગ્રાહકની માહિતી
                        </h3>
                        <div class="space-y-4">
                            <div class="relative" x-on:click.away="showCustomerDropdown = false">
                                <label
                                    class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">ગ્રાહકનું
                                    નામ (Customer Name)</label>
                                <input name="customer_name" required 
                                    x-model="customerName"
                                    @input.debounce.300ms="searchCustomers()"
                                    @keydown.down.prevent="navigateDropdown('down')"
                                    @keydown.up.prevent="navigateDropdown('up')"
                                    @keydown.enter.prevent="selectHighlighted()"
                                    @focus="if(customers.length > 0) showCustomerDropdown = true"
                                    :class="{'border-red-500': attemptedSubmit && !customerName}"
                                    class="input-field gujarati-text font-bold text-lg @error('customer_name') border-red-500 @enderror"
                                    placeholder="અમિતભાઈ શાહ..." type="text" autocomplete="disabled" />
                                 <p x-show="attemptedSubmit && !customerName" class="text-red-500 text-[10px] font-bold mt-1">ગ્રાહકનું નામ જરૂરી છે</p>
                                
                                <!-- Dropdown -->
                                <div x-show="showCustomerDropdown" 
                                    class="absolute z-50 w-full mt-1 bg-white border border-border-light rounded-xl shadow-2xl overflow-hidden max-h-60 overflow-y-auto"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0">
                                    <template x-if="isSearching">
                                        <div class="p-4 text-center text-xs font-bold text-text-secondary flex items-center justify-center gap-2">
                                            <div class="w-4 h-4 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
                                            Searching...
                                        </div>
                                    </template>
                                    <template x-if="searchError">
                                        <div class="p-4 text-center text-xs font-bold text-red-500 bg-red-50 border-b border-red-100">
                                            <span class="material-symbols-outlined text-[16px] align-middle mr-1">error</span>
                                            સર્વર કનેક્શન એરર! ડેટા લાવી શકાયો નથી.
                                        </div>
                                    </template>
                                    <template x-if="!isSearching && !searchError && customers.length === 0">
                                        <div class="p-4 text-center text-xs font-bold text-red-500">
                                            No customer found. You can enter manually.
                                        </div>
                                    </template>
                                    <template x-for="(c, index) in customers" :key="index">
                                        <div @click="selectCustomer(c)"
                                            :class="{'bg-primary/5 text-primary': selectedIndex === index, 'text-text-primary hover:bg-background': selectedIndex !== index}"
                                            class="p-4 cursor-pointer border-b border-border-light/50 last:border-0 transition-colors group">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-sm gujarati-text" x-text="c.name"></span>
                                                <div class="flex items-center gap-3 mt-1">
                                                    <span class="text-[10px] opacity-60 flex items-center gap-1">
                                                        <span class="material-symbols-outlined text-[14px]">call</span>
                                                        <span x-text="c.mobile || 'No Mobile'"></span>
                                                    </span>
                                                    <template x-if="c.address">
                                                        <span class="text-[10px] opacity-60 flex items-center gap-1">
                                                            <span class="material-symbols-outlined text-[14px]">location_on</span>
                                                            <span x-text="c.address"></span>
                                                        </span>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                @error('customer_name')
                                    <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">મોબાઇલ
                                    નંબર (Mobile)</label>
                                <input name="phone" x-model="customerPhone" class="input-field font-bold tracking-widest @error('phone') border-red-500 @enderror"
                                    placeholder="99245xxxxx" type="tel" autocomplete="disabled" />
                                @error('phone')
                                    <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label
                                    class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">સરનામું
                                    (Address)</label>
                                <textarea name="address" x-model="customerAddress"
                                    class="block w-full rounded-lg border-border-light shadow-sm focus:border-primary focus:ring focus:ring-primary/10 bg-white transition-all gujarati-text p-4 font-semibold text-sm placeholder-text-secondary/20 resize-none @error('address') border-red-500 @enderror"
                                    placeholder="મોડાસા, ગુજરાત" rows="2" autocomplete="disabled"></textarea>
                                @error('address')
                                    <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Right Section: Invoice Table -->
                <div class="col-span-12 lg:col-span-8 space-y-6">
                    <div class="card-surface shadow-premium">
                        <!-- Desktop Header -->
                        <div class="hidden md:grid grid-cols-12 bg-background border-b border-border-light px-6 py-4">
                            <div class="col-span-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">
                                આઈટમ (Product)</div>
                            <div
                                class="col-span-2 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-center">
                                સાઇઝ/ઊંચાઈ</div>
                            <div
                                class="col-span-2 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-center">
                                જથ્થો</div>
                            <div
                                class="col-span-2 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">
                                દર (₹)</div>
                            <div
                                class="col-span-2 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">
                                કુલ (₹)</div>
                        </div>

                        <!-- Rows -->
                        @if ($errors->has('items*'))
                            <div class="px-6 py-2 bg-red-50 border-b border-red-100 italic">
                                <p class="text-red-600 text-[10px] font-bold italic">બધી આઈટમનું નામ, જથ્થો અને ભાવ ભરવા ફરજિયાત છે.</p>
                            </div>
                        @endif
                        <div class="divide-y divide-border-light/40 overflow-visible">
                            <template x-for="(item, index) in items" :key="item.uid || index">
                                <div
                                    class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center group relative hover:bg-background/20 transition-colors">
                                    <input type="hidden" :name="'items['+index+'][product_id]'" :value="item.product_id">
                                    <input type="hidden" :name="'items['+index+'][variant_id]'" :value="item.variant_id">
                                    <div class="col-span-4 space-y-2">
                                        <div class="md:hidden text-[10px] font-bold text-text-secondary uppercase mb-1">
                                            આઈટમ</div>
                                        <div class="relative">
                                            <select
                                                class="w-full bg-background/50 border-none rounded-lg text-xs font-bold py-1.5 px-3 focus:ring-1 focus:ring-primary/20 mb-1"
                                                @change="updateProduct(index, $event.target.value)">
                                                <option value="">ઉત્પાદન પસંદ કરો...</option>
                                                <template x-for="p in availableProducts" :key="p.id">
                                                    <option :value="p.id" x-text="p.name"
                                                        :selected="item.product_id == p.id"></option>
                                                </template>
                                            </select>
                                            <input type="text" :id="'name-'+index" :name="'items['+index+'][product_name]'"
                                                x-model="item.product_name"
                                                :class="{'text-red-500 placeholder-red-200': attemptedSubmit && !item.product_name}"
                                                class="w-full bg-transparent border-none p-0 focus:ring-0 gujarati-text font-bold text-text-primary text-base placeholder-text-secondary/20"
                                                placeholder="Product Name..."
                                                @keydown.enter.prevent="focusNext(index, 'qty')">
                                            <p x-show="attemptedSubmit && !item.product_name" class="text-red-500 text-[9px] font-bold mt-1">નામ જરૂરી છે</p>
                                        </div>
                                    </div>
                                    <div class="col-span-2 flex flex-col items-center">
                                        <div class="md:hidden text-[10px] font-bold text-text-secondary uppercase mb-1">
                                            સાઇઝ</div>
                                        <div class="flex gap-1 items-center bg-background/50 rounded-lg p-1">
                                            <input type="text" :name="'items['+index+'][height]'" x-model="item.height"
                                                class="w-10 bg-transparent border-none p-0 text-center text-xs font-bold text-text-secondary focus:ring-0"
                                                placeholder="Ht">
                                            <span class="text-[10px] opacity-20">/</span>
                                            <input type="text" :name="'items['+index+'][bag_size]'"
                                                x-model="item.bag_size"
                                                class="w-10 bg-transparent border-none p-0 text-center text-xs font-bold text-text-secondary focus:ring-0"
                                                placeholder="Bag">
                                        </div>
                                        <select x-show="item.availableVariants.length > 0"
                                            class="w-full bg-transparent border-none py-0 px-1 text-[10px] font-bold text-primary/70 cursor-pointer focus:ring-0 text-center mt-1"
                                            @change="updateVariant(index, $event.target.value)">
                                            <option value="">વેરિઅન્ટ્સ...</option>
                                            <template x-for="v in item.availableVariants" :key="v.id">
                                                <option :value="v.id" x-text="v.height + ' | ' + v.bag_size"
                                                    :selected="item.variant_id == v.id"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-span-2 text-center">
                                        <div class="md:hidden text-[10px] font-bold text-text-secondary uppercase mb-1">
                                            જથ્થો</div>
                                        <input type="number" :id="'qty-'+index" :name="'items['+index+'][quantity]'"
                                            x-model.number="item.quantity"
                                            :class="{'border-red-500 bg-red-50': attemptedSubmit && (!item.quantity || item.quantity <= 0)}"
                                            class="w-full h-10 bg-white border border-border-light rounded-lg text-center text-sm font-bold text-primary focus:border-primary/50 focus:ring-0"
                                            min="1" @keydown.enter.prevent="focusNext(index, 'price')" />
                                        <p x-show="attemptedSubmit && (!item.quantity || item.quantity <= 0)" class="text-red-500 text-[9px] font-bold mt-1 text-center">જથ્થો જરૂરી</p>
                                    </div>
                                    <div class="col-span-2 text-right">
                                        <div class="md:hidden text-[10px] font-bold text-text-secondary uppercase mb-1">
                                            દર</div>
                                        <div class="relative">
                                            <input type="number" step="0.01" :id="'price-'+index"
                                                :name="'items['+index+'][price]'" x-model.number="item.price"
                                                :class="{'border-red-500 bg-red-50': attemptedSubmit && (!item.price && item.price !== 0)}"
                                                class="w-full h-10 bg-white border border-border-light rounded-lg text-right text-sm font-bold focus:border-primary/50 focus:ring-0 px-3"
                                                placeholder="0.00"
                                                @keydown.enter.prevent="index === items.length - 1 ? addItem() : focusNext(index + 1, 'name')" />
                                            <p x-show="attemptedSubmit && (!item.price && item.price !== 0)" class="text-red-500 text-[9px] font-bold mt-1 text-right">ભાવ જરૂરી</p>
                                        </div>
                                    </div>
                                    <div class="col-span-2 text-right">
                                        <div class="md:hidden text-[10px] font-bold text-text-secondary uppercase mb-1">
                                            કુલ</div>
                                        <span class="text-base font-black text-text-primary tracking-tight"
                                            x-text="(item.quantity * item.price).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                        <button type="button" @click="removeItem(index)"
                                            class="ml-2 w-7 h-7 rounded-lg bg-red-100 text-red-600 flex md:absolute md:-right-2 md:top-1/2 md:-translate-y-1/2 md:opacity-0 md:group-hover:opacity-100 md:group-hover:right-2 items-center justify-center transition-all hover:bg-red-600 hover:text-white active:scale-90">
                                            <span class="material-symbols-outlined text-[16px]">close</span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Footer Actions -->
                        <div
                            class="p-6 bg-background/30 border-t border-border-light/50 flex flex-col md:flex-row justify-between items-center gap-4">
                            <button type="button" @click="addItem()"
                                class="flex items-center gap-2 text-primary hover:text-primary-dark font-bold text-xs uppercase tracking-widest transition-all group active:scale-95">
                                <span
                                    class="material-symbols-outlined text-[20px] bg-primary/10 rounded-full p-1 text-primary group-hover:bg-primary group-hover:text-white transition-all">add</span>
                                <span class="gujarati-text underline underline-offset-4">વધુ આઈટમ ઉમેરો (Add
                                    Item)</span>
                            </button>
                            <div class="flex items-center gap-4 text-xs font-bold text-text-secondary">
                                <span x-text="items.length + ' આઈટમ્સ કુલ'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="card-surface p-6 border-border-light/30">
                            <label
                                class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-3 block opacity-60">મોટી
                                નોંધ (Extra Notes)</label>
                            <textarea name="notes" x-model="notes"
                                class="block w-full rounded-lg border-border-light shadow-sm focus:border-primary focus:ring-1 focus:ring-primary/20 bg-white p-4 text-xs font-bold gujarati-text placeholder-text-secondary/20 resize-none"
                                placeholder="કોઈ ખાસ સૂચના લખો..." rows="4"></textarea>
                        </div>
                        <div class="card-surface p-6 space-y-3 bg-white border-primary/10">
                            <div class="flex justify-between items-center px-2">
                                <span class="text-text-secondary text-xs uppercase font-bold opacity-60">Subtotal</span>
                                <span class="font-bold text-sm tracking-tight"
                                    x-text="'₹ ' + subtotal.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                            </div>
                            <div class="flex justify-between items-center px-4 py-3 bg-red-50/50 rounded-lg border border-red-100/50">
                                <span class="text-red-700 text-xs font-bold uppercase">Discount</span>
                                <div class="flex items-center gap-2">
                                    <input type="number" x-model.number="discount"
                                        class="w-20 h-8 border-none bg-white rounded shadow-sm text-center text-xs font-bold text-red-600 focus:ring-1 focus:ring-red-200">
                                    <span class="font-bold text-xs text-red-700"
                                        x-text="'- ₹' + discount.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                </div>
                            </div>
                            <template x-if="gstSettings.enabled">
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center px-2">
                                        <div class="flex flex-col">
                                            <span class="text-text-secondary text-[10px] uppercase font-bold opacity-60">CGST</span>
                                            <span class="text-[9px] font-bold text-text-secondary/40" x-text="'(' + gstSettings.cgst_percentage + '%)'"></span>
                                        </div>
                                        <span class="font-bold text-sm tracking-tight" x-text="'₹ ' + cgstAmount.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                    </div>
                                    <div class="flex justify-between items-center px-2">
                                        <div class="flex flex-col">
                                            <span class="text-text-secondary text-[10px] uppercase font-bold opacity-60">SGST</span>
                                            <span class="text-[9px] font-bold text-text-secondary/40" x-text="'(' + gstSettings.sgst_percentage + '%)'"></span>
                                        </div>
                                        <span class="font-bold text-sm tracking-tight" x-text="'₹ ' + sgstAmount.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                    </div>
                                    <div class="flex justify-between items-center px-4 py-2 bg-primary/5 rounded-lg border border-primary/10">
                                        <span class="text-primary text-[10px] font-bold uppercase">Total GST</span>
                                        <span class="font-bold text-xs text-primary" x-text="'₹ ' + gstAmount.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                    </div>
                                </div>
                            </template>
                            <div class="pt-4 border-t border-border-light flex justify-between items-center">
                                <div class="gujarati-text font-black text-xl text-primary leading-none">ફાઈનલ ટોટલ</div>
                                <div class="text-right">
                                    <p
                                        class="text-[10px] uppercase font-bold text-text-secondary opacity-40 leading-none mb-1">
                                        Final Amount</p>
                                    <p class="text-3xl font-black text-primary tracking-tighter leading-none"
                                        x-text="'₹ ' + grandTotal.toLocaleString('en-IN', {minimumFractionDigits: 2})">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="subtotal" :value="subtotal">
            <input type="hidden" name="discount" :value="discount">
            <input type="hidden" name="gst" :value="gstAmount">
            <input type="hidden" name="total" :value="grandTotal">
        </form>

    <!-- Print Preview Modal -->
    <div x-show="showPreviewModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showPreviewModal" x-transition.opacity class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" @click="showPreviewModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showPreviewModal" x-transition class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-8 sm:pb-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-xl leading-6 font-black text-gray-900 mb-6 flex items-center justify-between border-b pb-4" id="modal-title">
                                <span class="flex items-center gap-2"><span class="material-symbols-outlined text-primary">visibility</span> ઇન્વોઇસ પ્રિવ્યૂ</span>
                                <button type="button" @click="showPreviewModal = false" class="text-gray-400 hover:text-gray-500 focus:outline-none"><span class="material-symbols-outlined">close</span></button>
                            </h3>
                            <div class="mt-4 border border-border-light rounded-xl p-6 bg-background/30 shadow-subtle">
                                <div class="grid grid-cols-2 gap-4 mb-6 pb-6 border-b border-dashed border-gray-200">
                                    <div class="text-left">
                                        <p class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1">ગ્રાહક</p>
                                        <p class="font-bold text-lg text-primary gujarati-text" x-text="customerName || 'None'"></p>
                                        <p class="text-sm font-bold text-text-secondary mt-1" x-show="customerPhone" x-text="'+91 ' + customerPhone"></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1">તારીખ</p>
                                        <p class="font-black text-lg">{{ date('d M, Y') }}</p>
                                    </div>
                                </div>
                                <div class="overflow-hidden border border-border-light rounded-xl">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-[10px] font-black text-text-secondary uppercase tracking-wider">આઈટમ (Product)</th>
                                                <th class="px-4 py-3 text-center text-[10px] font-black text-text-secondary uppercase tracking-wider">જથ્થો (Qty)</th>
                                                <th class="px-4 py-3 text-right text-[10px] font-black text-text-secondary uppercase tracking-wider">ભાવ (Price)</th>
                                                <th class="px-4 py-3 text-right text-[10px] font-black text-text-secondary uppercase tracking-wider">કુલ (Total)</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            <template x-for="(item, index) in items" :key="index">
                                                <template x-if="item.product_name || item.quantity > 0">
                                                    <tr>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900 gujarati-text" x-text="item.product_name || '-'"></td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-600 text-center" x-text="item.quantity"></td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-600 text-right" x-text="'₹ ' + Number(item.price).toLocaleString('en-IN', {minimumFractionDigits: 2})"></td>
                                                        <td class="px-4 py-3 whitespace-nowrap text-sm font-black text-primary text-right" x-text="'₹ ' + (item.quantity * item.price).toLocaleString('en-IN', {minimumFractionDigits: 2})"></td>
                                                    </tr>
                                                </template>
                                            </template>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-6 flex justify-end">
                                    <div class="w-72 space-y-3 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                        <div class="flex justify-between text-sm"><span class="font-bold text-gray-500 uppercase text-[10px] tracking-widest">Subtotal</span> <span class="font-black text-gray-800" x-text="'₹ ' + subtotal.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></div>
                                        <div class="flex justify-between text-sm text-red-600" x-show="discount > 0"><span class="font-bold uppercase text-[10px] tracking-widest">Discount</span> <span class="font-black" x-text="'- ₹ ' + discount.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></div>
                                        <template x-if="gstSettings.enabled && gstAmount > 0">
                                            <div class="flex justify-between text-sm text-emerald-600"><span class="font-bold uppercase text-[10px] tracking-widest">GST</span> <span class="font-black" x-text="'+ ₹ ' + gstAmount.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></div>
                                        </template>
                                        <div class="flex justify-between text-lg font-black pt-3 border-t border-dashed border-gray-200 text-primary"><span>Total</span> <span x-text="'₹ ' + grandTotal.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-4 sm:px-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-gray-200">
                    <button type="button" @click="showPreviewModal = false" class="w-full inline-flex justify-center rounded-lg border-2 border-gray-200 px-6 py-3 bg-white text-sm font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 focus:outline-none transition-all sm:w-auto uppercase tracking-widest hidden sm:block">
                        સુધારો (EDIT)
                    </button>
                    <button type="button" @click="submitFormFromPreview()" class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-lg shadow-primary/20 px-8 py-3 bg-primary text-sm font-bold text-white hover:bg-primary-dark focus:outline-none transition-all sm:w-auto uppercase tracking-widest flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">task_alt</span> બનાવો (SUBMIT)
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sticky Footer -->
    <footer
        class="fixed bottom-0 md:left-64 left-0 right-0 bg-white border-t border-border-light px-4 md:px-8 py-4 md:py-5 flex flex-col md:flex-row justify-between items-center z-50 shadow-[0_-10px_30px_rgba(0,0,0,0.03)] transition-all duration-300">
        <div class="flex items-center justify-between w-full md:w-auto gap-8 mb-4 md:mb-0">
            <div class="flex flex-col min-w-[120px]">
                <span class="text-[10px] uppercase font-bold text-text-secondary opacity-60 leading-none mb-1">Net
                    Payable</span>
                <span class="text-2xl font-black text-primary tracking-tighter leading-tight whitespace-nowrap"
                    x-text="'₹ ' + Number(grandTotal).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
            </div>
            <div class="h-8 w-[1px] bg-border-light hidden md:block"></div>
            <p class="text-[10px] font-bold text-text-secondary gujarati-text opacity-50 hidden lg:block">સરનામું અને
                સંપર્ક વિગતો આપોઆપ <br>બિલીંગ પર છપાશે.</p>
        </div>
        <div class="flex gap-2 md:gap-4 w-full md:w-auto">
            <button type="button" @click="if(validate()) showPreviewModal = true"
                class="secondary-btn flex-1 md:flex-none text-[10px] md:text-xs uppercase tracking-widest px-4 md:px-8 cursor-pointer py-3 border border-primary/20 hover:border-primary text-primary transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined text-[18px]">visibility</span>
                <span class="hidden sm:inline">પ્રિવ્યૂ </span>(VIEW)
            </button>
            <button type="submit" form="invoiceForm"
                :disabled="isSubmitting"
                :class="{'opacity-75 cursor-wait': isSubmitting}"
                class="primary-btn flex-1 md:flex-none text-[10px] md:text-xs uppercase tracking-widest px-6 md:px-10 shadow-[0_10px_20px_rgba(var(--primary),0.3)] py-3 leading-none flex items-center justify-center gap-2 bg-primary text-white font-bold rounded-lg hover:bg-primary-dark hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 relative overflow-hidden">
                <span x-show="!isSubmitting" class="material-symbols-outlined text-[18px] transition-transform group-hover:scale-110">task_alt</span>
                <div x-show="isSubmitting" style="display: none;" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                
                <span class="whitespace-nowrap" x-text="isSubmitting ? 'બની રહ્યું છે...' : 'બનાવો (SUBMIT)'"></span>
            </button>
        </div>
    </footer>
    </div>

    <script>
        function invoiceSystem() {
            return {
                availableProducts: @json($products),
                gstSettings: @json($gstSettings),
                items: [],
                subtotal: 0,
                discount: 0,
                gstAmount: 0,
                cgstAmount: 0,
                sgstAmount: 0,
                grandTotal: 0,
                search: '',
                
                // Customer Autocomplete State
                draftKey: 'invoice_draft_{{ auth()->id() ?? "guest" }}',
                searchController: null,
                draftTimeout: null,
                customerName: @json(old('customer_name', '')),
                customerPhone: @json(old('phone', '')),
                customerAddress: @json(old('address', '')),
                customers: [],
                showCustomerDropdown: false,
                isSearching: false,
                searchError: false,
                selectedIndex: -1,
                attemptedSubmit: false,
                isSubmitting: false,
                showPreviewModal: false,
                notes: @json(old('notes', '')),

                generateUid() {
                    return Date.now().toString(36) + Math.random().toString(36).substr(2);
                },

                init() {
                    const rawOldItems = @json(old('items'));
                    const oldItems = rawOldItems ? Object.values(rawOldItems) : null;
                    
                    if (oldItems && oldItems.length > 0) {
                        this.items = oldItems.map(item => ({
                            uid: this.generateUid(),
                            product_id: item.product_id || '',
                            variant_id: item.variant_id || '',
                            product_name: item.product_name || '',
                            height: item.height || '',
                            bag_size: item.bag_size || '',
                            quantity: item.quantity || 1,
                            price: item.price || 0,
                            availableVariants: []
                        }));
                        this.subtotal = parseFloat({{ old('subtotal', 0) }});
                        this.discount = parseFloat({{ old('discount', 0) }});
                        this.gstAmount = parseFloat({{ old('gst', 0) }});
                        this.grandTotal = parseFloat({{ old('total', 0) }});
                    } else {
                        const draft = localStorage.getItem(this.draftKey);
                        if (draft) {
                            try {
                                const parsedDraft = JSON.parse(draft);
                                this.items = parsedDraft.items || [];
                                this.items.forEach(item => { if(!item.uid) item.uid = this.generateUid(); });
                                this.customerName = parsedDraft.customerName || '';
                                this.customerPhone = parsedDraft.customerPhone || '';
                                this.customerAddress = parsedDraft.customerAddress || '';
                                this.discount = parsedDraft.discount || 0;
                                this.notes = parsedDraft.notes || '';
                                if (this.items.length === 0) this.addItem();
                                setTimeout(() => this.calculate(), 100);
                            } catch (e) {
                                this.addItem();
                            }
                        } else {
                            this.addItem();
                        }
                        
                        this.subtotal = parseFloat({{ old('subtotal', 0) }});
                        if (!draft) this.discount = parseFloat({{ old('discount', 0) }});
                        this.gstAmount = parseFloat({{ old('gst', 0) }});
                        this.grandTotal = parseFloat({{ old('total', 0) }});
                    }

                    this.$watch('items', () => { this.calculate(); this.saveDraft(); }, { deep: true });
                    this.$watch('discount', () => { this.calculate(); this.saveDraft(); });
                    this.$watch('customerName', () => this.saveDraft());
                    this.$watch('customerPhone', () => this.saveDraft());
                    this.$watch('customerAddress', () => this.saveDraft());
                    this.$watch('notes', () => this.saveDraft());
                },
                
                saveDraft() {
                    if (this.isSubmitting) return;
                    clearTimeout(this.draftTimeout);
                    this.draftTimeout = setTimeout(() => {
                        const draft = {
                            items: this.items,
                            customerName: this.customerName,
                            customerPhone: this.customerPhone,
                            customerAddress: this.customerAddress,
                            discount: this.discount,
                            notes: this.notes
                        };
                        localStorage.setItem(this.draftKey, JSON.stringify(draft));
                    }, 500);
                },

                addItem() {
                    const maxLimit = this.gstSettings.enabled ? 20 : 25;
                    if (this.items.length >= maxLimit) {
                        alert(`તમે બિલમાં ${maxLimit} થી વધુ આઈટમ ઉમેરી શકતા નથી.`);
                        return;
                    }

                    this.items.push({
                        uid: this.generateUid(),
                        product_id: '',
                        variant_id: '',
                        product_name: '',
                        height: '',
                        bag_size: '',
                        quantity: 1,
                        price: 0,
                        availableVariants: []
                    });
                    this.$nextTick(() => {
                        this.focusNext(this.items.length - 1, 'name');
                    });
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },

                updateProduct(index, productId) {
                    const product = this.availableProducts.find(p => p.id == productId);
                    if (product) {
                        this.items[index].product_id = product.id;
                        this.items[index].product_name = product.name;
                        this.items[index].availableVariants = product.variants;

                        if (product.variants.length > 0) {
                            this.updateVariant(index, product.variants[0].id);
                        }
                    }
                },

                updateVariant(index, variantId) {
                    const variant = this.items[index].availableVariants.find(v => v.id == variantId);
                    if (variant) {
                        this.items[index].variant_id = variant.id;
                        this.items[index].height = variant.height;
                        this.items[index].bag_size = variant.bag_size;
                        this.items[index].price = variant.price;
                    }
                },

                calculate() {
                    // Prevent discount from being negative
                    if (this.discount < 0) this.discount = 0;

                    this.subtotal = this.items.reduce((sum, item) => sum + (Math.max(0, parseFloat(item.quantity) || 0) * (parseFloat(item.price) || 0)), 0);
                    
                    if (this.discount > this.subtotal) this.discount = this.subtotal;

                    const amountToTax = this.subtotal - this.discount;
                    
                    if (this.gstSettings.enabled && this.gstSettings.percentage > 0) {
                        if (this.gstSettings.type === 'inclusive') {
                            const basePrice = amountToTax / (1 + (this.gstSettings.percentage / 100));
                            this.gstAmount = amountToTax - basePrice;
                            this.grandTotal = amountToTax;
                            
                            this.cgstAmount = basePrice * (this.gstSettings.cgst_percentage / 100);
                            this.sgstAmount = basePrice * (this.gstSettings.sgst_percentage / 100);
                        } else {
                            this.gstAmount = amountToTax * (this.gstSettings.percentage / 100);
                            this.grandTotal = amountToTax + this.gstAmount;
                            
                            this.cgstAmount = amountToTax * (this.gstSettings.cgst_percentage / 100);
                            this.sgstAmount = amountToTax * (this.gstSettings.sgst_percentage / 100);
                        }
                    } else {
                        this.gstAmount = 0;
                        this.cgstAmount = 0;
                        this.sgstAmount = 0;
                        this.grandTotal = amountToTax;
                    }

                    if (this.gstAmount < 0) this.gstAmount = 0;
                    if (this.grandTotal < 0) this.grandTotal = 0;

                    this.gstAmount = parseFloat(this.gstAmount.toFixed(2));
                    this.cgstAmount = parseFloat(this.cgstAmount.toFixed(2));
                    this.sgstAmount = parseFloat(this.sgstAmount.toFixed(2));
                    this.grandTotal = parseFloat(this.grandTotal.toFixed(2));
                    this.subtotal = parseFloat(this.subtotal.toFixed(2));
                },

                focusNext(index, field) {
                    // Logic to jump focus to next field
                    const currentItem = this.items[index];
                    if (field === 'qty') document.getElementById('qty-' + index)?.focus();
                    if (field === 'price') document.getElementById('price-' + index)?.focus();
                    if (field === 'name') document.getElementById('name-' + index)?.focus();
                },

                resetForm() {
                    localStorage.removeItem(this.draftKey);
                    this.notes = '';
                    this.customerName = '';
                    this.customerPhone = '';
                    this.customerAddress = '';
                    this.subtotal = 0;
                    this.discount = 0;
                    this.gstAmount = 0;
                    this.cgstAmount = 0;
                    this.sgstAmount = 0;
                    this.grandTotal = 0;
                    this.items = [];
                    this.addItem();
                    this.attemptedSubmit = false;
                    this.isSubmitting = false;
                    this.searchError = false;
                },

                submitFormFromPreview() {
                    if (this.isSubmitting) return;
                    this.isSubmitting = true;
                    this.showPreviewModal = false;
                    localStorage.removeItem(this.draftKey);
                    document.getElementById('invoiceForm').submit();
                },

                validate() {
                    this.attemptedSubmit = true;
                    let isValid = true;

                    if (!this.customerName || this.customerName.trim() === '') {
                        isValid = false;
                    }

                    this.items.forEach(item => {
                        if (!item.product_name || item.product_name.trim() === '') isValid = false;
                        if (!item.quantity || item.quantity <= 0) isValid = false;
                        if (item.price === '' || item.price === null || item.price === undefined) isValid = false;
                    });

                    if (!isValid) {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }

                    return isValid;
                },

                async searchCustomers() {
                    if (this.customerName.length < 2) {
                        this.customers = [];
                        this.showCustomerDropdown = false;
                        this.searchError = false;
                        return;
                    }

                    this.isSearching = true;
                    this.showCustomerDropdown = true;
                    this.searchError = false;
                    this.selectedIndex = -1;

                    if (this.searchController) this.searchController.abort();
                    this.searchController = new AbortController();

                    try {
                        const response = await fetch(`/api/customers/search?query=${encodeURIComponent(this.customerName)}`, {
                            signal: this.searchController.signal
                        });
                        if (!response.ok) throw new Error('API Error');
                        this.customers = await response.json();
                    } catch (error) {
                        if (error.name === 'AbortError') return;
                        console.error('Error fetching customers:', error);
                        this.searchError = true;
                        this.customers = [];
                    } finally {
                        this.isSearching = false;
                    }
                },

                selectCustomer(customer) {
                    this.customerName = customer.name;
                    this.customerPhone = customer.mobile || '';
                    this.customerAddress = customer.address || '';
                    this.showCustomerDropdown = false;
                    this.customers = [];
                    this.selectedIndex = -1;
                },

                navigateDropdown(direction) {
                    if (this.customers.length === 0) return;

                    if (direction === 'down') {
                        this.selectedIndex = (this.selectedIndex + 1) % this.customers.length;
                    } else if (direction === 'up') {
                        this.selectedIndex = (this.selectedIndex - 1 + this.customers.length) % this.customers.length;
                    }
                    
                    // Scroll into view if needed
                    this.$nextTick(() => {
                        const activeItem = this.$el.querySelector('.bg-primary\\/5');
                        if (activeItem) {
                            activeItem.scrollIntoView({ block: 'nearest' });
                        }
                    });
                },

                selectHighlighted() {
                    if (this.selectedIndex >= 0 && this.selectedIndex < this.customers.length) {
                        this.selectCustomer(this.customers[this.selectedIndex]);
                    } else {
                        this.showCustomerDropdown = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>