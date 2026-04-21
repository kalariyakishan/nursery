<aside class="h-screen w-64 fixed left-0 top-0 bg-white border-r border-border-light flex flex-col z-50 overflow-y-auto transition-transform duration-300 transform md:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0 shadow-2xl shadow-black/20' : '-translate-x-full'">
    <div class="h-20 flex items-center justify-between px-6 border-b border-border-light/50">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-lg bg-white border border-border-light shadow-sm flex items-center justify-center p-1.5 shrink-0 overflow-hidden group hover:rotate-3 transition-transform duration-300">
                <img src="{{ asset('images/logo.png') }}" class="w-full h-full object-contain" alt="New Vrundavan Nursery Logo">
            </div>
            <div class="min-w-0">
                <h1 class="text-sm font-bold text-text-primary gujarati-text leading-tight truncate">New Vrundavan Nursery</h1>
                <p class="text-[9px] font-semibold text-text-secondary uppercase tracking-[0.15em] opacity-40">Digital Billing System</p>
            </div>
        </div>
        <!-- Close button on Mobile -->
        <button @click="sidebarOpen = false" class="md:hidden text-text-secondary p-1 border border-border-light rounded-lg">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    </div>
    
    <nav class="flex-1 px-4 py-2 space-y-1">
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-primary text-white shadow-md font-semibold' : 'text-text-secondary hover:bg-background hover:text-primary' }}">
            <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('dashboard') ? "font-variation-settings: 'FILL' 1;" : '' }}">dashboard</span>
            <span class="gujarati-text text-sm">ડેશબોર્ડ</span>
        </a>
        
        <!-- Invoice Management Dropdown -->
        <div x-data="{ invoiceOpen: {{ request()->routeIs('invoices.*') ? 'true' : 'false' }} }">
            <button @click="invoiceOpen = !invoiceOpen" 
                class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('invoices.*') ? 'bg-background text-primary font-bold' : 'text-text-secondary hover:bg-background hover:text-primary' }}">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('invoices.*') ? "font-variation-settings: 'FILL' 1;" : '' }}">receipt_long</span>
                    <span class="gujarati-text text-sm">ઇન્વોઇસ મેનેજમેન્ટ</span>
                </div>
                <span class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="invoiceOpen ? 'rotate-180' : ''">expand_more</span>
            </button>
            
            <div x-show="invoiceOpen" 
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 -translate-y-2" 
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="ml-10 mt-1 border-l border-border-light/60 pl-2">
                
                <a href="{{ route('invoices.create') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm transition-all {{ request()->routeIs('invoices.create') ? 'text-primary font-bold bg-primary/10' : 'text-text-secondary hover:text-primary hover:bg-background' }}">
                    <span class="gujarati-text">નવું ઇન્વોઇસ બનાવો</span>
                </a>
                
                <a href="{{ route('invoices.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm transition-all {{ request()->routeIs('invoices.index') ? 'text-primary font-bold bg-primary/10' : 'text-text-secondary hover:text-primary hover:bg-background' }}">
                    <span class="gujarati-text">ઇન્વોઇસ હિસ્ટ્રી</span>
                </a>
            </div>
        </div>
        
        <a href="{{ route('products.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-primary text-white shadow-md font-semibold' : 'text-text-secondary hover:bg-background hover:text-primary' }}">
            <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('products.*') ? "font-variation-settings: 'FILL' 1;" : '' }}">inventory_2</span>
            <span class="gujarati-text text-sm">પ્રોડક્ટ મેનેજમેન્ટ</span>
        </a>

        <a href="{{ route('rojmel.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('rojmel.*') ? 'bg-primary text-white shadow-md font-semibold' : 'text-text-secondary hover:bg-background hover:text-primary' }}">
            <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('rojmel.*') ? "font-variation-settings: 'FILL' 1;" : '' }}">account_balance_wallet</span>
            <span class="gujarati-text text-sm">ડેઈલી રોકડમેળ (Rojmel)</span>
        </a>

        <!-- Labour Management Dropdown -->
        <div x-data="{ labourOpen: {{ request()->routeIs('labour-entries.*', 'advances.*', 'workers.*', 'reports.labour*') ? 'true' : 'false' }} }">
            <button @click="labourOpen = !labourOpen" 
                class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('labour-entries.*', 'advances.*', 'workers.*', 'reports.labour*') ? 'bg-background text-primary font-bold' : 'text-text-secondary hover:bg-background hover:text-primary' }}">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('labour-entries.*', 'advances.*', 'workers.*', 'reports.labour*') ? "font-variation-settings: 'FILL' 1;" : '' }}">engineering</span>
                    <span class="gujarati-text text-sm">મજૂરી મેનેજમેન્ટ</span>
                </div>
                <span class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="labourOpen ? 'rotate-180' : ''">expand_more</span>
            </button>
            
            <div x-show="labourOpen" 
                 x-transition:enter="transition ease-out duration-200" 
                 x-transition:enter-start="opacity-0 -translate-y-2" 
                 x-transition:enter-end="opacity-100 translate-y-0"
                 class="ml-10 mt-1 border-l border-border-light/60 pl-2">
                
                <a href="{{ route('labour-entries.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm transition-all {{ request()->routeIs('labour-entries.*') ? 'text-primary font-bold bg-primary/10' : 'text-text-secondary hover:text-primary hover:bg-background' }}">
                    <span class="gujarati-text">ડેઈલી હજરી એન્ટ્રી</span>
                </a>
                
                <a href="{{ route('advances.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm transition-all {{ request()->routeIs('advances.*') ? 'text-red-600 font-bold bg-red-50' : 'text-text-secondary hover:text-red-600 hover:bg-background' }}">
                    <span class="gujarati-text">મજૂર ઉપાડ (Upad)</span>
                </a>
                
                <a href="{{ route('workers.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm transition-all {{ request()->routeIs('workers.*') ? 'text-primary font-bold bg-primary/10' : 'text-text-secondary hover:text-primary hover:bg-background' }}">
                    <span class="gujarati-text">મજૂર લિસ્ટ</span>
                </a>
                
                <a href="{{ route('reports.labour') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm transition-all {{ request()->routeIs('reports.labour') ? 'text-primary font-bold bg-primary/10' : 'text-text-secondary hover:text-primary hover:bg-background' }}">
                    <span class="gujarati-text">સેટલમેન્ટ રીપોર્ટ</span>
                </a>
                
                <a href="{{ route('settlements.index') }}" 
                   class="flex items-center px-4 py-2.5 rounded-lg text-sm transition-all {{ request()->routeIs('settlements.*') ? 'text-primary font-bold bg-primary/10' : 'text-text-secondary hover:text-primary hover:bg-background' }}">
                    <span class="gujarati-text">પગાર પતાવટ (History)</span>
                </a>
            </div>
        </div>

        <a href="{{ route('settings.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('settings.*') ? 'bg-primary text-white shadow-md font-semibold' : 'text-text-secondary hover:bg-background hover:text-primary' }}">
            <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('settings.*') ? "font-variation-settings: 'FILL' 1;" : '' }}">settings</span>
            <span class="gujarati-text text-sm">સેટિંગ્સ</span>
        </a>
    </nav>
    
    <div class="p-4 border-t border-border-light">
        <div class="flex items-center gap-3 px-4 py-3 mb-2 rounded-lg bg-background/50">
            <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-text-secondary">Administrator</p>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-red-600 hover:bg-red-50 transition-colors rounded-lg text-sm font-semibold group">
                <span class="material-symbols-outlined text-[20px] group-hover:rotate-12 transition-transform">logout</span>
                <span class="gujarati-text">લૉગ આઉટ</span>
            </button>
        </form>
    </div>
</aside>
