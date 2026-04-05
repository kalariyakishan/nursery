<header class="fixed top-0 right-0 md:left-64 left-0 h-20 bg-white border-b border-border-light z-40 flex justify-between items-center px-4 md:px-8 shadow-subtle transition-all duration-300">
    <div class="flex items-center gap-4 flex-1">
        <!-- Toggle Menu for Mobile -->
        <button @click="sidebarOpen = true" class="md:hidden p-2 text-text-primary hover:bg-background rounded-lg transition-colors cursor-pointer">
            <span class="material-symbols-outlined text-[24px]">menu</span>
        </button>

        <div class="relative w-full max-w-xs md:max-w-md group hidden sm:block" 
             x-data="{ 
                query: '', 
                results: [], 
                loading: false,
                async search() {
                    if (this.query.length < 2) { this.results = []; return; }
                    this.loading = true;
                    try {
                        const res = await fetch(`/api/search?q=${this.query}`);
                        this.results = await res.json();
                    } catch (e) { console.error(e); }
                    this.loading = false;
                }
             }" 
             @click.away="query = ''; results = []">
            
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary text-sm group-focus-within:text-primary transition-colors">search</span>
            
            <input class="w-full bg-background border-border-light rounded-lg py-2 pl-10 pr-4 text-sm focus:border-primary focus:ring focus:ring-primary/10 transition-all font-sans placeholder-text-secondary/40" 
                   placeholder="ગ્રાહક અથવા બિલ નંબર શોધો..." 
                   type="text"
                   x-model="query"
                   @input.debounce.300ms="search()"/>

            <!-- Search Results Dropdown -->
            <div class="absolute top-full left-0 w-full mt-2 bg-white rounded-xl shadow-2xl border border-primary/10 overflow-hidden z-50"
                 x-show="results.length > 0"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;">
                
                <div class="max-h-80 overflow-y-auto">
                    <template x-for="item in results" :key="item.id">
                        <a :href="`/invoices/${item.id}`" class="flex items-center justify-between p-4 hover:bg-primary/5 transition-colors border-b border-border-light/50 last:border-0 group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary font-black text-[10px]">
                                    #<span x-text="item.id"></span>
                                </div>
                                <div>
                                    <p class="font-black gujarati-text text-primary-dark group-hover:text-primary leading-none" x-text="item.customer_name"></p>
                                    <p class="text-[10px] text-text-secondary mt-1 font-bold">બિલ જોવા માટે ક્લિક કરો</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-primary text-xs">₹ <span x-text="parseFloat(item.total).toLocaleString()"></span></p>
                            </div>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex items-center gap-2 md:gap-4">
        <div class="flex gap-1 md:gap-2 pr-2 md:pr-4 border-r border-border-light">
            <button class="w-9 h-9 flex items-center justify-center rounded-lg text-text-secondary hover:bg-background hover:text-primary transition-colors cursor-pointer"><span class="material-symbols-outlined text-[20px]">notifications</span></button>
            <button class="w-9 h-9 flex items-center justify-center rounded-lg text-text-secondary hover:bg-background hover:text-primary transition-colors cursor-pointer hidden md:flex"><span class="material-symbols-outlined text-[20px]">settings</span></button>
        </div>
        
        <div class="flex items-center gap-3 cursor-pointer p-1 rounded-lg transition-all duration-200">
            <div class="text-right hidden lg:block">
                <p class="text-xs font-bold text-text-primary gujarati-text leading-none">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-text-secondary font-semibold uppercase tracking-wider leading-none mt-1">Admin</p>
            </div>
            <div class="w-9 h-9 rounded-lg bg-primary-light/10 text-primary flex items-center justify-center border border-primary/10 shadow-sm font-bold">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </div>
    </div>
</header>
