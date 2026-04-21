<x-app-layout>
    <div x-data="invoiceSystem()">
        <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter">ઇન્વોઇસ સુધારો (Edit Invoice)</h2>
                <div class="flex items-center gap-3 mt-1">
                    <span class="text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em]">SaaS Billing System</span>
                    <span class="w-1 h-1 bg-border-light rounded-full"></span>
                    <span class="text-xs font-bold text-primary">{{ $invoice->invoice_no }}</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('invoices.index') }}" class="bg-white text-text-secondary px-6 py-3 rounded-lg border border-border-light font-bold text-sm flex items-center gap-2 hover:bg-background transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    પાછા જાઓ
                </a>
                <div class="px-6 py-3 bg-white rounded-lg border border-border-light shadow-subtle flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px] text-primary">calendar_today</span>
                    <input type="date" name="invoice_date" form="invoiceForm" value="{{ $invoice->created_at->format('Y-m-d') }}" 
                           class="border-none p-0 text-xs font-bold focus:ring-0 cursor-pointer bg-transparent">
                </div>
            </div>
        </div>

        <form id="invoiceForm" action="{{ route('invoices.update', $invoice) }}" method="POST" autocomplete="off" @submit.prevent="if(validate() && !isSubmitting) { isSubmitting = true; $el.submit(); }">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-12 gap-8 pb-32">
                <!-- Left Section: Customer Info -->
                <div class="col-span-12 lg:col-span-4 space-y-6">
                    <div class="card-surface p-6 shadow-premium">
                        <h3 class="text-lg font-bold text-text-primary gujarati-text flex items-center gap-2 mb-6">
                            <span class="material-symbols-outlined text-primary text-[20px]" style="font-variation-settings: 'FILL' 1;">person</span>
                            ગ્રાહકની માહિતી
                        </h3>
                        <div class="space-y-4">
                            <div class="relative" x-on:click.away="showCustomerDropdown = false">
                                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">ગ્રાહકનું નામ (Customer Name)</label>
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
                                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">મોબાઇલ નંબર (Mobile)</label>
                                <input name="phone" x-model="customerPhone"
                                       class="input-field font-bold tracking-widest @error('phone') border-red-500 @enderror" 
                                       placeholder="99245xxxxx" type="tel" autocomplete="disabled"/>
                                @error('phone')
                                    <p class="text-red-500 text-[10px] font-bold mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">સરનામું (Address)</label>
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
                            <div class="col-span-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">આઈટમ (Product)</div>
                            <div class="col-span-2 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-center">સાઇઝ/ઊંચાઈ</div>
                            <div class="col-span-2 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-center">જથ્થો</div>
                            <div class="col-span-2 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">દર (₹)</div>
                            <div class="col-span-2 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">કુલ (₹)</div>
                        </div>

                        <!-- Rows -->
                        @if ($errors->has('items*'))
                            <div class="px-6 py-2 bg-red-50 border-b border-red-100 italic">
                                <p class="text-red-600 text-[10px] font-bold italic">બધી આઈટમનું નામ, જથ્થો અને ભાવ ભરવા ફરજિયાત છે.</p>
                            </div>
@endif
                        <div class="divide-y divide-border-light/40 overflow-visible">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="p-4 md:p-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center group relative hover:bg-background/20 transition-colors">
                                    <div class="col-span-4 space-y-2">
                                        <div class="md:hidden text-[10px] font-bold text-text-secondary uppercase mb-1">આઈટમ</div>
                                        <div class="relative">
                                            <select class="w-full bg-background/50 border-none rounded-lg text-xs font-bold py-1.5 px-3 focus:ring-1 focus:ring-primary/20 mb-1" 
                                                    @change="updateProduct(index, $event.target.value)">
                                                <option value="">ઉત્પાદન પસંદ કરો...</option>
                                                <template x-for="p in availableProducts" :key="p.id">
                                                    <option :value="p.id" x-text="p.name" :selected="item.product_id == p.id"></option>
                                                </template>
                                            </select>
                                            <input type="text" :id="'name-'+index" :name="'items['+index+'][product_name]'" x-model="item.product_name" 
                                                   :class="{'text-red-500 placeholder-red-200': attemptedSubmit && !item.product_name}"
                                                   class="w-full bg-transparent border-none p-0 focus:ring-0 gujarati-text font-bold text-text-primary text-base placeholder-text-secondary/20" 
                                                   placeholder="Product Name..."
                                                   @keydown.enter.prevent="focusNext(index, 'qty')">
                                            <p x-show="attemptedSubmit && !item.product_name" class="text-red-500 text-[9px] font-bold mt-1">નામ જરૂરી છે</p>
                                        </div>
                                    </div>
                                    <div class="col-span-2 flex flex-col items-center">
                                        <div class="md:hidden text-[10px] font-bold text-text-secondary uppercase mb-1">સાઇઝ</div>
                                        <div class="flex gap-1 items-center bg-background/50 rounded-lg p-1">
                                            <input type="text" :name="'items['+index+'][height]'" x-model="item.height" 
                                                   class="w-10 bg-transparent border-none p-0 text-center text-xs font-bold text-text-secondary focus:ring-0" 
                                                   placeholder="Ht">
                                            <span class="text-[10px] opacity-20">/</span>
                                            <input type="text" :name="'items['+index+'][bag_size]'" x-model="item.bag_size" 
                                                   class="w-10 bg-transparent border-none p-0 text-center text-xs font-bold text-text-secondary focus:ring-0" 
                                                   placeholder="Bag">
                                        </div>
                                        <select x-show="item.availableVariants.length > 0"
                                                class="w-full bg-transparent border-none py-0 px-1 text-[10px] font-bold text-primary/70 cursor-pointer focus:ring-0 text-center mt-1"
                                                @change="updateVariant(index, $event.target.value)">
                                            <option value="">વેરિઅન્ટ્સ...</option>
                                            <template x-for="v in item.availableVariants" :key="v.id">
                                                <option :value="v.id" x-text="v.height + ' | ' + v.bag_size" :selected="item.variant_id == v.id"></option>
                                            </template>
                                        </select>
                                    </div>
                                    <div class="col-span-2 text-center">
                                        <div class="md:hidden text-[10px] font-bold text-text-secondary uppercase mb-1">જથ્થો</div>
                                        <input type="number" :id="'qty-'+index" :name="'items['+index+'][quantity]'" x-model.number="item.quantity" 
                                               :class="{'border-red-500 bg-red-50': attemptedSubmit && (!item.quantity || item.quantity <= 0)}"
                                               class="w-full h-10 bg-white border border-border-light rounded-lg text-center text-sm font-bold text-primary focus:border-primary/50 focus:ring-0" 
                                               min="1"
                                               @keydown.enter.prevent="focusNext(index, 'price')"/>
                                        <p x-show="attemptedSubmit && (!item.quantity || item.quantity <= 0)" class="text-red-500 text-[9px] font-bold mt-1 text-center">જથ્થો જરૂરી</p>
                                    </div>
                                    <div class="col-span-2 text-right">
                                        <div class="md:hidden text-[10px] font-bold text-text-secondary uppercase mb-1">દર</div>
                                        <div class="relative">
                                            <input type="number" step="0.01" :id="'price-'+index" :name="'items['+index+'][price]'" x-model.number="item.price" 
                                                   :class="{'border-red-500 bg-red-50': attemptedSubmit && (!item.price && item.price !== 0)}"
                                                   class="w-full h-10 bg-white border border-border-light rounded-lg text-right text-sm font-bold focus:border-primary/50 focus:ring-0 px-3" 
                                                   placeholder="0.00"
                                                   @keydown.enter.prevent="index === items.length - 1 ? addItem() : focusNext(index + 1, 'name')"/>
                                            <p x-show="attemptedSubmit && (!item.price && item.price !== 0)" class="text-red-500 text-[9px] font-bold mt-1 text-right">ભાવ જરૂરી</p>
                                        </div>
                                    </div>
                                    <div class="col-span-2 text-right">
                                        <div class="md:hidden text-[10px] font-bold text-text-secondary uppercase mb-1">કુલ</div>
                                        <span class="text-base font-black text-text-primary tracking-tight" x-text="(item.quantity * item.price).toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                                        <button type="button" @click="removeItem(index)" 
                                                class="ml-2 w-7 h-7 rounded-lg bg-red-100 text-red-600 flex md:absolute md:-right-2 md:top-1/2 md:-translate-y-1/2 md:opacity-0 md:group-hover:opacity-100 md:group-hover:right-2 items-center justify-center transition-all hover:bg-red-600 hover:text-white active:scale-90">
                                            <span class="material-symbols-outlined text-[16px]">close</span>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Footer Actions -->
                        <div class="p-6 bg-background/30 border-t border-border-light/50 flex flex-col md:flex-row justify-between items-center gap-4">
                            <button type="button" @click="addItem()" class="flex items-center gap-2 text-primary hover:text-primary-dark font-bold text-xs uppercase tracking-widest transition-all group active:scale-95">
                                <span class="material-symbols-outlined text-[20px] bg-primary/10 rounded-full p-1 text-primary group-hover:bg-primary group-hover:text-white transition-all">add</span>
                                <span class="gujarati-text underline underline-offset-4">વધુ આઈટમ ઉમેરો (Add Item)</span>
                            </button>
                            <div class="flex items-center gap-4 text-xs font-bold text-text-secondary">
                                <span x-text="items.length + ' આઈટમ્સ કુલ'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="card-surface p-6 border-border-light/30">
                            <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-3 block opacity-60">મોટી નોંધ (Extra Notes)</label>
                            <textarea name="notes" class="block w-full rounded-lg border-border-light shadow-sm focus:border-primary focus:ring-1 focus:ring-primary/20 bg-white p-4 text-xs font-bold gujarati-text placeholder-text-secondary/20 resize-none" 
                                      placeholder="કોઈ ખાસ સૂચના લખો..." rows="4">{{ $invoice->notes }}</textarea>
                        </div>
                        <div class="card-surface p-6 space-y-3 bg-white border-primary/10">
                            <div class="flex justify-between items-center px-2">
                                <span class="text-text-secondary text-xs uppercase font-bold opacity-60">Subtotal</span>
                                <span class="font-bold text-sm tracking-tight" x-text="'₹ ' + subtotal.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
                            </div>
                            <div class="flex justify-between items-center px-4 py-3 bg-red-50/50 rounded-lg border border-red-100/50">
                                <span class="text-red-700 text-xs font-bold uppercase">Discount</span>
                                <div class="flex items-center gap-2">
                                    <input type="number" x-model.number="discount" class="w-20 h-8 border-none bg-white rounded shadow-sm text-center text-xs font-bold text-red-600 focus:ring-1 focus:ring-red-200">
                                    <span class="font-bold text-xs text-red-700" x-text="'- ₹' + discount.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
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
                                    <p class="text-[10px] uppercase font-bold text-text-secondary opacity-40 leading-none mb-1">Final Amount</p>
                                    <p class="text-3xl font-black text-primary tracking-tighter leading-none" x-text="'₹ ' + grandTotal.toLocaleString('en-IN', {minimumFractionDigits: 2})"></p>
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
    </div>

    @push('footer')
    <!-- Sticky Footer -->
    <footer class="fixed bottom-0 md:left-64 left-0 right-0 bg-white border-t border-border-light px-4 md:px-8 py-4 md:py-5 flex flex-col md:flex-row justify-between items-center z-50 shadow-[0_-10px_30px_rgba(0,0,0,0.03)] transition-all duration-300">
        <div class="flex items-center justify-between w-full md:w-auto gap-8 mb-4 md:mb-0">
            <div class="flex flex-col">
                <span class="text-[10px] uppercase font-bold text-text-secondary opacity-60 leading-none mb-1">Net Payable</span>
                <span class="text-2xl font-black text-primary tracking-tighter leading-tight" x-text="'₹ ' + grandTotal.toLocaleString('en-IN', {minimumFractionDigits: 2})"></span>
            </div>
            <div class="h-8 w-[1px] bg-border-light hidden md:block"></div>
            <p class="text-[10px] font-bold text-text-secondary gujarati-text opacity-50 hidden lg:block">સરનામું અને સંપર્ક વિગતો આપોઆપ <br>બિલીંગ પર છપાશે.</p>
        </div>
        <div class="flex gap-2 md:gap-4 w-full md:w-auto">
            <button type="button" @click="window.print()" class="secondary-btn flex-1 md:flex-none text-[10px] md:text-xs uppercase tracking-widest px-4 md:px-8 cursor-pointer py-3">
                <span class="material-symbols-outlined text-[18px]">print</span>
                <span class="hidden sm:inline">પ્રિન્ટ </span>(PRINT)
            </button>
            <button type="submit" form="invoiceForm" :disabled="isSubmitting" :class="{'opacity-75 cursor-wait': isSubmitting}" class="primary-btn flex-1 md:flex-none text-[10px] md:text-xs uppercase tracking-widest px-4 md:px-8 shadow-lg shadow-primary/20 py-3 flex items-center justify-center gap-2">
                <template x-if="!isSubmitting">
                    <span class="material-symbols-outlined text-[18px]">task_alt</span>
                </template>
                <template x-if="isSubmitting">
                    <div class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                </template>
                <span class="hidden sm:inline" x-text="isSubmitting ? 'સુધરી રહ્યું છે...' : 'સુધારો '"></span>
                <span x-show="!isSubmitting">(UPDATE)</span>
            </button>
        </div>
    </footer>
    @endpush

    <script>
        function invoiceSystem() {
            return {
                availableProducts: @json($products),
                gstSettings: @json($gstSettings),
                items: @json($invoice->items),
                subtotal: {{ $invoice->subtotal }},
                discount: {{ $invoice->discount }},
                gstAmount: 0,
                cgstAmount: 0,
                sgstAmount: 0,
                grandTotal: {{ $invoice->total }},

                // Customer Autocomplete State
                customerName: {!! json_encode(old('customer_name', $invoice->customer_name)) !!},
                customerPhone: {!! json_encode(old('phone', $invoice->phone)) !!},
                customerAddress: {!! json_encode(old('address', $invoice->address)) !!},
                customers: [],
                showCustomerDropdown: false,
                isSearching: false,
                searchError: false,
                selectedIndex: -1,
                attemptedSubmit: false,
                isSubmitting: false,

                init() {
                    let rawItems = @json(old('items'));
                    if (!rawItems || rawItems.length === 0) {
                        rawItems = @json($invoice->items);
                    }

                    this.items = rawItems.map(item => ({
                        product_id: '',
                        product_name: item.product_name || '',
                        height: item.height || '',
                        bag_size: item.bag_size || '',
                        quantity: item.quantity || 1,
                        price: item.price || 0,
                        availableVariants: []
                    }));

                    this.subtotal = {{ old('subtotal', $invoice->subtotal) }};
                    this.discount = {{ old('discount', $invoice->discount) }};
                    this.gstAmount = {{ old('gst_amount', $invoice->gst_amount) }};
                    this.grandTotal = {{ old('total', $invoice->total) }};
                    // Try to match existing items with available products to load variants
                    this.items.forEach((item, index) => {
                        const product = this.availableProducts.find(p => p.name === item.product_name);
                        if (product) {
                            item.product_id = product.id;
                            item.availableVariants = product.variants;
                            const variant = product.variants.find(v => v.height === item.height && v.bag_size === item.bag_size);
                            if (variant) item.variant_id = variant.id;
                        }
                    });
                    
                    this.$watch('items', () => this.calculate(), { deep: true });
                    this.$watch('discount', () => this.calculate());
                },

                addItem() {
                    this.items.push({
                        product_id: '',
                        product_name: '',
                        height: '',
                        bag_size: '',
                        quantity: 1,
                        price: 0,
                        availableVariants: []
                    });
                    setTimeout(() => {
                        this.focusNext(this.items.length - 1, 'name');
                    }, 50);
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
                    this.subtotal = this.items.reduce((sum, item) => sum + (item.quantity * (item.price || 0)), 0);
                    
                    // Prevent discount from being negative or exceeding subtotal
                    if (this.discount < 0) this.discount = 0;
                    if (this.discount > this.subtotal) this.discount = this.subtotal;

                    const amountToTax = this.subtotal - this.discount;
                    
                    if (this.gstSettings.enabled && this.gstSettings.percentage > 0) {
                        if (this.gstSettings.type === 'inclusive') {
                            this.gstAmount = amountToTax - (amountToTax / (1 + (this.gstSettings.percentage / 100)));
                            this.grandTotal = amountToTax;

                            const basePrice = amountToTax / (1 + (this.gstSettings.percentage / 100));
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
                },

                focusNext(index, field) {
                    const currentItem = this.items[index];
                    if (field === 'qty') document.getElementById('qty-' + index)?.focus();
                    if (field === 'price') document.getElementById('price-' + index)?.focus();
                    if (field === 'name') document.getElementById('name-' + index)?.focus();
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

                    try {
                        const response = await fetch(`/api/customers/search?query=${encodeURIComponent(this.customerName)}`);
                        if (!response.ok) throw new Error('API Error');
                        this.customers = await response.json();
                    } catch (error) {
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
