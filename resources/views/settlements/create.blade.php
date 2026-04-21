<x-app-layout>
    <div class="mb-8">
        <div class="flex items-center gap-2 text-text-secondary mb-2">
            <a href="{{ route('settlements.index') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                પતાવટ લિસ્ટ
            </a>
            <span>/</span>
            <span class="text-xs font-bold uppercase tracking-widest">New Settlement</span>
        </div>
        <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter">નવી પગાર પતાવટ (Salary Closing)</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Filter Form -->
        <div class="lg:col-span-1">
            <div class="card-surface p-6 shadow-premium sticky top-40">
                <h3 class="text-lg font-bold text-text-primary mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">filter_list</span>
                    ફિલ્ટર જાહેરાત
                </h3>
                
                <form action="{{ route('settlements.create') }}" method="GET" class="flex flex-col gap-5">
                    <div>
                        <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">મજૂર પસંદ કરો</label>
                        <select name="worker_id" required class="input-field border border-slate-300 gujarati-text font-bold w-full" onchange="this.form.submit()">
                            <option value="">મજૂર પસંદ કરો...</option>
                            @foreach($workers as $w)
                                <option value="{{ $w->id }}" {{ $workerId == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">થી તારીખ</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" class="input-field border border-slate-300 font-bold w-full" onchange="this.form.submit()">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">સુધી તારીખ</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="input-field border border-slate-300 font-bold w-full" onchange="this.form.submit()">
                        </div>
                    </div>
                </form>

                @if($worker)
                <div class="mt-8 pt-8 border-t border-border-light">
                    <div class="flex flex-col gap-4">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-text-secondary uppercase">કુલ કમાણી:</span>
                            <span class="font-bold text-emerald-600">₹ {{ number_format($stats['earnings'], 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-bold text-text-secondary uppercase">કુલ ઉપાડ (-):</span>
                            <span class="font-bold text-red-600">₹ {{ number_format($stats['advances'], 2) }}</span>
                        </div>
                        <div class="pt-4 mt-2 border-t-2 border-dashed border-border-light flex justify-between items-center">
                            <span class="text-sm font-black text-text-primary uppercase">ચૂકવવાપાત્ર:</span>
                            <span class="text-2xl font-black text-primary">₹ {{ number_format($stats['payable'], 2) }}</span>
                        </div>
                    </div>

                    <form action="{{ route('settlements.store') }}" method="POST" class="mt-8 flex flex-col gap-4">
                        @csrf
                        <input type="hidden" name="worker_id" value="{{ $workerId }}">
                        <input type="hidden" name="start_date" value="{{ $startDate }}">
                        <input type="hidden" name="end_date" value="{{ $endDate }}">
                        
                        <div>
                            <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">પતાવટ તારીખ</label>
                            <input type="date" name="settlement_date" value="{{ date('Y-m-d') }}" required class="input-field border border-slate-300 font-bold w-full">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">ચૂકવેલ રકમ (Paid)</label>
                            <input type="number" step="0.01" name="paid_amount" value="{{ $stats['payable'] }}" required class="input-field border border-primary font-black text-primary text-xl w-full">
                        </div>

                        <div>
                            <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">પેમેન્ટ પદ્ધતિ</label>
                            <select name="payment_method" required class="input-field border border-slate-300 font-bold w-full">
                                <option value="Cash">Cash (રોકડ)</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                                <option value="UPI">UPI / PhonePe / GPay</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">નોંધ (Notes)</label>
                            <textarea name="notes" rows="2" class="input-field border border-slate-300 gujarati-text w-full" placeholder="વધારાની વિગત..."></textarea>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-bold text-lg shadow-xl shadow-primary/20 hover:bg-primary/90 transition-all mt-4 flex items-center justify-center gap-2">
                             <span class="material-symbols-outlined">payments</span>
                             પતાવટ પૂર્ણ કરો (Confirm Settlement)
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Details List -->
        <div class="lg:col-span-2">
            @if(!$worker)
                <div class="card-surface p-12 text-center border-2 border-dashed border-border-light">
                    <span class="material-symbols-outlined text-6xl text-text-secondary/20 mb-4">person_search</span>
                    <p class="text-text-secondary gujarati-text text-xl">પતાવટ કરવા માટે મજૂર અને ગાળો પસંદ કરો.</p>
                </div>
            @else
                <div class="space-y-6">
                    <!-- Earnings -->
                    <div class="card-surface overflow-hidden shadow-premium">
                        <div class="p-4 bg-emerald-50 border-b border-emerald-100 flex justify-between items-center">
                            <h4 class="font-black text-emerald-700 gujarati-text flex items-center gap-2">
                                <span class="material-symbols-outlined">work</span>
                                મજૂરીની વિગતો (Earnings)
                            </h4>
                            <span class="bg-emerald-600 text-white px-3 py-1 rounded-full text-xs font-black">₹ {{ number_format($stats['earnings'], 2) }}</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-background">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-bold text-text-secondary uppercase tracking-widest">તારીખ</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-text-secondary uppercase tracking-widest">કામનો પ્રકાર</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">રકમ</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border-light/50">
                                    @forelse($unsettledEarnings as $e)
                                        <tr class="hover:bg-emerald-50/30 transition-colors">
                                            <td class="px-4 py-3 font-bold text-text-primary">{{ \Carbon\Carbon::parse($e->entry->date)->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3 gujarati-text text-sm">{{ $e->work_type ?: 'સામાન્ય' }}</td>
                                            <td class="px-4 py-3 text-right font-bold text-emerald-600">₹ {{ number_format($e->wage_amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-8 text-center text-text-secondary/50 italic">કોઈ રેકોર્ડ નથી.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Advances -->
                    <div class="card-surface overflow-hidden shadow-premium">
                        <div class="p-4 bg-red-50 border-b border-red-100 flex justify-between items-center">
                            <h4 class="font-black text-red-700 gujarati-text flex items-center gap-2">
                                <span class="material-symbols-outlined">payments</span>
                                ઉપાડની વિગતો (Advances)
                            </h4>
                            <span class="bg-red-600 text-white px-3 py-1 rounded-full text-xs font-black">₹ {{ number_format($stats['advances'], 2) }}</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-background">
                                    <tr>
                                        <th class="px-4 py-3 text-[10px] font-bold text-text-secondary uppercase tracking-widest">તારીખ</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-text-secondary uppercase tracking-widest">નોંધ</th>
                                        <th class="px-4 py-3 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">રકમ</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border-light/50">
                                    @forelse($unsettledAdvances as $a)
                                        <tr class="hover:bg-red-50/30 transition-colors">
                                            <td class="px-4 py-3 font-bold text-text-primary">{{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }}</td>
                                            <td class="px-4 py-3 gujarati-text text-sm">{{ $a->note ?: '-' }}</td>
                                            <td class="px-4 py-3 text-right font-bold text-red-600">₹ {{ number_format($a->amount, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-4 py-8 text-center text-text-secondary/50 italic">કોઈ ઉપાડ નથી.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
