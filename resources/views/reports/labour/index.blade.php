<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter">મજૂરી રીપોર્ટ</h2>
            <p class="text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em] mt-1">Monthly Labour Wages Report</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('reports.labour.excel', ['month' => $month, 'worker_id' => $workerId, 'start_date' => $startDate, 'end_date' => $endDate]) }}" class="bg-green-50 text-green-600 px-6 py-3 rounded-lg font-bold text-sm flex items-center gap-2 hover:bg-green-600 hover:text-white transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">description</span>
                Excel ડાઉનલોડ
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card-surface p-6 shadow-premium mb-8">
        <form action="{{ route('reports.labour') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
            <div>
                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">મહિનો (વૈકલ્પિક)</label>
                <input type="month" name="month" value="{{ $month }}" class="input-field font-bold">
            </div>
            <div>
                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">થી (From Date)</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="input-field font-bold text-xs">
            </div>
            <div>
                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">સુધી (To Date)</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="input-field font-bold text-xs">
            </div>
            <div>
                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">મજૂર (વૈકલ્પિક)</label>
                <select name="worker_id" class="input-field gujarati-text font-bold">
                    <option value="">બધા મજૂરો</option>
                    @foreach($workers as $worker)
                        <option value="{{ $worker->id }}" {{ $workerId == $worker->id ? 'selected' : '' }}>{{ $worker->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="primary-btn flex-1 py-3">રીપોર્ટ જુઓ</button>
                <a href="{{ route('reports.labour') }}" class="px-6 py-3 bg-background rounded-xl font-bold text-sm text-text-secondary hover:bg-border-light/20 transition-all flex items-center">રીસેટ</a>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card-surface p-6 border-l-4 border-green-500">
            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">કુલ મજૂરી</span>
            <h3 class="text-2xl font-black text-green-600">₹ {{ number_format($stats['total_wages'], 2) }}</h3>
        </div>
        <div class="card-surface p-6 border-l-4 border-red-500">
            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">કુલ ઉપાડ (Upad)</span>
            <h3 class="text-2xl font-black text-red-600">₹ {{ number_format($stats['total_advance'], 2) }}</h3>
        </div>
        <div class="card-surface p-6 border-l-4 border-primary">
            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">બાકી ચૂકવણું (Payable)</span>
            <h3 class="text-2xl font-black text-primary">₹ {{ number_format($stats['total_payable'], 2) }}</h3>
        </div>
        <div class="card-surface p-6 border-l-4 border-orange-500">
            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">કુલ દિવસો</span>
            <h3 class="text-2xl font-black text-text-primary">{{ $stats['total_days'] }}</h3>
        </div>
    </div>

    <!-- Monthly Settlement Table -->
    <div class="mb-8">
        <h3 class="text-lg font-bold text-text-primary gujarati-text mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">analytics</span>
            સેટલમેન્ટ સમરી (Settlement Summary)
        </h3>
        <div class="card-surface shadow-premium overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-background border-b border-border-light">
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">મજૂરનું નામ</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-center">દિવસો</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">આગળની બાકી</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">કુલ કમાણી (+)</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">કુલ ઉપાડ (-)</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">ચૂકવેલ (Paid)</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">ચૂકવવાના બાકી</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light/50">
                        @foreach($settlements as $s)
                            <tr class="hover:bg-background/20 transition-colors {{ $workerId == $s->worker->id ? 'bg-primary/5' : '' }}">
                                <td class="px-6 py-4 gujarati-text font-bold text-text-primary">
                                    <div class="flex items-center gap-2">
                                        {{ $s->worker->name }}
                                        <a href="{{ route('reports.labour', ['worker_id' => $s->worker->id, 'start_date' => $startDate, 'end_date' => $endDate]) }}" title="લેજર જુઓ" class="text-primary hover:scale-110 transition-transform">
                                            <span class="material-symbols-outlined text-[18px]">account_balance_wallet</span>
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-text-secondary">{{ $s->total_days }}</td>
                                <td class="px-6 py-4 text-right font-bold {{ $s->opening_balance >= 0 ? 'text-primary' : 'text-red-500' }}">₹ {{ number_format($s->opening_balance, 2) }}</td>
                                <td class="px-6 py-4 text-right font-bold text-green-600">₹ {{ number_format($s->total_earnings, 2) }}</td>
                                <td class="px-6 py-4 text-right font-bold text-red-600">₹ {{ number_format($s->total_advance, 2) }}</td>
                                <td class="px-6 py-4 text-right font-bold text-blue-600">₹ {{ number_format($s->total_paid, 2) }}</td>
                                <td class="px-6 py-4 text-right">
                                    <span class="px-3 py-1 rounded-lg {{ $s->final_payable >= 0 ? 'bg-primary/10 text-primary' : 'bg-red-100 text-red-600' }} font-black text-sm">
                                        ₹ {{ number_format($s->final_payable, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($timeline->isNotEmpty())
        <!-- Worker Timeline (Ledger style) -->
        <div class="mb-4 flex gap-4 border-b border-border-light">
            <button class="px-6 py-2 font-bold text-sm text-primary border-b-2 border-primary">મજૂર લેજર (Worker Ledger Timeline)</button>
        </div>
        <div class="card-surface shadow-premium overflow-hidden mb-8">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-background border-b border-border-light">
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">તારીખ</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">વિગત</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">રકમ (+)</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">ઉપાડ / ચુકવણી (-)</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">બેલેન્સ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light/50">
                        @foreach($timeline as $item)
                            <tr class="hover:bg-background/20 transition-colors {{ in_array($item->type, ['advance', 'settlement']) ? 'bg-red-50/20' : '' }}">
                                <td class="px-6 py-4 font-bold text-xs text-text-primary">{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 gujarati-text text-sm">
                                    {{ $item->description }}
                                    @if($item->type == 'settlement')
                                        <span class="ml-2 px-1.5 py-0.5 bg-blue-100 text-blue-700 text-[9px] font-black uppercase rounded">પતાવટ</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-green-600">{{ $item->type == 'earning' ? '₹ ' . number_format($item->amount, 2) : '-' }}</td>
                                <td class="px-6 py-4 text-right font-bold text-red-600">{{ in_array($item->type, ['advance', 'settlement']) ? '₹ ' . number_format(abs($item->amount), 2) : '-' }}</td>
                                <td class="px-6 py-4 text-right font-black text-text-primary">₹ {{ number_format($item->running_balance, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- General Detailed Breakdown -->
        <div class="mb-4 flex gap-4 border-b border-border-light">
            <button class="px-6 py-2 font-bold text-sm text-primary border-b-2 border-primary">Earnings Details</button>
        </div>

        <div class="card-surface shadow-premium overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-background border-b border-border-light">
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">તારીખ</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">મજૂરનું નામ</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">કામ</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-center">હાજરી</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">મજૂરી (₹)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light/50">
                        @forelse($details as $detail)
                            <tr class="hover:bg-background/20 transition-colors">
                                <td class="px-6 py-4 font-bold text-text-primary">{{ \Carbon\Carbon::parse($detail->entry->date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 gujarati-text font-bold">{{ $detail->worker->name }}</td>
                                <td class="px-6 py-4 text-xs">{{ $detail->work_type ?: '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($detail->attendance_type == 'full')
                                        <span class="text-[10px] font-bold bg-green-100 text-green-700 px-2 py-1 rounded uppercase">આખો દિવસ</span>
                                    @elseif($detail->attendance_type == 'half')
                                        <span class="text-[10px] font-bold bg-orange-100 text-orange-700 px-2 py-1 rounded uppercase">અડધો દિવસ</span>
                                    @else
                                        <span class="text-[10px] font-bold bg-blue-100 text-blue-700 px-2 py-1 rounded uppercase">{{ $detail->hours }} કલાક</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-black text-primary">₹ {{ number_format($detail->wage_amount, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-text-secondary/30 italic">આ ફિલ્ટર મુજબ કોઈ વિગત મળી નથી.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</x-app-layout>
