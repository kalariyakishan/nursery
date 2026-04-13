<x-app-layout>
    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter">મજૂર મેનેજમેન્ટ</h2>
            <p class="text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em] mt-1">Worker Management System</p>
        </div>
        <div class="flex gap-4">
            <button onclick="document.getElementById('addWorkerModal').classList.remove('hidden')" class="primary-btn px-8 shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined">person_add</span>
                નવો મજૂર ઉમેરો
            </button>
        </div>
    </div>

    <!-- Workers Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($workers as $worker)
            <div class="card-surface p-6 shadow-premium relative group overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-bl-[100px] -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
                
                <div class="flex items-start justify-between relative">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-black text-xl">
                            {{ substr($worker->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-text-primary gujarati-text">{{ $worker->name }}</h3>
                            <p class="text-xs font-bold text-text-secondary flex items-center gap-1">
                                <span class="material-symbols-outlined text-[14px]">call</span>
                                {{ $worker->phone ?: 'નંબર નથી' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between border-t border-border-light pt-4">
                    <div>
                        <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block opacity-60">રોજની મજૂરી</span>
                        <span class="text-lg font-black text-primary">₹ {{ number_format($worker->default_wage, 2) }}</span>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="editWorker({{ json_encode($worker) }})" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-all">
                            <span class="material-symbols-outlined text-[18px]">edit</span>
                        </button>
                        <form action="{{ route('workers.destroy', $worker) }}" method="POST" onsubmit="return confirm('શું તમે આ મજૂરને લિસ્ટમાંથી કાઢી નાખવા માંગો છો?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full card-surface p-12 text-center text-text-secondary/30">
                <span class="material-symbols-outlined text-6xl">group_off</span>
                <p class="gujarati-text font-bold mt-2">હજી સુધી કોઈ મજૂર ઉમેર્યા નથી.</p>
            </div>
        @endforelse
    </div>

    @push('modals')
    <!-- Add Worker Modal -->
    <div id="addWorkerModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 hidden p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-y-auto p-5">
            <div class="pb-4 mb-4 border-b border-border-light flex justify-between items-center bg-white sticky top-0 z-20">
                <h3 class="text-xl font-black text-text-primary gujarati-text">નવો મજૂર ઉમેરો</h3>
                <button type="button" onclick="document.getElementById('addWorkerModal').classList.add('hidden')" class="text-text-secondary hover:text-text-primary">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form action="{{ route('workers.store') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">મજૂરનું નામ</label>
                    <input type="text" name="name" required class="input-field border border-slate-300 gujarati-text font-bold w-full" placeholder="નામ લખો...">
                </div>
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">ફોન નંબર (વૈકલ્પિક)</label>
                    <input type="tel" name="phone" class="input-field border border-slate-300 font-bold w-full" placeholder="99245xxxxx">
                </div>
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">રોજની મજૂરી (Fixed Wage)</label>
                    <input type="number" step="0.01" name="default_wage" class="input-field border border-slate-300 font-black text-primary w-full" placeholder="0.00">
                </div>
                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="document.getElementById('addWorkerModal').classList.add('hidden')" class="flex-1 px-6 py-3 rounded-xl border border-border-light font-bold text-sm text-text-secondary hover:bg-background transition-all">કેન્સલ</button>
                    <button type="submit" class="flex-1 primary-btn py-3 shadow-lg shadow-primary/20">સાચવો (Store)</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Worker Modal -->
    <div id="editWorkerModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 hidden p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-y-auto p-5">
            <div class="pb-4 mb-4 border-b border-border-light flex justify-between items-center bg-white sticky top-0 z-20">
                <h3 class="text-xl font-black text-text-primary gujarati-text">મજૂરની વિગત સુધારો</h3>
                <button type="button" onclick="document.getElementById('editWorkerModal').classList.add('hidden')" class="text-text-secondary hover:text-text-primary">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <form id="editWorkerForm" method="POST" class="flex flex-col gap-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">મજૂરનું નામ</label>
                    <input type="text" name="name" id="edit_name" required class="input-field border border-slate-300 gujarati-text font-bold w-full">
                </div>
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">ફોન નંબર (વૈકલ્પિક)</label>
                    <input type="tel" name="phone" id="edit_phone" class="input-field border border-slate-300 font-bold w-full">
                </div>
                <div>
                    <label class="text-xs font-bold text-text-secondary uppercase tracking-widest mb-2 block">રોજની મજૂરી (Fixed Wage)</label>
                    <input type="number" step="0.01" name="default_wage" id="edit_wage" class="input-field border border-slate-300 font-black text-primary w-full">
                </div>
                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="document.getElementById('editWorkerModal').classList.add('hidden')" class="flex-1 px-6 py-3 rounded-xl border border-border-light font-bold text-sm text-text-secondary hover:bg-background transition-all">કેન્સલ</button>
                    <button type="submit" class="flex-1 primary-btn py-3 shadow-lg shadow-primary/20">અપડેટ કરો</button>
                </div>
            </form>
        </div>
    </div>
    @endpush

    @push('footer')
    <script>
        function editWorker(worker) {
            document.getElementById('edit_name').value = worker.name;
            document.getElementById('edit_phone').value = worker.phone || '';
            document.getElementById('edit_wage').value = worker.default_wage || 0;
            document.getElementById('editWorkerForm').action = '/workers/' + worker.id;
            document.getElementById('editWorkerModal').classList.remove('hidden');
        }
    </script>
    @endpush
</x-app-layout>
