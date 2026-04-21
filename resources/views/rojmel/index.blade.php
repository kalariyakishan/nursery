<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter">ડેઈલી રોકડમેળ (Rojmel)</h2>
            <p class="text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em] mt-1">Daily Cash Book & Transaction Ledger</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('rojmel.report') }}" class="bg-white text-primary px-6 py-3 rounded-lg border border-primary/10 font-bold text-sm flex items-center gap-2 hover:bg-primary/5 transition-all">
                <span class="material-symbols-outlined text-[18px]">analytics</span>
                રીપોર્ટ જુઓ
            </a>
            <a href="{{ route('rojmel.pdf', ['date' => $date]) }}" class="bg-red-50 text-red-600 px-6 py-3 rounded-lg font-bold text-sm flex items-center gap-2 hover:bg-red-600 hover:text-white transition-all">
                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                PDF ડાઉનલોડ
            </a>
        </div>
    </div>

    <!-- Date Selector -->
    <div class="card-surface p-4 shadow-sm mb-8 flex justify-between items-center">
        <form id="dateForm" action="{{ route('rojmel.index') }}" method="GET" class="flex items-center gap-4">
            <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="input-field py-2 font-bold text-sm">
            <span class="gujarati-text font-bold text-text-secondary text-xs">તારીખ મુજબ વિગત</span>
        </form>
        <div class="hidden md:flex gap-4">
            <button onclick="document.querySelector('#amount-input').focus()" class="text-[10px] font-bold text-primary uppercase tracking-widest border-b border-primary/20 pb-1">ત્વરિત એન્ટ્રી (Enter Key to Save)</button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card-surface p-6 border-l-4 border-blue-400">
            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">શરૂઆતનું બેલેન્સ (Opening)</span>
            <h3 class="text-2xl font-black text-text-primary">₹ {{ number_format($stats->opening_balance ?? 0, 2) }}</h3>
        </div>
        <div class="card-surface p-6 border-l-4 border-green-500">
            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">કુલ આવક (Avak)</span>
            <h3 class="text-2xl font-black text-green-600">+ ₹ {{ number_format($stats->total_avak ?? 0, 2) }}</h3>
        </div>
        <div class="card-surface p-6 border-l-4 border-red-500">
            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">કુલ જાવક (Javak)</span>
            <h3 class="text-2xl font-black text-red-600">- ₹ {{ number_format($stats->total_javak ?? 0, 2) }}</h3>
        </div>
        <div class="card-surface p-6 bg-primary text-white border-none shadow-xl shadow-primary/30">
            <span class="text-[10px] font-bold text-white/60 uppercase tracking-widest block mb-1 uppercase">આજનું બેલેન્સ (Closing)</span>
            <h3 class="text-2xl font-black">₹ {{ number_format($stats->closing_balance ?? 0, 2) }}</h3>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Entry Form -->
        <div class="lg:col-span-1">
            <div class="card-surface p-8 sticky top-8 shadow-premium">
                <h3 class="text-xl font-black text-text-primary gujarati-text mb-6">નવી એન્ટ્રી (New Entry)</h3>
                
                <form id="entryForm" action="{{ route('rojmel.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="hidden" name="date" value="{{ $date }}">
                    
                    <div>
                        <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-2 block">પ્રકાર (Transaction Type)</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="type" value="avak" checked class="peer sr-only">
                                <div class="w-full py-4 text-center rounded-xl border-2 border-border-light font-black text-xs uppercase tracking-widest transition-all
                                           peer-checked:border-green-500 peer-checked:bg-green-50 peer-checked:text-green-600 hover:bg-background">
                                    આવક (Avak)
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="type" value="javak" class="peer sr-only">
                                <div class="w-full py-4 text-center rounded-xl border-2 border-border-light font-black text-xs uppercase tracking-widest transition-all
                                           peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:text-red-600 hover:bg-background">
                                    જાવક (Javak)
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-2 block">રકમ (Amount)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 font-black text-text-secondary tracking-tighter">₹</span>
                            <input type="number" step="0.01" name="amount" id="amount-input" required autofocus
                                   class="input-field pl-10 py-4 text-2xl font-black text-primary placeholder:text-primary/20" placeholder="0.00">
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-2 block">કેટેગરી (Category)</label>
                        <input type="text" name="category" list="categories" class="input-field gujarati-text font-bold" placeholder="પસંદ કરો અથવા લખો...">
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
                        <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-2 block">વિગત (Description)</label>
                        <textarea name="description" rows="2" class="input-field gujarati-text font-bold" placeholder="એન્ટ્રી વિશે વધુ માહિતી..."></textarea>
                    </div>

                    <button type="submit" class="primary-btn w-full py-4 text-sm tracking-widest uppercase flex justify-center items-center gap-3">
                        <span class="material-symbols-outlined">save</span>
                        સેવ કરો (Save)
                    </button>
                    <p class="text-[9px] text-center text-text-secondary font-bold uppercase opacity-40">પ્રેસ એન્ટર મરવાથી પણ સેવ થશે</p>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="lg:col-span-2">
            <div class="card-surface shadow-premium overflow-hidden min-h-[500px]">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-background border-b border-border-light">
                                <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">સમય</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">વિગત</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">આવક (+)</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">જાવક (-)</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-center">એક્શન</th>
                            </tr>
                        </thead>
                        <tbody id="entryTableBody" class="divide-y divide-border-light/50">
                            @forelse($entries as $entry)
                                <tr class="hover:bg-primary/5 transition-colors group">
                                    <td class="px-6 py-4 font-bold text-text-secondary text-[11px] uppercase whitespace-nowrap">
                                        {{ $entry->created_at->format('h:i A') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-text-primary gujarati-text">{{ $entry->description ?: '-' }}</div>
                                        @if($entry->category)
                                            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full bg-background text-text-secondary border border-border-light">
                                                {{ $entry->category }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-green-600">
                                        {{ $entry->type == 'avak' ? '₹ ' . number_format($entry->amount, 2) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-red-600">
                                        {{ $entry->type == 'javak' ? '₹ ' . number_format($entry->amount, 2) : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center">
                                            <form action="{{ route('rojmel.destroy', $entry) }}" method="POST" onsubmit="return confirm('ખાતરી છે?')">
                                                @csrf
                                                @method('DELETE')
                                                <button title="Delete" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all">
                                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-24 text-center">
                                        <div class="flex flex-col items-center opacity-20">
                                            <span class="material-symbols-outlined text-6xl">list_alt</span>
                                            <p class="font-bold gujarati-text text-xl mt-4">આજે કોઈ વ્યવહાર થયો નથી.</p>
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

    @push('footer')
    <script>
        document.getElementById('entryForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('button[type="submit"]');
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">sync</span> સેવ થઈ રહ્યું છે...';

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
                    window.location.reload(); 
                } else {
                    alert(data.message || 'Error occurred');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span class="material-symbols-outlined">save</span> સેવ કરો (Save)';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Something went wrong. Please try again.');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span class="material-symbols-outlined">save</span> સેવ કરો (Save)';
            });
        });
    </script>
    @endpush
</x-app-layout>
