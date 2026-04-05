<aside class="h-screen w-64 fixed left-0 top-0 bg-white border-r border-border-light flex flex-col z-50 overflow-y-auto transition-transform duration-300 transform md:translate-x-0"
       :class="sidebarOpen ? 'translate-x-0 shadow-2xl shadow-black/20' : '-translate-x-full'">
    <div class="h-20 flex items-center justify-between px-6 border-b border-border-light/50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center text-white shadow shadow-primary/20 shrink-0">
                <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">potted_plant</span>
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
        
        <a href="{{ route('invoices.create') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('invoices.create') ? 'bg-primary text-white shadow-md font-semibold' : 'text-text-secondary hover:bg-background hover:text-primary' }}">
            <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('invoices.create') ? "font-variation-settings: 'FILL' 1;" : '' }}">receipt_long</span>
            <span class="gujarati-text text-sm">નવું ઇન્વોઇસ</span>
        </a>

        <a href="{{ route('invoices.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('invoices.index') ? 'bg-primary text-white shadow-md font-semibold' : 'text-text-secondary hover:bg-background hover:text-primary' }}">
            <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('invoices.index') ? "font-variation-settings: 'FILL' 1;" : '' }}">history</span>
            <span class="gujarati-text text-sm">ઇન્વોઇસ હિસ્ટ્રી</span>
        </a>
        
        <a href="{{ route('products.index') }}" 
           class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('products.*') ? 'bg-primary text-white shadow-md font-semibold' : 'text-text-secondary hover:bg-background hover:text-primary' }}">
            <span class="material-symbols-outlined text-[20px]" style="{{ request()->routeIs('products.*') ? "font-variation-settings: 'FILL' 1;" : '' }}">inventory_2</span>
            <span class="gujarati-text text-sm">પ્રોડક્ટ મેનેજમેન્ટ</span>
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
