<x-app-layout>
    <div x-data="labourSystem()">
        <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter cursor-pointer flex items-center gap-2" @click="window.location.href='{{ route('labour-entries.index') }}'">
                    <span class="material-symbols-outlined rounded-full bg-background p-2 hover:bg-border-light transition-all">arrow_back</span>
                    ડેઈલી હજરી / મજૂરી એન્ટ્રી
                </h2>
                <div class="flex items-center gap-3 mt-1 ml-12">
                    <span class="text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em]">Live Autosave Sync</span>
                    <span class="w-1 h-1 bg-green-500 rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold text-primary" x-text="'તારીખ: ' + formatDate(date)"></span>
                </div>
            </div>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('labour-entries.index') }}" 
                   class="bg-white text-text-secondary px-6 py-3 rounded-lg border border-border-light font-bold text-sm flex items-center gap-2 hover:bg-background transition-all cursor-pointer">
                    <span class="material-symbols-outlined text-[18px]">history</span>
                    હિસ્ટ્રી જુઓ
                </a>
                <div class="px-6 py-3 bg-white rounded-lg border border-border-light shadow-subtle flex items-center gap-3">
                    <span class="material-symbols-outlined text-[18px] text-primary">calendar_today</span>
                    <input type="date" x-model="date"
                        class="border-none p-0 text-xs font-bold focus:ring-0 cursor-pointer bg-transparent">
                </div>
            </div>
        </div>

        <div>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-12">
                <!-- Left Panel: Worker Selection List -->
                <div class="lg:col-span-1 bg-white rounded-xl shadow-premium border border-border-light overflow-hidden flex flex-col h-[500px] lg:h-[calc(100vh-140px)] lg:sticky lg:top-8">
                    <div class="p-4 bg-background border-b border-border-light">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-text-secondary/50 text-xl">search</span>
                            <input type="text" x-model="searchQuery" 
                                @keydown.arrow-down.prevent="moveDown()" 
                                @keydown.arrow-up.prevent="moveUp()" 
                                @keydown.enter.prevent="addActiveWorker()"
                                @input="activeWorkerIndex = 0"
                                class="input-field text-sm font-bold pl-10 focus:ring-primary focus:border-primary" 
                                placeholder="મજૂર શોધો (Search)..."
                                autofocus>
                        </div>
                    </div>
                    
                    <div class="overflow-y-auto flex-1 p-2 space-y-1" id="worker-scroll-container">
                        <template x-for="(worker, index) in filteredWorkers" :key="index">
                            <div @click="addWorker(worker)" 
                                 @dblclick="addWorker(worker)"
                                 class="worker-list-item p-3 rounded-lg cursor-pointer flex justify-between items-center transition-all border"
                                 :class="{'bg-primary/10 border-primary shadow-sm': activeWorkerIndex === index, 'border-transparent hover:bg-background': activeWorkerIndex !== index, 'opacity-40 cursor-not-allowed': isWorkerAdded(worker)}">
                                 <div>
                                     <div class="font-bold text-text-primary gujarati-text text-[15px]" :class="{'text-primary': activeWorkerIndex === index}" x-text="worker.name"></div>
                                     <div class="text-[10px] font-bold text-text-secondary mt-0.5">રોજ: ₹ <span x-text="worker.default_wage"></span></div>
                                 </div>
                                 <template x-if="isWorkerAdded(worker)">
                                     <span class="material-symbols-outlined text-[18px] text-green-500">check_circle</span>
                                 </template>
                            </div>
                        </template>
                        
                        <!-- Inline Quick Add -->
                        <div x-show="filteredWorkers.length === 0 && searchQuery.trim() !== ''" 
                             @click="addNewWorkerInline()"
                             class="p-6 text-center cursor-pointer bg-primary/5 hover:bg-primary/10 transition-all rounded-xl border border-dashed border-primary text-primary mt-2">
                            <span class="material-symbols-outlined block text-3xl mx-auto mb-2">person_add</span>
                            <span class="font-bold text-sm block mb-1">નવો મજૂર ઉમેરો</span>
                            <span class="font-black text-lg gujarati-text" x-text="searchQuery"></span>
                            <span class="text-[10px] block opacity-60 mt-2 uppercase tracking-widest">(Press Enter)</span>
                        </div>

                        <div x-show="filteredWorkers.length === 0 && searchQuery.trim() === ''" class="p-8 text-center text-text-secondary/40">
                            <span class="material-symbols-outlined text-4xl mb-2">group</span>
                            <p class="font-bold text-xs">કોઈ મજૂર મળ્યો નથી.</p>
                        </div>
                    </div>
                </div>

                <!-- Right Panel: Selected Workers for Today -->
                <div class="lg:col-span-3 bg-white rounded-xl shadow-premium border border-border-light flex flex-col lg:h-[calc(100vh-140px)] relative overflow-hidden">
                    
                    <!-- Headers -->
                    <div class="hidden md:grid grid-cols-12 bg-background border-b border-border-light px-6 py-4 sticky top-0 z-10 shadow-sm">
                        <div class="col-span-5 text-[10px] font-bold text-text-secondary uppercase tracking-widest">પસંદ કરેલ મજૂર (Selected Workers)</div>
                        <div class="col-span-3 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-center">હાજરી (Attendance)</div>
                        <div class="col-span-3 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-right">મજૂરી (Wage)</div>
                        <div class="col-span-1 text-[10px] font-bold text-text-secondary uppercase tracking-widest text-center">સ્ટેટસ</div>
                    </div>

                    <!-- Items Container -->
                    <div class="divide-y divide-border-light/40 overflow-y-auto flex-1 p-2 md:p-0 relative">
                        <!-- Empty State -->
                        <div x-show="items.length === 0" class="absolute inset-0 flex flex-col items-center justify-center text-text-secondary/30 pointer-events-none p-6 text-center">
                            <div class="w-24 h-24 bg-background rounded-full flex items-center justify-center mb-4 border-4 border-white shadow-sm">
                                <span class="material-symbols-outlined text-5xl text-primary/40">touch_app</span>
                            </div>
                            <p class="font-black text-xl gujarati-text text-text-primary/40 mb-1">મજૂર પસંદ કરો</p>
                            <p class="font-bold text-sm">ડાબી બાજુના લિસ્ટમાંથી મજૂર પસંદ કરો અથવા નવું નામ લખી Enter દબાવો.</p>
                        </div>

                        <template x-for="(item, index) in items" :key="item.worker_name">
                            <div class="p-4 md:px-6 md:py-4 grid grid-cols-1 md:grid-cols-12 gap-4 items-center hover:bg-background/30 transition-colors group relative" :class="{'opacity-50': item.status === 'deleting'}">
                                
                                <!-- Worker Info -->
                                <div class="col-span-5 flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-black text-sm shrink-0 border border-primary/20 shadow-sm" x-text="items.length - index"></div>
                                    <div>
                                        <div class="font-black text-text-primary gujarati-text text-xl" x-text="item.worker_name"></div>
                                        <div class="text-[10px] font-bold text-text-secondary mt-0.5">રોજની મજૂરી: ₹ <span x-text="item.default_wage"></span></div>
                                    </div>
                                </div>

                                <!-- Attendance Select -->
                                <div class="col-span-3 border-l border-border-light pl-4">
                                    <div class="md:hidden text-[10px] font-bold text-text-secondary uppercase mb-1">હાજરી</div>
                                    <select x-model="item.attendance_type"
                                        @change="calculateWage(index)"
                                        class="input-field text-sm font-bold focus:ring-primary focus:border-primary cursor-pointer text-center bg-background/50 border-transparent hover:border-border-light transition-all">
                                        <option value="full">આખો દિવસ (Full Day)</option>
                                        <option value="half">અડધો દિવસ (Half Day)</option>
                                    </select>
                                </div>

                                <!-- Wage Input -->
                                <div class="col-span-3">
                                    <div class="md:hidden text-[10px] font-bold text-text-secondary uppercase mb-1">મજૂરી રકમ</div>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-text-secondary/40 text-[10px] font-bold">₹</span>
                                        <input type="number" step="0.01" x-model.number="item.wage_amount"
                                            @input="wageChanged(index)"
                                            class="input-field text-right font-black text-primary pl-7 text-xl lg:text-2xl focus:ring-primary bg-background/50 border-transparent hover:border-border-light transition-all" placeholder="0.00">
                                    </div>
                                </div>

                                <!-- UX Status & Remove -->
                                <div class="col-span-1 flex items-center justify-center gap-2">
                                    <template x-if="item.status === 'saving'">
                                        <span class="material-symbols-outlined text-[18px] text-secondary animate-spin">sync</span>
                                    </template>
                                    <template x-if="item.status === 'saved'">
                                        <span class="material-symbols-outlined text-[18px] text-green-500 animate-pulse">check_circle</span>
                                    </template>
                                    <template x-if="item.status === 'error'">
                                        <button @click="triggerUpdate(index)" class="material-symbols-outlined text-[18px] text-red-500 hover:text-red-700" title="Retry">error</button>
                                    </template>
                                    <button type="button" @click="removeItem(index)" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-sm">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Footer Summary -->
                    <div class="p-6 bg-background border-t border-border-light flex justify-between items-center z-10 sticky bottom-0">
                        <div>
                            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block opacity-60">કુલ મજૂર (Total)</span>
                            <span class="text-3xl font-black text-text-primary" x-text="items.length"></span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] font-bold text-text-secondary uppercase tracking-widest block opacity-60">કુલ રકમ (Amount)</span>
                            <span class="text-3xl font-black text-primary tracking-tighter" x-text="'₹ ' + totalWage.toLocaleString('en-IN')"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('footer')
    <script>
        function labourSystem() {
            return {
                workers: @json($workers),
                items: [],
                date: '{{ $newDate ?? date('Y-m-d') }}',
                searchQuery: '',
                activeWorkerIndex: 0,
                
                init() {
                    const existingItems = @json($items ?? []);
                    if (existingItems.length > 0) {
                        this.items = existingItems.map(item => ({
                            id: null, // Since we are duplicating from past date, these are fresh additions
                            worker_id: item.worker_id || '',
                            worker_name: item.worker_name || '',
                            default_wage: item.default_wage || 0,
                            attendance_type: item.attendance_type || 'full',
                            wage_amount: item.wage_amount || 0,
                            status: ''
                        }));
                        
                        // We need to auto-save these items instantly since we loaded them from a past date
                        this.items.forEach((item, idx) => {
                            setTimeout(() => {
                                this.persistAdd(item);
                            }, idx * 100);
                        });
                    }
                },

                get filteredWorkers() {
                    if (this.searchQuery.trim() === '') {
                        return this.workers;
                    }
                    return this.workers.filter(w => w.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
                },

                moveDown() {
                    if (this.activeWorkerIndex < this.filteredWorkers.length - 1) {
                        this.activeWorkerIndex++;
                        this.scrollToActive();
                    }
                },

                moveUp() {
                    if (this.activeWorkerIndex > 0) {
                        this.activeWorkerIndex--;
                        this.scrollToActive();
                    }
                },

                scrollToActive() {
                    this.$nextTick(() => {
                        const container = document.getElementById('worker-scroll-container');
                        const activeEl = container.querySelector('.worker-list-item.bg-primary\\/10');
                        if (activeEl) {
                            activeEl.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                        }
                    });
                },

                addActiveWorker() {
                    if (this.filteredWorkers.length === 0 && this.searchQuery.trim() !== '') {
                        this.addNewWorkerInline();
                    } else if (this.filteredWorkers.length > 0) {
                        this.addWorker(this.filteredWorkers[this.activeWorkerIndex]);
                    }
                },

                isWorkerAdded(worker) {
                    return this.items.some(i => i.worker_name === worker.name);
                },

                addWorker(worker) {
                    if (this.isWorkerAdded(worker)) return;
                    
                    const newItem = {
                        id: null,
                        worker_id: worker.id,
                        worker_name: worker.name,
                        default_wage: worker.default_wage,
                        attendance_type: 'full',
                        wage_amount: worker.default_wage,
                        status: ''
                    };
                    
                    this.items.unshift(newItem);
                    this.searchQuery = '';
                    this.activeWorkerIndex = 0;
                    this.persistAdd(this.items[0]);
                },

                addNewWorkerInline() {
                    const name = this.searchQuery.trim();
                    if(!name) return;
                    if(this.items.some(i => i.worker_name === name)) return;
                    
                    const newItem = {
                        id: null,
                        worker_id: null,
                        worker_name: name,
                        default_wage: 0,
                        attendance_type: 'full',
                        wage_amount: 0,
                        status: ''
                    };
                    
                    this.items.unshift(newItem);
                    this.workers.unshift({ id: null, name: name, default_wage: 0 }); // Local optimistic add
                    
                    this.searchQuery = '';
                    this.activeWorkerIndex = 0;
                    this.persistAdd(this.items[0]);
                },

                async persistAdd(item) {
                    try {
                        item.status = 'saving';
                        const res = await fetch('{{ route('api.labour-entries.store') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({
                                date: this.date,
                                worker_id: item.worker_id,
                                worker_name: item.worker_name,
                                attendance_type: item.attendance_type,
                                wage_amount: item.wage_amount
                            })
                        });
                        const data = await res.json();
                        if (data.success) {
                            item.id = data.detail_id;
                            if(!item.worker_id) item.worker_id = data.worker_id;
                            item.status = 'saved';
                            setTimeout(() => { if(item.status === 'saved') item.status = ''; }, 2000);
                        } else {
                            item.status = 'error';
                        }
                    } catch (e) {
                         item.status = 'error';
                    }
                },

                async removeItem(index) {
                    const item = this.items[index];
                    item.status = 'deleting';
                    const detailId = item.id;
                    
                    // Optimistic UI removal
                    this.items.splice(index, 1);
                    
                    if (detailId) {
                        try {
                            await fetch(`/labour-entries/api/destroy/${detailId}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            });
                        } catch(e) {
                            // If it fails, realistically we should alert or revert, but silent is fine for nursery
                            console.error('Delete failed', e);
                        }
                    }
                },

                calculateWage(index) {
                    const item = this.items[index];
                    const wage = item.default_wage || 0;
                    if (item.attendance_type === 'full') {
                        item.wage_amount = wage;
                    } else if (item.attendance_type === 'half') {
                        item.wage_amount = wage / 2;
                    }
                    this.triggerUpdate(index);
                },
                
                wageChanged(index) {
                     this.triggerUpdate(index);
                },

                triggerUpdate(index) {
                    const item = this.items[index];
                    if(!item.id) {
                        // Not persisted yet, it will sync soon
                        return;
                    }
                    
                    item.status = 'saving';
                    clearTimeout(item.debounceTimer);
                    // Fast debounce for seamless sync
                    item.debounceTimer = setTimeout(() => {
                         this.persistUpdate(item);
                    }, 500);
                },

                async persistUpdate(item) {
                     try {
                        const res = await fetch(`/labour-entries/api/update/${item.id}`, {
                            method: 'PUT',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                            body: JSON.stringify({
                                attendance_type: item.attendance_type,
                                wage_amount: item.wage_amount
                            })
                        });
                        const data = await res.json();
                        if (data.success) {
                            item.status = 'saved';
                            setTimeout(() => { if(item.status === 'saved') item.status = ''; }, 2000);
                        } else {
                            item.status = 'error';
                        }
                     } catch(e) { 
                         item.status = 'error'; 
                     }
                },

                get totalWage() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.wage_amount) || 0), 0);
                },

                formatDate(dateStr) {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    return d.toLocaleDateString('gu-IN', { day: '2-digit', month: '2-digit', year: 'numeric' });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
