<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter">પગાર પતાવટ (Settlements)</h2>
            <p class="text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em] mt-1">Salary Closing & Payment History</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('settlements.create') }}" class="bg-primary text-white px-8 py-3 rounded-lg font-bold text-sm shadow-lg shadow-primary/20 flex items-center gap-2 hover:bg-primary/90 transition-all">
                <span class="material-symbols-outlined">receipt_long</span>
                નવી પતાવટ (New Settlement)
            </a>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="card-surface p-6 shadow-premium mb-8">
        <form action="{{ route('settlements.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-6 items-end">
            <div class="md:col-span-1">
                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">શોધો (Search)</label>
                <input type="text" name="search" value="{{ request('search') }}" class="input-field font-bold text-sm" placeholder="ID કે નોંધ શોધો...">
            </div>
            <div>
                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">મજૂર પસંદ કરો</label>
                <select name="worker_id" class="input-field gujarati-text font-bold">
                    <option value="">બધા મજૂરો</option>
                    @foreach($workers as $worker)
                        <option value="{{ $worker->id }}" {{ $workerId == $worker->id ? 'selected' : '' }}>{{ $worker->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">થી (From Date)</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="input-field font-bold text-xs">
            </div>
            <div>
                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">સુધી (To Date)</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="input-field font-bold text-xs">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="primary-btn flex-1 py-3 text-xs">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    ફિલ્ટર
                </button>
                <a href="{{ route('settlements.index') }}" class="px-6 py-3 bg-background rounded-xl font-bold text-sm text-text-secondary hover:bg-border-light/20 transition-all flex items-center">રીસેટ</a>
            </div>
        </form>
    </div>

    <!-- Settlements Table -->
    <div class="mb-4 flex justify-between items-center px-2">
        <p class="text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em] opacity-50">
            Showing {{ $settlements->firstItem() ?? 0 }} - {{ $settlements->lastItem() ?? 0 }} of {{ $settlements->total() }} settlements
        </p>
    </div>
    <div class="card-surface shadow-premium overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-background border-b border-border-light">
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">તારીખ</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">મજૂરનું નામ</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">ગાળો (Period)</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">કુલ મજૂરી</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">ઉપાડ (-)</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">ચૂકવેલ</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-center">એક્શન</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light/50">
                    @forelse($settlements as $s)
                        <tr class="hover:bg-primary/5 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-text-primary">{{ \Carbon\Carbon::parse($s->settlement_date)->format('d/m/Y') }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-text-primary gujarati-text text-lg">{{ $s->worker->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-[11px] font-bold text-text-secondary flex items-center gap-1">
                                    {{ \Carbon\Carbon::parse($s->start_date)->format('d/m/Y') }} 
                                    <span class="material-symbols-outlined text-[12px]">trending_flat</span>
                                    {{ \Carbon\Carbon::parse($s->end_date)->format('d/m/Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-emerald-600">₹{{ number_format($s->total_earnings, 2) }}</td>
                            <td class="px-6 py-4 text-right font-bold text-red-600">₹{{ number_format($s->total_advance, 2) }}</td>
                            <td class="px-6 py-4 text-right font-black text-primary bg-primary/5">₹{{ number_format($s->paid_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('settlements.show', $s) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </a>
                                    <form action="{{ route('settlements.destroy', $s) }}" method="POST" onsubmit="return confirm('શું તમે આ સેટલમેન્ટ રદ કરવા માંગો છો? આનાથી જોડાયેલ હાજરી અને ઉપાડ પાછા અનલોક થઈ જશે.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-text-secondary/30 italic">હજી સુધી કોઈ પતાવટની એન્ટ્રી મળી નથી.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-background/30 border-t border-border-light">
            {{ $settlements->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
