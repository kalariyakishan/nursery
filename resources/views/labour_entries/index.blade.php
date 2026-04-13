<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter">મજૂરી હાજરી હિસ્ટ્રી</h2>
            <p class="text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em] mt-1">Daily Labour Entry History</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('labour-entries.create') }}" class="primary-btn px-8 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined">add</span>
                નવી એન્ટ્રી (ADD NEW)
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="card-surface p-6 border-l-4 border-primary">
            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">આજની કુલ એન્ટ્રી</span>
            @php
                $todayEntry = \App\Models\LabourEntry::where('date', date('Y-m-d'))->first();
            @endphp
            <div class="flex items-end justify-between">
                <h3 class="text-3xl font-black text-text-primary">{{ $todayEntry ? $todayEntry->total_workers : 0 }} મજૂર</h3>
                <span class="text-xs font-bold text-primary bg-primary/10 px-2 py-1 rounded">આજની તારીખ</span>
            </div>
        </div>
        <div class="card-surface p-6 border-l-4 border-green-500">
            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">આજનું કુલ ચૂકવણું</span>
            <div class="flex items-end justify-between">
                <h3 class="text-3xl font-black text-text-primary text-primary">₹ {{ number_format($todayEntry ? $todayEntry->total_amount : 0, 2) }}</h3>
                <span class="material-symbols-outlined text-green-500">payments</span>
            </div>
        </div>
        <div class="card-surface p-6 border-l-4 border-orange-500 flex items-center justify-between">
            <div>
                <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block mb-1">પાછલી એન્ટ્રી કોપી કરો</span>
                <p class="text-xs font-bold text-text-secondary opacity-60">ગઈકાલના ડેટા સાથે નવું ફોર્મ ભરો</p>
            </div>
            @php
                $lastEntry = \App\Models\LabourEntry::latest('date')->first();
            @endphp
            @if($lastEntry)
                <a href="{{ route('labour-entries.duplicate', $lastEntry->date) }}" class="w-10 h-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center hover:bg-orange-600 hover:text-white transition-all shadow-sm">
                    <span class="material-symbols-outlined">content_copy</span>
                </a>
            @else
                <button disabled class="w-10 h-10 rounded-full bg-gray-100 text-gray-400 flex items-center justify-center cursor-not-allowed">
                    <span class="material-symbols-outlined">content_copy</span>
                </button>
            @endif
        </div>
    </div>

    <!-- History Table -->
    <div class="card-surface shadow-premium overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-background border-b border-border-light">
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">તારીખ (Date)</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-center">કુલ મજૂર</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">કુલ રકમ</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-center">એક્શન</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light/50">
                    @forelse($entries as $entry)
                        <tr class="hover:bg-background/20 transition-colors group">
                            <td class="px-6 py-4">
                                <span class="font-bold text-text-primary block">{{ \Carbon\Carbon::parse($entry->date)->format('d/m/Y') }}</span>
                                <span class="text-[10px] text-text-secondary uppercase font-bold opacity-40">{{ \Carbon\Carbon::parse($entry->date)->format('l') }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-black">{{ $entry->total_workers }} Persons</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-black text-text-primary tracking-tight text-lg">₹ {{ number_format($entry->total_amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('labour-entries.edit', $entry) }}" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <form action="{{ route('labour-entries.destroy', $entry) }}" method="POST" onsubmit="return confirm('શું તમે આ એન્ટ્રી કાઢી નાખવા માંગો છો?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                    <a href="{{ route('labour-entries.duplicate', $entry->date) }}" title="Copy entries" class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center hover:bg-orange-600 hover:text-white transition-all">
                                        <span class="material-symbols-outlined text-[18px]">content_copy</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <span class="material-symbols-outlined text-6xl">engineering</span>
                                    <p class="gujarati-text font-bold mt-2">કોઈ હાજરી એન્ટ્રી મળી નથી.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-background/30">
            {{ $entries->links() }}
        </div>
    </div>
</x-app-layout>
