<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter">રોકડમેળ રીપોર્ટ (Range Report)</h2>
            <p class="text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em] mt-1">Cash flow summaries over time</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('rojmel.dashboard') }}" class="bg-white text-text-secondary px-6 py-3 rounded-lg border border-border-light font-bold text-sm flex items-center gap-2 hover:bg-background transition-all">
                <span class="material-symbols-outlined text-[18px]">dashboard</span>
                ડેશબોર્ડ
            </a>
            <a href="{{ route('rojmel.index') }}" class="bg-white text-text-secondary px-6 py-3 rounded-lg border border-border-light font-bold text-sm flex items-center gap-2 hover:bg-background transition-all">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                પાછા જાઓ
            </a>
            <a href="{{ route('rojmel.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="bg-red-50 text-red-600 px-6 py-3 rounded-lg font-bold text-sm flex items-center gap-2 hover:bg-red-600 hover:text-white transition-all">
                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                રીપોર્ટ PDF
            </a>
            <a href="{{ route('rojmel.excel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="bg-green-50 text-green-600 px-6 py-3 rounded-lg font-bold text-sm flex items-center gap-2 hover:bg-green-600 hover:text-white transition-all">
                <span class="material-symbols-outlined text-[18px]">description</span>
                Excel ડાઉનલોડ
            </a>
        </div>
    </div>

    <!-- Date Filters -->
    <div class="card-surface p-6 shadow-premium mb-8">
        <form action="{{ route('rojmel.report') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <div>
                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">થી (From Date)</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="input-field font-bold">
            </div>
            <div>
                <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">સુધી (To Date)</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="input-field font-bold">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="primary-btn flex-1 py-3">રીપોર્ટ જુઓ</button>
                <a href="{{ route('rojmel.report') }}" class="px-6 py-3 bg-background rounded-xl font-bold text-sm text-text-secondary hover:bg-border-light/20 transition-all flex items-center">રીસેટ</a>
            </div>
        </form>
    </div>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card-surface p-6 border-l-4 border-green-500">
            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">કુલ આવક (+)</span>
            <h3 class="text-2xl font-black text-green-600">₹ {{ number_format($summary['total_avak'], 2) }}</h3>
        </div>
        <div class="card-surface p-6 border-l-4 border-red-500">
            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">કુલ જાવક (-)</span>
            <h3 class="text-2xl font-black text-red-600">₹ {{ number_format($summary['total_javak'], 2) }}</h3>
        </div>
        <div class="card-surface p-6 border-l-4 border-primary">
            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">ચોખ્ખો વધારો/ઘટાડો (Net)</span>
            <h3 class="text-2xl font-black {{ $summary['net_change'] >= 0 ? 'text-primary' : 'text-red-500' }}">₹ {{ number_format($summary['net_change'], 2) }}</h3>
        </div>
    </div>

    <!-- Day-wise breakdown table -->
    <div class="card-surface shadow-premium overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-background border-b border-border-light">
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">તારીખ</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">શરૂઆતનું બેલેન્સ</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">કુલ આવક (+)</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">કુલ જાવક (-)</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">આખરનું બેલેન્સ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light/50">
                    @forelse($balances as $b)
                        <tr class="hover:bg-primary/5 transition-colors group cursor-pointer" onclick="window.location='{{ route('rojmel.index', ['date' => $b->date->format('Y-m-d')]) }}'">
                            <td class="px-6 py-4 font-black text-text-primary tracking-tight">
                                {{ $b->date->format('d/m/Y') }}
                                <span class="text-[9px] block font-bold text-text-secondary uppercase opacity-50">{{ $b->date->format('l') }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-text-secondary opacity-60">₹ {{ number_format($b->opening_balance, 2) }}</td>
                            <td class="px-6 py-4 text-right font-bold text-green-600">+ ₹ {{ number_format($b->total_avak, 2) }}</td>
                            <td class="px-6 py-4 text-right font-bold text-red-600">- ₹ {{ number_format($b->total_javak, 2) }}</td>
                            <td class="px-6 py-4 text-right font-black text-primary">₹ {{ number_format($b->closing_balance, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-text-secondary/30 italic">આ સમયગાળામાં કોઈ ટ્રાન્ઝેક્શન મળ્યા નથી.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
