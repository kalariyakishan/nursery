<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter">મજૂર લેજર (Worker Ledger)</h2>
            <p class="text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em] mt-1">Timeline of earnings and advances</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('reports.labour') }}" class="bg-white text-text-secondary px-6 py-3 rounded-lg border border-border-light font-bold text-sm flex items-center gap-2 hover:bg-background transition-all">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                રીપોર્ટ પર પાછા જાઓ
            </a>
        </div>
    </div>

    <!-- Ledger Filters -->
    <div class="card-surface p-6 shadow-premium mb-8">
        <form action="{{ route('reports.labour.ledger') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div class="md:col-span-1">
                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">મજૂર પસંદ કરો</label>
                <select name="worker_id" required class="input-field gujarati-text font-bold">
                    <option value="">મજૂર પસંદ કરો...</option>
                    @foreach($workers as $w)
                        <option value="{{ $w->id }}" {{ $workerId == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">થી (From Date)</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="input-field font-bold">
            </div>
            <div>
                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">સુધી (To Date)</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="input-field font-bold">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="primary-btn flex-1 py-3 text-xs">લેજર જુઓ</button>
            </div>
        </form>
    </div>

    @if($worker)
        <!-- Worker Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="card-surface p-6 border-l-4 border-green-500">
                <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">કુલ કમાણી (+)</span>
                <h3 class="text-2xl font-black text-green-600">₹ {{ number_format($timeline->where('type', 'earning')->sum('amount'), 2) }}</h3>
            </div>
            <div class="card-surface p-6 border-l-4 border-red-500">
                <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">કુલ ઉપાડ (-)</span>
                <h3 class="text-2xl font-black text-red-600">₹ {{ number_format(abs($timeline->where('type', 'advance')->sum('amount')), 2) }}</h3>
            </div>
            <div class="card-surface p-6 border-l-4 border-primary">
                <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">ચોખ્ખું બાકી (Net Balance)</span>
                <h3 class="text-2xl font-black text-primary">₹ {{ number_format($timeline->last() ? $timeline->last()->running_balance : 0, 2) }}</h3>
            </div>
        </div>

        <!-- Timeline Table -->
        <div class="card-surface shadow-premium overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-background border-b border-border-light">
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">તારીખ</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">વિગત (Description)</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">રકમ (+)</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">ઉપાડ (-)</th>
                            <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">બેલેન્સ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light/50">
                        @forelse($timeline as $item)
                            <tr class="hover:bg-background/20 transition-colors {{ $item->type == 'advance' ? 'bg-red-50/20' : '' }}">
                                <td class="px-6 py-4 font-bold text-text-primary text-xs">{{ \Carbon\Carbon::parse($item->date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 gujarati-text text-sm">
                                    <span class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-[18px] {{ $item->type == 'advance' ? 'text-red-500' : 'text-green-500' }}">
                                            {{ $item->type == 'advance' ? 'trending_down' : 'trending_up' }}
                                        </span>
                                        {{ $item->description }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-green-600">
                                    {{ $item->type == 'earning' ? '₹ ' . number_format($item->amount, 2) : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-red-600">
                                    {{ $item->type == 'advance' ? '₹ ' . number_format(abs($item->amount), 2) : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-black text-text-primary">
                                        ₹ {{ number_format($item->running_balance, 2) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-text-secondary/30 italic">આ સમયગાળામાં કોઈ લેવડ-દેવડ મળી નથી.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="card-surface p-12 text-center">
            <div class="flex flex-col items-center opacity-20">
                <span class="material-symbols-outlined text-6xl">person_search</span>
                <p class="gujarati-text font-bold mt-2 text-xl">મજૂર પસંદ કરો અને લેજર જુઓ.</p>
            </div>
        </div>
    @endif
</x-app-layout>
