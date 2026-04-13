<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter">મજૂર ઉપાડ (Advance Entry)</h2>
            <p class="text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em] mt-1">Worker Advance Payment Management</p>
        </div>
        <div class="flex gap-4">
            <button onclick="document.getElementById('addAdvanceModal').classList.remove('hidden')" class="bg-red-600 text-white px-8 py-3 rounded-lg font-bold text-sm shadow-lg shadow-red-200 flex items-center gap-2 hover:bg-red-700 transition-all">
                <span class="material-symbols-outlined">payments</span>
                નવો ઉપાડ (Add Advance)
            </button>
        </div>
    </div>

    <!-- Advances Table -->
    <div class="card-surface shadow-premium overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-background border-b border-border-light">
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">તારીખ</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">મજૂરનું નામ</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest">નોંધ (Note)</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">રકમ (Amount)</th>
                        <th class="px-6 py-4 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-center">એક્શન</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light/50">
                    @forelse($advances as $advance)
                        <tr class="hover:bg-red-50/30 transition-colors">
                            <td class="px-6 py-4 font-bold text-text-primary">{{ \Carbon\Carbon::parse($advance->date)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 gujarati-text font-bold text-text-primary">{{ $advance->worker->name }}</td>
                            <td class="px-6 py-4 text-xs text-text-secondary">{{ $advance->note ?: '-' }}</td>
                            <td class="px-6 py-4 text-right font-black text-red-600">₹ {{ number_format($advance->amount, 2) }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <button onclick="editAdvance({{ json_encode($advance) }})" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </button>
                                    <form action="{{ route('advances.destroy', $advance) }}" method="POST" onsubmit="return confirm('શું તમે આ ઉપાડની વિગત કાઢી નાખવા માંગો છો?')">
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
                            <td colspan="5" class="px-6 py-12 text-center text-text-secondary/30 italic">હજી સુધી કોઈ ઉપાડની એન્ટ્રી મળી નથી.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-background/30">
            {{ $advances->links() }}
        </div>
    </div>

    @push('modals')
    <!-- Add Advance Modal -->
    <div id="addAdvanceModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 hidden p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-y-auto p-5">
            <div class="pb-4 mb-4 border-b border-border-light flex justify-between items-center">
                <h3 class="text-xl font-black text-red-600 gujarati-text">નવો ઉપાડ ઉમેરો</h3>
                <button type="button" onclick="document.getElementById('addAdvanceModal').classList.add('hidden')" class="text-text-secondary hover:text-text-primary">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form action="{{ route('advances.store') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">મજૂર પસંદ કરો</label>
                    <select name="worker_id" required class="input-field border border-slate-300 gujarati-text font-bold w-full">
                        <option value="">મજૂર પસંદ કરો...</option>
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">તારીખ</label>
                    <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="input-field border border-slate-300 font-bold w-full">
                </div>
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">ઉપાડ રકમ (Amount)</label>
                    <input type="number" step="0.01" name="amount" required class="input-field border border-slate-300 font-black text-red-600 text-lg w-full" placeholder="0.00">
                </div>
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">નોંધ / વિગત (Note)</label>
                    <input type="text" name="note" class="input-field border border-slate-300 gujarati-text w-full" placeholder="વિગત લખો...">
                </div>
                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="document.getElementById('addAdvanceModal').classList.add('hidden')" class="flex-1 px-6 py-3 rounded-xl border border-border-light font-bold text-sm text-text-secondary hover:bg-background transition-all">કેન્સલ</button>
                    <button type="submit" class="flex-1 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 shadow-lg shadow-red-200 transition-all">સાચવો (Add)</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Advance Modal -->
    <div id="editAdvanceModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 hidden p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-y-auto p-5">
            <div class="pb-4 mb-4 border-b border-border-light flex justify-between items-center">
                <h3 class="text-xl font-black text-blue-600 gujarati-text">ઉપાડની વિગત સુધારો</h3>
                <button type="button" onclick="document.getElementById('editAdvanceModal').classList.add('hidden')" class="text-text-secondary hover:text-text-primary">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form id="editAdvanceForm" method="POST" class="flex flex-col gap-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">મજૂર</label>
                    <select name="worker_id" id="edit_worker_id" required class="input-field border border-slate-300 gujarati-text font-bold w-full">
                        @foreach($workers as $worker)
                            <option value="{{ $worker->id }}">{{ $worker->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">તારીખ</label>
                    <input type="date" name="date" id="edit_date" required class="input-field border border-slate-300 font-bold w-full">
                </div>
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">રકમ</label>
                    <input type="number" step="0.01" name="amount" id="edit_amount" required class="input-field border border-slate-300 font-black text-red-600 text-lg w-full">
                </div>
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">નોંધ</label>
                    <input type="text" name="note" id="edit_note" class="input-field border border-slate-300 gujarati-text w-full">
                </div>
                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="document.getElementById('editAdvanceModal').classList.add('hidden')" class="flex-1 px-6 py-3 rounded-xl border border-border-light font-bold text-sm text-text-secondary hover:bg-background transition-all">કેન્સલ</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all">અપડેટ કરો</button>
                </div>
            </form>
        </div>
    </div>
    @endpush

    @push('footer')
    <script>
        function editAdvance(advance) {
            document.getElementById('edit_worker_id').value = advance.worker_id;
            document.getElementById('edit_date').value = advance.date;
            document.getElementById('edit_amount').value = advance.amount;
            document.getElementById('edit_note').value = advance.note || '';
            document.getElementById('editAdvanceForm').action = '/advances/' + advance.id;
            document.getElementById('editAdvanceModal').classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>
