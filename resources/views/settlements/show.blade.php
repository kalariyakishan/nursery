<x-app-layout>
    <div class="mb-8">
        <div class="flex items-center gap-2 text-text-secondary mb-2">
            <a href="{{ route('settlements.index') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                પતાવટ લિસ્ટ
            </a>
            <span>/</span>
            <span class="text-xs font-bold uppercase tracking-widest">Settlement #{{ $settlement->id }}</span>
        </div>
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter">પગાર પતાવટની વિગત</h2>
                <p class="text-xs font-bold text-text-secondary uppercase tracking-[0.2em] mt-1">{{ $settlement->worker->name }} | {{ \Carbon\Carbon::parse($settlement->settlement_date)->format('d/m/Y') }}</p>
            </div>
            <button onclick="window.print()" class="print:hidden bg-background border border-border-light text-text-primary px-6 py-3 rounded-lg font-bold text-sm shadow-sm flex items-center gap-2 hover:bg-border-light/20 transition-all">
                <span class="material-symbols-outlined">print</span>
                પ્રિન્ટ (Print)
            </button>
        </div>
    </div>

    <!-- Settlement Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="card-surface p-8 shadow-premium border-t-4 border-primary">
                <div class="text-center mb-8">
                    <div class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-1">ચૂકવેલ રકમ</div>
                    <div class="text-4xl font-black text-primary">₹ {{ number_format($settlement->paid_amount, 2) }}</div>
                </div>

                <div class="space-y-6">
                    <div class="flex justify-between items-center py-3 border-b border-border-light/50">
                        <span class="text-xs font-bold text-text-secondary uppercase">મજૂર:</span>
                        <span class="font-bold text-text-primary gujarati-text">{{ $settlement->worker->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-border-light/50">
                        <span class="text-xs font-bold text-text-secondary uppercase">ગાળો (Period):</span>
                        <span class="font-bold text-text-primary text-xs">{{ \Carbon\Carbon::parse($settlement->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($settlement->end_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-border-light/50">
                        <span class="text-xs font-bold text-text-secondary uppercase">કુલ કમાણી:</span>
                        <span class="font-bold text-emerald-600">₹ {{ number_format($settlement->total_earnings, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-border-light/50">
                        <span class="text-xs font-bold text-text-secondary uppercase">કુલ ઉપાડ:</span>
                        <span class="font-bold text-red-600">₹ {{ number_format($settlement->total_advance, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-border-light/50">
                        <span class="text-xs font-bold text-text-secondary uppercase">પેમેન્ટ પદ્ધતિ:</span>
                        <span class="badge badge-primary px-3 py-1">{{ $settlement->payment_method }}</span>
                    </div>
                </div>

                @if($settlement->notes)
                <div class="mt-8 pt-6 border-t border-border-light/50">
                    <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-2">નોંધ (Notes)</label>
                    <p class="text-sm text-text-primary gujarati-text leading-relaxed">{{ $settlement->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <!-- Earnings Included -->
            <div class="card-surface overflow-hidden shadow-premium">
                <div class="p-4 bg-background border-b border-border-light">
                    <h4 class="font-black text-text-primary gujarati-text">સમાવિષ્ટ હાજરી (Involved Attendance)</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-background/20">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase">તારીખ</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase">કામ</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase text-right">રકમ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light/50">
                            @foreach($settlement->labourDetails as $d)
                            <tr>
                                <td class="px-6 py-4 font-bold text-text-primary">{{ \Carbon\Carbon::parse($d->entry->date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 gujarati-text text-sm">{{ $d->work_type ?: 'સામાન્ય' }}</td>
                                <td class="px-6 py-4 text-right font-bold text-emerald-600">₹ {{ number_format($d->wage_amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Advances Included -->
            <div class="card-surface overflow-hidden shadow-premium">
                <div class="p-4 bg-background border-b border-border-light">
                    <h4 class="font-black text-text-primary gujarati-text">સમાવિષ્ટ ઉપાડ (Involved Advances)</h4>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-background/20">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase">તારીખ</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase">નોંધ</th>
                                <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase text-right">રકમ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-light/50">
                            @foreach($settlement->advances as $a)
                            <tr>
                                <td class="px-6 py-4 font-bold text-text-primary">{{ \Carbon\Carbon::parse($a->date)->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 gujarati-text text-sm">{{ $a->note ?: '-' }}</td>
                                <td class="px-6 py-4 text-right font-bold text-red-600">₹ {{ number_format($a->amount, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
