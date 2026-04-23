<x-app-layout>
    <!-- Header & Navigation -->
    <div class="mb-8 flex flex-col lg:flex-row lg:justify-between lg:items-end gap-6">
        <div>
            <h2 class="text-4xl font-black text-text-primary gujarati-text tracking-tighter">ડેઈલી રોકડમેળ (Rojmel)</h2>
            <p class="text-xs font-bold text-text-secondary uppercase tracking-[0.3em] mt-2 opacity-70">Daily Cash Book & Transaction Ledger</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-3">
             <div class="flex items-center bg-white rounded-2xl shadow-sm border border-border-light p-1.5">
                <a href="{{ route('rojmel.index', ['date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d')]) }}" 
                   class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-background transition-colors text-text-secondary" title="ગઈકાલ">
                    <span class="material-symbols-outlined">chevron_left</span>
                </a>
                
                <form action="{{ route('rojmel.index') }}" method="GET" class="px-2">
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" 
                           class="border-none bg-transparent font-black text-text-primary focus:ring-0 cursor-pointer text-sm py-1">
                </form>

                <a href="{{ route('rojmel.index', ['date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d')]) }}" 
                   class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-background transition-colors text-text-secondary" title="આવતીકાલ">
                    <span class="material-symbols-outlined">chevron_right</span>
                </a>
            </div>

            <a href="{{ route('rojmel.index', ['date' => date('Y-m-d')]) }}" 
               class="px-5 py-3 bg-white border border-border-light rounded-xl font-bold text-xs text-text-secondary hover:bg-primary/5 hover:text-primary hover:border-primary/20 transition-all shadow-sm flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">today</span>
                આજે (Today)
            </a>

            <a href="{{ route('rojmel.dashboard') }}" 
               class="px-5 py-3 bg-primary text-white rounded-xl font-bold text-xs shadow-premium-hover flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">analytics</span>
                રીપોર્ટ જુઓ
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Opening Balance -->
        <div class="card-surface p-6 relative overflow-hidden group border-b-4 border-blue-500">
            <span class="text-[10px] font-black text-text-secondary uppercase tracking-[0.2em] block mb-2 opacity-60">શરૂઆતનું બેલેન્સ</span>
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-black text-text-primary tracking-tighter">₹ {{ number_format($stats->opening_balance ?? 0, 2) }}</h3>
                <span class="material-symbols-outlined text-blue-500/20 text-4xl group-hover:scale-110 transition-transform">start</span>
            </div>
        </div>

        <!-- Total Credit (Income) -->
        <div class="card-surface p-6 relative overflow-hidden group border-b-4 border-green-500">
            <span class="text-[10px] font-black text-text-secondary uppercase tracking-[0.2em] block mb-2 opacity-60">કુલ આવક (જમા)</span>
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-black text-green-600 tracking-tighter">+ ₹ <span id="total-avak-display">{{ number_format($stats->total_avak ?? 0, 2) }}</span></h3>
                <span class="material-symbols-outlined text-green-500/20 text-4xl group-hover:scale-110 transition-transform">add_circle</span>
            </div>
        </div>

        <!-- Total Debit (Expense) -->
        <div class="card-surface p-6 relative overflow-hidden group border-b-4 border-red-500">
            <span class="text-[10px] font-black text-text-secondary uppercase tracking-[0.2em] block mb-2 opacity-60">કુલ જાવક (ઉધાર)</span>
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-black text-red-600 tracking-tighter">- ₹ <span id="total-javak-display">{{ number_format($stats->total_javak ?? 0, 2) }}</span></h3>
                <span class="material-symbols-outlined text-red-500/20 text-4xl group-hover:scale-110 transition-transform">remove_circle</span>
            </div>
        </div>

        <!-- Closing Balance -->
        <div class="card-surface p-6 relative overflow-hidden bg-primary/5 group border-b-4 border-primary shadow-xl shadow-primary/10">
            <span class="text-[10px] font-black text-primary uppercase tracking-[0.2em] block mb-2">આખર સિલક</span>
            <div class="flex items-center justify-between">
                <h3 class="text-2xl font-black text-primary tracking-tighter">₹ <span id="closing-balance-display">{{ number_format($stats->closing_balance ?? 0, 2) }}</span></h3>
                <span class="material-symbols-outlined text-primary/20 text-4xl group-hover:scale-110 transition-transform">account_balance_wallet</span>
            </div>
        </div>
    </div>

    <!-- Quick Entry & Transaction Table Row -->
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start mb-12">
        <!-- Input Form -->
        <div class="xl:col-span-4">
            <div class="card-surface p-8 shadow-premium border border-border-light overflow-hidden relative">
                <div class="absolute top-0 left-0 w-1 h-full bg-primary"></div>
                <h3 class="text-xl font-black text-text-primary gujarati-text mb-8 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">edit_square</span>
                    ઝડપી એન્ટ્રી (Quick Entry)
                </h3>
                
                <form id="entryForm" action="{{ route('rojmel.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    
                    <div>
                        <label class="text-[11px] font-black text-text-secondary uppercase tracking-widest mb-3 block">વ્યવહારનો પ્રકાર (Transaction Type)</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="type" value="avak" checked class="peer sr-only">
                                <div class="w-full py-4 text-center rounded-2xl border-2 border-border-light font-black text-xs transition-all
                                           peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-600 hover:bg-background group-active:scale-95">
                                    <div class="flex flex-col gap-1 items-center">
                                         <span class="material-symbols-outlined text-[18px]">south_east</span>
                                         આવક (જમા)
                                    </div>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="type" value="javak" class="peer sr-only">
                                <div class="w-full py-4 text-center rounded-2xl border-2 border-border-light font-black text-xs transition-all
                                           peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-600 hover:bg-background group-active:scale-95">
                                    <div class="flex flex-col gap-1 items-center">
                                         <span class="material-symbols-outlined text-[18px]">north_east</span>
                                         જાવક (ઉધાર)
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="text-[11px] font-black text-text-secondary uppercase tracking-widest mb-3 block">રકમ (Amount) <span class="text-red-500">*</span></label>
                        <div class="relative group">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 font-black text-text-secondary tracking-tighter text-xl group-focus-within:text-primary transition-colors">₹</span>
                            <input type="number" step="0.01" name="amount" id="amount-input" required autofocus
                                   class="input-field pl-12 py-5 text-3xl font-black text-primary placeholder:text-primary/10 border-2 focus:border-primary/50 transition-all rounded-2xl" placeholder="0.00">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-[11px] font-black text-text-secondary uppercase tracking-widest mb-3 block">કેટેગરી (Category) <span class="text-red-500">*</span></label>
                            <input type="text" name="category" list="categories" required class="input-field gujarati-text font-bold py-4 rounded-xl border-2 focus:border-primary/50" placeholder="કેટેગરી પસંદ કરો...">
                            <datalist id="categories">
                                <option value="વેચાણ (Sales)">
                                <option value="ખરીદી (Purchase)">
                                <option value="મજૂરી (Labour)">
                                <option value="વીજળી બિલ">
                                <option value="ભાડું">
                                <option value="ટાટા એઈસ ભાડું">
                            </datalist>
                        </div>

                        <div>
                            <label class="text-[11px] font-black text-text-secondary uppercase tracking-widest mb-3 block">વિગત (Particulars)</label>
                            <input type="text" name="description" id="desc-input" class="input-field gujarati-text font-bold py-4 rounded-xl" placeholder="વિગત લખો...">
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" id="save-btn" class="primary-btn w-full py-5 text-sm tracking-[0.2em] uppercase flex justify-center items-center gap-3 shadow-lg shadow-primary/25 rounded-2xl">
                            <span class="material-symbols-outlined">check_circle</span>
                            સેવ કરો (Save Entry)
                        </button>
                        <div class="mt-4 flex items-center justify-center gap-2 opacity-40">
                             <span class="px-1.5 py-0.5 rounded border border-text-secondary text-[9px] font-bold">ENTER</span>
                             <span class="text-[10px] font-black uppercase tracking-widest">સીધું સેવ કરવા માટે</span>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Instructions/Shortcuts Card -->
            <div class="mt-6 card-surface p-6 bg-background/50 border-dashed border-2 border-border-light">
                <h4 class="text-[10px] font-black text-text-primary uppercase tracking-[0.3em] mb-4">ઝડપી એન્ટ્રીની રીત</h4>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2 text-xs font-bold text-text-secondary">
                        <span class="material-symbols-outlined text-green-500 text-[16px] mt-0.5">check_circle</span>
                        રકમ લખીને Enter મારતા જ એન્ટ્રી સેવ થઈ જશે.
                    </li>
                    <li class="flex items-start gap-2 text-xs font-bold text-text-secondary">
                        <span class="material-symbols-outlined text-blue-500 text-[16px] mt-0.5">info</span>
                        કેટેગરી માટે લિસ્ટમાંથી પસંદ કરો અથવા નવી ટાઇપ કરો.
                    </li>
                </ul>
            </div>
        </div>

        <!-- Transaction Table -->
        <div class="xl:col-span-8">
            <div class="card-surface shadow-premium overflow-hidden border border-border-light min-h-[600px] flex flex-col">
                <div class="p-6 border-b border-border-light bg-background/40 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <h3 class="text-xl font-black text-text-primary gujarati-text tracking-tighter">આજના વ્યવહાર (Transactions)</h3>
                        <span class="px-3 py-1 bg-primary/10 text-primary text-[10px] font-black rounded-full uppercase tracking-widest" id="transaction-count">{{ count($entries) }} એન્ટ્રી</span>
                    </div>
                    
                    <div class="relative w-full md:w-64">
                         <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary text-[20px] opacity-50">search</span>
                         <input type="text" id="transactionSearch" placeholder="સર્ચ કરો (વિગત, રકમ)..." 
                                class="w-full pl-10 pr-4 py-2.5 bg-white border border-border-light rounded-xl text-xs font-bold focus:ring-2 focus:ring-primary/20 transition-all">
                    </div>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse" id="rojmel-table">
                        <thead>
                            <tr class="bg-background/80 text-[10px] font-black text-text-secondary uppercase tracking-[0.2em]">
                                <th class="px-6 py-4">સમય</th>
                                <th class="px-6 py-4">વિગત / કેટેગરી</th>
                                <th class="px-6 py-4 text-right">આવક (જમા)</th>
                                <th class="px-6 py-4 text-right">જાવક (ઉધાર)</th>
                                <th class="px-6 py-4 text-right">સિલક (Balance)</th>
                                <th class="px-6 py-4 text-center"></th>
                            </tr>
                        </thead>
                        <tbody id="entryTableBody" class="divide-y divide-border-light/40">
                            @php $runningBalance = $stats->opening_balance ?? 0; @endphp
                            @forelse($entries as $entry)
                                @php 
                                    if($entry->type == 'avak') $runningBalance += $entry->amount;
                                    else $runningBalance -= $entry->amount;
                                @endphp
                                <tr class="hover:bg-primary/5 transition-all group entry-row animate-in fade-in slide-in-from-left-4 duration-300" 
                                    data-id="{{ $entry->id }}" 
                                    data-search="{{ strtolower($entry->description . ' ' . $entry->category . ' ' . $entry->amount) }}">
                                    <td class="px-6 py-5 whitespace-nowrap text-[10px] font-black text-text-secondary opacity-60">
                                        {{ $entry->created_at->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="font-bold text-text-primary gujarati-text leading-tight">{{ $entry->description ?: '-' }}</div>
                                        @if($entry->category)
                                            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md bg-background border border-border-light text-text-secondary mt-1 inline-block">
                                                {{ $entry->category }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-5 text-right font-black text-green-600 text-sm">
                                        {{ $entry->type == 'avak' ? '₹ ' . number_format($entry->amount, 2) : '-' }}
                                    </td>
                                    <td class="px-6 py-5 text-right font-black text-red-600 text-sm">
                                        {{ $entry->type == 'javak' ? '₹ ' . number_format($entry->amount, 2) : '-' }}
                                    </td>
                                    <td class="px-6 py-5 text-right font-black text-text-primary">
                                        <div class="inline-block px-3 py-1 rounded-lg bg-background border border-border-light">
                                            ₹ {{ number_format($runningBalance, 2) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex justify-center">
                                            <button onclick="deleteEntry({{ $entry->id }})" 
                                                    class="w-9 h-9 rounded-xl bg-red-50 text-red-600 opacity-0 group-hover:opacity-100 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                                <span class="material-symbols-outlined text-[18px]">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="empty-row">
                                    <td colspan="6" class="px-6 py-32 text-center">
                                        <div class="flex flex-col items-center opacity-10">
                                            <span class="material-symbols-outlined text-8xl">receipt_long</span>
                                            <p class="font-black gujarati-text text-2xl mt-6 tracking-widest">આજે કોઈ વ્યવહાર થયો નથી.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Hind+Vadodara:wght@300;400;500;600;700&display=swap');
        .gujarati-text { font-family: 'Hind Vadodara', sans-serif; }
        .shadow-premium { box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.05), 0 10px 20px -15px rgba(0, 0, 0, 0.05); }
        .shadow-premium-hover:hover { box-shadow: 0 25px 50px -12px rgba(var(--primary-rgb), 0.3); transform: translateY(-2px); }
    </style>
    @endpush

    @push('footer')
    <script>
        // 1. AJAX Store Logic
        document.getElementById('entryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('save-btn');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[20px]">sync</span> સેવ થઈ રહ્યું છે...';

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Quick feedback
                    window.location.reload(); // Simple reload for consistent balance calculation
                } else {
                    alert(data.message || 'Error occurred');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span class="material-symbols-outlined">check_circle</span> સેવ કરો (Save Entry)';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span class="material-symbols-outlined">check_circle</span> સેવ કરો (Save Entry)';
            });
        });

        // 2. AJAX Delete Logic
        function deleteEntry(id) {
            if (!confirm('શું તમે ખરેખર આ એન્ટ્રી રદ કરવા માંગો છો?')) return;

            // Form dynamic request
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/rojmel/${id}`;
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            
            form.appendChild(methodInput);
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }

        // 3. Search Logic
        document.getElementById('transactionSearch').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.entry-row');
            
            rows.forEach(row => {
                const text = row.getAttribute('data-search');
                if (text.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // 4. Focus trigger on Enter in Amount
        document.getElementById('amount-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                // If only amount is entered, we can save. If they want to add note, they can tab.
                // To keep it lightning fast, let's just trigger submit.
                // document.getElementById('entryForm').dispatchEvent(new Event('submit'));
            }
        });
    </script>
    @endpush
</x-app-layout>
