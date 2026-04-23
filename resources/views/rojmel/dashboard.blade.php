<x-app-layout>
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-6">
        <div>
            <h2 class="text-4xl font-black text-text-primary gujarati-text tracking-tighter">રોજમેળ ડેશબોર્ડ</h2>
            <p class="text-xs font-bold text-text-secondary uppercase tracking-[0.3em] mt-2 opacity-70">Accounting Analytics & Insights</p>
        </div>
        <div class="flex items-center gap-4">
            <form action="{{ route('rojmel.dashboard') }}" method="GET" class="flex items-center gap-3 bg-white p-2 rounded-2xl shadow-sm border border-border-light">
                <span class="material-symbols-outlined text-text-secondary ml-2">calendar_today</span>
                <select name="year" onchange="this.form.submit()" class="bg-transparent border-none font-bold text-text-primary focus:ring-0 cursor-pointer pr-8">
                    @for($y = date('Y'); $y >= 2020; $y--)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
            <a href="{{ route('rojmel.excel', ['year' => $year]) }}" class="bg-green-50 text-green-600 px-6 py-3 rounded-lg font-bold text-sm flex items-center gap-2 hover:bg-green-600 hover:text-white transition-all">
                <span class="material-symbols-outlined text-[18px]">description</span>
                આખા વર્ષનું Excel
            </a>
            <a href="{{ route('rojmel.index') }}" class="primary-btn flex items-center gap-2 px-6 shadow-premium-hover">
                <span class="material-symbols-outlined text-[20px]">add_circle</span>
                નવી એન્ટ્રી
            </a>
        </div>
    </div>

    <!-- Top Highlight Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
        <div class="card-surface p-8 relative overflow-hidden group hover:scale-[1.02] transition-all duration-500 border-b-4 border-green-500">
            <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-[80px] text-green-600">trending_up</span>
            </div>
            <span class="text-[11px] font-black text-text-secondary uppercase tracking-[0.2em] block mb-2">કુલ આવક ({{ $year }})</span>
            <h3 class="text-3xl font-black text-green-600 mb-1">₹ {{ number_format($totalYearlyIncome, 2) }}</h3>
            <div class="w-full bg-green-100 h-1.5 rounded-full mt-4 overflow-hidden">
                <div class="bg-green-500 h-full rounded-full" style="width: 100%"></div>
            </div>
        </div>

        <div class="card-surface p-8 relative overflow-hidden group hover:scale-[1.02] transition-all duration-500 border-b-4 border-red-500">
            <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-[80px] text-red-600">trending_down</span>
            </div>
            <span class="text-[11px] font-black text-text-secondary uppercase tracking-[0.2em] block mb-2">કુલ જાવક ({{ $year }})</span>
            <h3 class="text-3xl font-black text-red-600 mb-1">₹ {{ number_format($totalYearlyExpense, 2) }}</h3>
            <div class="w-full bg-red-100 h-1.5 rounded-full mt-4 overflow-hidden">
                <div class="bg-red-500 h-full rounded-full" style="width: {{ $totalYearlyIncome > 0 ? min(100, ($totalYearlyExpense / $totalYearlyIncome) * 100) : 0 }}%"></div>
            </div>
        </div>

        <div class="card-surface p-8 bg-primary/5 relative overflow-hidden group hover:scale-[1.02] transition-all duration-500 border-b-4 border-primary">
            <div class="absolute top-0 right-0 p-6 opacity-10 group-hover:opacity-20 transition-opacity">
                <span class="material-symbols-outlined text-[80px] text-primary">account_balance_wallet</span>
            </div>
            <span class="text-[11px] font-black text-text-secondary uppercase tracking-[0.2em] block mb-2">હાલની સિલક (Current)</span>
            <h3 class="text-3xl font-black text-primary mb-1">₹ {{ number_format($currentBalance, 2) }}</h3>
            <p class="text-[10px] font-bold text-text-secondary mt-4 flex items-center gap-1">
                <span class="material-symbols-outlined text-[14px]">info</span>
                ઓટોમેટિક ગણતરી મુજબ
            </p>
        </div>
    </div>

    <!-- Monthly Summary and Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start mb-12">
        <!-- Table -->
        <div class="lg:col-span-8">
            <div class="card-surface shadow-premium overflow-hidden border border-border-light">
                <div class="p-6 border-b border-border-light bg-background/50 flex justify-between items-center">
                    <h3 class="text-xl font-black text-text-primary gujarati-text tracking-tighter">માસિક સારાંશ - {{ $year }}</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-background">
                                <th class="px-6 py-4 text-[11px] font-black text-text-secondary uppercase tracking-widest">ક્રમ</th>
                                <th class="px-6 py-4 text-[11px] font-black text-text-secondary uppercase tracking-widest">માસનું નામ</th>
                                <th class="px-6 py-4 text-right text-[11px] font-black text-text-secondary uppercase tracking-widest">આવક (+)</th>
                                <th class="px-6 py-4 text-right text-[11px] font-black text-text-secondary uppercase tracking-widest">જાવક (-)</th>
                                <th class="px-6 py-4 text-right text-[11px] font-black text-text-secondary uppercase tracking-widest">આખર સિલક</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light/50">
                            @foreach($formattedMonthlyData as $data)
                                @if($data['income'] > 0 || $data['expense'] > 0 || $data['month_num'] <= date('m') || $year < date('Y'))
                                <tr class="hover:bg-primary/5 transition-all group">
                                    <td class="px-6 py-5 font-bold text-text-secondary opacity-50">{{ $data['month_num'] }}</td>
                                    <td class="px-6 py-5 cursor-pointer" onclick="updateRangeFilter({{ $data['month_num'] }}, {{ $year }})">
                                        <div class="flex flex-col">
                                            <span class="font-black text-text-primary gujarati-text tracking-tight group-hover:text-primary transition-colors">{{ $data['month_gujarati'] }}</span>
                                            <span class="text-[10px] font-bold text-text-secondary opacity-60 uppercase">{{ $data['month_name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-right font-black text-green-600">₹ {{ number_format($data['income'], 2) }}</td>
                                    <td class="px-6 py-5 text-right font-black text-red-600">₹ {{ number_format($data['expense'], 2) }}</td>
                                    <td class="px-6 py-5 text-right">
                                        <div class="inline-block px-4 py-1.5 rounded-lg font-black {{ $data['closing_balance'] >= 0 ? 'bg-primary/10 text-primary' : 'bg-red-100 text-red-600' }}">
                                            ₹ {{ number_format($data['closing_balance'], 2) }}
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar Charts -->
        <div class="lg:col-span-4 space-y-8">
            <div class="card-surface p-6 shadow-premium">
                <h3 class="text-xl font-black text-text-primary mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">pie_chart</span>
                    ખર્ચનું વિભાજન (Expenses)
                </h3>
                <div class="relative aspect-square">
                    <canvas id="expenseChart"></canvas>
                </div>
            </div>
            
            <div class="card-surface p-6 shadow-premium h-full">
                <h3 class="text-xl font-black text-text-primary mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">bar_chart</span>
                    માસિક વલણ
                </h3>
                <div class="space-y-4">
                    @foreach(array_reverse($formattedMonthlyData) as $data)
                        @if($data['income'] > 0 || $data['expense'] > 0)
                            <div class="cursor-pointer group" onclick="updateRangeFilter({{ $data['month_num'] }}, {{ $year }})">
                                <div class="flex justify-between mb-1.5 px-0.5">
                                    <span class="text-[10px] font-black text-text-primary group-hover:text-primary transition-colors">{{ $data['month_gujarati'] }}</span>
                                    <span class="text-[9px] font-bold text-text-secondary opacity-60">નફો: ₹ {{ number_format($data['income'] - $data['expense'], 2) }}</span>
                                </div>
                                <div class="relative h-2 bg-background rounded-full overflow-hidden flex">
                                    @php
                                        $monthTotal = max(($data['income'] + $data['expense']), 1);
                                        $incomeWidth = ($data['income'] / $monthTotal) * 100;
                                        $expenseWidth = ($data['expense'] / $monthTotal) * 100;
                                    @endphp
                                    <div class="h-full bg-green-500" style="width: {{ $incomeWidth }}%"></div>
                                    <div class="h-full bg-red-500" style="width: {{ $expenseWidth }}%"></div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Integrated Detailed Transactions -->
    <div id="detailed-section" class="scroll-mt-40">
        <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-end gap-6">
            <div>
                <h3 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter">વ્યવહારની વિગતવાર યાદી</h3>
                <p class="text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em] mt-1">Search & Filter Transactions</p>
            </div>
            <div class="w-full md:w-96 relative">
                 <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-text-secondary z-10">search</span>
                 <input type="text" id="transactionSearch" placeholder="કોઈપણ વિગત અથવા રકમથી સર્ચ કરો..." 
                        class="input-field pl-12 py-4 bg-white border border-border-light shadow-sm font-bold gujarati-text">
            </div>
        </div>

        <div class="card-surface p-6 shadow-premium mb-8 border border-border-light bg-primary/5">
            <form action="{{ route('rojmel.dashboard') }}#detailed-section" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <input type="hidden" name="year" value="{{ $year }}">
                <div>
                    <label class="text-[10px] font-black text-text-secondary uppercase tracking-widest mb-1.5 block">થી (From Date)</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" class="input-field font-bold">
                </div>
                <div>
                    <label class="text-[10px] font-black text-text-secondary uppercase tracking-widest mb-1.5 block">સુધી (To Date)</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" class="input-field font-bold">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="primary-btn flex-1 py-3 text-sm">ફિલ્ટર કરો</button>
                    <a href="{{ route('rojmel.dashboard') }}#detailed-section" class="px-6 py-3 bg-white rounded-xl font-bold text-sm text-text-secondary hover:bg-border-light/20 transition-all border border-border-light flex items-center leading-none">રીસેટ</a>
                </div>
                <div class="flex gap-2 justify-end">
                    <a href="{{ route('rojmel.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="w-12 h-12 bg-green-50 text-green-600 rounded-xl flex items-center justify-center hover:bg-green-600 hover:text-white transition-all shadow-sm group">
                        <span class="material-symbols-outlined">description</span>
                    </a>
                </div>
            </form>
        </div>

        <div class="card-surface shadow-premium overflow-hidden border border-border-light mb-12">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="entriesTable">
                    <thead>
                        <tr class="bg-background border-b border-border-light">
                            <th class="px-6 py-4 text-[10px] font-black text-text-secondary uppercase tracking-widest">તારીખ</th>
                            <th class="px-6 py-4 text-[10px] font-black text-text-secondary uppercase tracking-widest">વિગત / કેટેગરી</th>
                            <th class="px-6 py-4 text-[10px] font-black text-text-secondary uppercase tracking-widest text-right">આવક (+)</th>
                            <th class="px-6 py-4 text-[10px] font-black text-text-secondary uppercase tracking-widest text-right">જાવક (-)</th>
                            <th class="px-6 py-4 text-[10px] font-black text-text-secondary uppercase tracking-widest text-right">સિલક (Balance)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light/50">
                        @php $runningBalance = $detailSummary['opening_balance']; @endphp
                        @forelse($detailEntries as $entry)
                            @php 
                                if($entry->type == 'avak') $runningBalance += $entry->amount;
                                else $runningBalance -= $entry->amount;
                            @endphp
                            <tr class="hover:bg-primary/5 transition-colors group entry-row" 
                                data-search="{{ strtolower($entry->description . ' ' . $entry->category . ' ' . $entry->amount . ' ' . $entry->date->format('d/m/Y')) }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-black text-text-primary tracking-tight">{{ $entry->date->format('d/m/Y') }}</div>
                                    <span class="text-[9px] block font-bold text-text-secondary uppercase opacity-50">{{ $entry->date->format('l') }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-text-primary gujarati-text mb-1">{{ $entry->description ?: '-' }}</div>
                                    @if($entry->category)
                                        <span class="px-2 py-0.5 rounded-md bg-background text-text-secondary text-[9px] font-black uppercase tracking-widest border border-border-light">
                                            {{ $entry->category }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($entry->type == 'avak')
                                        <span class="font-black text-green-600">₹ {{ number_format($entry->amount, 2) }}</span>
                                    @else
                                        <span class="opacity-20">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($entry->type == 'javak')
                                        <span class="font-black text-red-600">₹ {{ number_format($entry->amount, 2) }}</span>
                                    @else
                                        <span class="opacity-20">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="inline-block px-3 py-1 rounded-lg bg-background border border-border-light font-black text-text-primary text-xs">
                                        ₹ {{ number_format($runningBalance, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center text-text-secondary/30 italic">આ સમયગાળામાં કોઈ વ્યવહાર મળ્યા નથી.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if($detailEntries->count() > 0)
                    <tfoot>
                         <tr class="bg-background border-t-2 border-border-light font-black">
                            <td colspan="2" class="px-6 py-6 text-[12px] uppercase tracking-widest text-text-primary">કુલ સરવાળો (TOTAL)</td>
                            <td class="px-6 py-6 text-right text-green-600 text-lg">₹ {{ number_format($detailSummary['total_avak'], 2) }}</td>
                            <td class="px-6 py-6 text-right text-red-600 text-lg">₹ {{ number_format($detailSummary['total_javak'], 2) }}</td>
                            <td class="px-6 py-6"></td>
                        </tr>
                        <tr class="bg-primary/5">
                            <td colspan="5" class="px-6 py-4 text-right">
                                <span class="text-text-secondary text-[10px] font-bold uppercase tracking-widest mr-4">સમયગાળાની આખર સિલક (Closing Balance):</span>
                                <span class="text-primary text-xl font-black">₹ {{ number_format($detailSummary['closing_balance'], 2) }}</span>
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Hind+Vadodara:wght@300;400;500;600;700&display=swap');
        .gujarati-text { font-family: 'Hind Vadodara', sans-serif; }
        .shadow-premium { box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 5px 15px -8px rgba(0, 0, 0, 0.05); }
    </style>
    @endpush

    @push('footer')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Live Search Logic
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

        // 2. Filter Update Helper
        function updateRangeFilter(month, year) {
            const firstDay = `${year}-${month.toString().padStart(2, '0')}-01`;
            const lastDay = new Date(year, month, 0).toISOString().split('T')[0];
            
            document.getElementById('start_date').value = firstDay;
            document.getElementById('end_date').value = lastDay;
            
            window.location.href = `{{ route('rojmel.dashboard') }}?year=${year}&start_date=${firstDay}&end_date=${lastDay}#detailed-section`;
        }

        // 3. Expense Breakdown Chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('expenseChart').getContext('2d');
            const data = @json($categoryStats);
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.map(i => i.category),
                    datasets: [{
                        data: data.map(i => i.total),
                        backgroundColor: [
                            '#2563eb', '#16a34a', '#dc2626', '#f59e0b', '#8b5cf6', '#06b6d4'
                        ],
                        borderWidth: 0,
                        hoverOffset: 15
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 10, family: 'Hind Vadodara', weight: 'bold' },
                                boxWidth: 10,
                                padding: 15
                            }
                        }
                    },
                    cutout: '70%',
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
