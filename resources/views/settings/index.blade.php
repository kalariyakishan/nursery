<x-app-layout>
    <div x-data="{ 
        enabled: {{ $settings['gst_enabled'] == '1' ? 'true' : 'false' }},
        type: '{{ $settings['gst_type'] }}',
        cgst: {{ $settings['cgst_percentage'] }},
        sgst: {{ $settings['sgst_percentage'] }},
        previewSubtotal: 1000,

        get totalGst() {
            return (parseFloat(this.cgst) || 0) + (parseFloat(this.sgst) || 0);
        },

        get gstAmount() {
            if (!this.enabled || !this.totalGst) return 0;
            if (this.type === 'inclusive') {
                return this.previewSubtotal - (this.previewSubtotal / (1 + (this.totalGst / 100)));
            }
            return this.previewSubtotal * (this.totalGst / 100);
        },
        
        get calcCgst() {
            return this.gstAmount * ((this.cgst || 0) / (this.totalGst || 1));
        },
        
        get calcSgst() {
            return this.gstAmount * ((this.sgst || 0) / (this.totalGst || 1));
        },

        get finalTotal() {
            if (this.type === 'inclusive' || !this.enabled) return this.previewSubtotal;
            return this.previewSubtotal + this.gstAmount;
        }
    }" class="w-full px-4 py-8">
        
        <!-- Page Title -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-slate-800 gujarati-text tracking-tighter">GST સેટિંગ્સ સંચાલન (Tax Management)</h2>
            <div class="w-20 h-1.5 bg-primary mx-auto mt-2 rounded-full"></div>
        </div>

        <form action="{{ route('settings.update') }}" method="POST" id="settingsForm" class="max-w-[1600px] mx-auto">
            @csrf
            
            <div class="flex flex-col md:flex-row gap-10 items-start">
                
                <!-- Settings Area (Left) -->
                <div class="w-full md:w-[60%] space-y-8">
                    
                    <!-- MASTER TOGGLE -->
                    <div class="bg-white border-2 border-slate-100 rounded-[2.5rem] p-8 shadow-sm">
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-6">
                            <div class="flex items-center gap-5">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all shadow-inner"
                                    :class="enabled ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-400'">
                                    <span class="material-symbols-outlined text-[30px]" x-text="enabled ? 'verified' : 'block'"></span>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900 gujarati-text">GST ગણતરી ચાલુ રાખવી?</h3>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Master Enable/Disable</p>
                                </div>
                            </div>

                            <div class="flex bg-slate-100 p-1.5 rounded-2xl border border-slate-200">
                                <button type="button" @click="enabled = true" 
                                    class="px-8 py-3 rounded-xl font-black text-xs transition-all flex items-center justify-center"
                                    :class="enabled ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'text-slate-500 hover:bg-slate-200'">
                                    ON (ચાલુ)
                                </button>
                                <button type="button" @click="enabled = false" 
                                    class="px-8 py-3 rounded-xl font-black text-xs transition-all flex items-center justify-center"
                                    :class="!enabled ? 'bg-red-500 text-white shadow-lg shadow-red-200' : 'text-slate-500 hover:bg-slate-200'">
                                    OFF (બંધ)
                                </button>
                            </div>
                            <input type="hidden" name="gst_enabled" :value="enabled ? '1' : '0'">
                        </div>
                    </div>

                    <div class="space-y-8 transition-all duration-500" :class="!enabled ? 'opacity-30 grayscale blur-[1px] pointer-events-none' : ''">
                        
                        <!-- Mode Selector -->
                        <div class="bg-white border-2 border-slate-100 rounded-[2.5rem] p-8 shadow-sm">
                            <label class="text-[12px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 block border-b pb-4">૧. ટેક્સ મોડ પસંદ કરો (Selection)</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <button type="button" @click="type = 'exclusive'" 
                                    class="flex items-center justify-between p-6 rounded-2xl border-2 transition-all"
                                    :class="type === 'exclusive' ? 'border-primary bg-primary/5' : 'border-slate-50'">
                                    <div class="flex items-center gap-4 text-left">
                                        <span class="material-symbols-outlined" :class="type === 'exclusive' ? 'text-primary' : 'text-slate-300'">add_circle</span>
                                        <div class="font-black text-slate-700 uppercase text-xs tracking-tight">Exclusive (Extra)</div>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center" :class="type === 'exclusive' ? 'border-primary bg-primary' : 'border-slate-200'">
                                        <span class="material-symbols-outlined text-white text-[16px]">check</span>
                                    </div>
                                </button>

                                <button type="button" @click="type = 'inclusive'" 
                                    class="flex items-center justify-between p-6 rounded-2xl border-2 transition-all"
                                    :class="type === 'inclusive' ? 'border-primary bg-primary/5' : 'border-slate-50'">
                                    <div class="flex items-center gap-4 text-left">
                                        <span class="material-symbols-outlined" :class="type === 'inclusive' ? 'text-primary' : 'text-slate-300'">adjust</span>
                                        <div class="font-black text-slate-700 uppercase text-xs tracking-tight">Inclusive (In-Price)</div>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center" :class="type === 'inclusive' ? 'border-primary bg-primary' : 'border-slate-200'">
                                        <span class="material-symbols-outlined text-white text-[16px]">check</span>
                                    </div>
                                </button>
                            </div>
                            <input type="hidden" name="gst_type" :value="type">
                        </div>

                        <!-- Manual % Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="bg-white border-2 border-slate-100 rounded-[2.5rem] p-8 shadow-sm space-y-4">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block">૨. CGST (%)</label>
                                <div class="flex items-center gap-4">
                                    <input type="number" name="cgst_percentage" step="0.01" min="0" max="100" x-model.number="cgst"
                                        class="flex-1 h-14 bg-slate-50 border-2 border-slate-200 rounded-xl px-4 text-xl font-black text-slate-800 focus:border-primary">
                                    <div class="w-14 h-14 bg-primary text-white rounded-xl flex items-center justify-center font-black text-xl shadow-lg shadow-primary/20">%</div>
                                </div>
                            </div>
                            <div class="bg-white border-2 border-slate-100 rounded-[2.5rem] p-8 shadow-sm space-y-4">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest block">૩. SGST (%)</label>
                                <div class="flex items-center gap-4">
                                    <input type="number" name="sgst_percentage" step="0.01" min="0" max="100" x-model.number="sgst"
                                        class="flex-1 h-14 bg-slate-50 border-2 border-slate-200 rounded-xl px-4 text-xl font-black text-slate-800 focus:border-primary">
                                    <div class="w-14 h-14 bg-primary text-white rounded-xl flex items-center justify-center font-black text-xl shadow-lg shadow-primary/20">%</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Area (Right) -->
                <div class="w-full md:w-[40%] md:sticky md:top-8">
                    <div class="bg-slate-50 border-2 border-slate-200 rounded-[3rem] p-10 shadow-sm relative overflow-hidden transition-all" :class="!enabled ? 'bg-slate-100' : ''">
                        <div class="relative z-10 space-y-10">
                            <div class="flex justify-between items-center border-b-2 border-slate-200 pb-6">
                                <div>
                                    <h4 class="text-2xl font-black text-slate-900 gujarati-text tracking-tighter">લાઇવ પ્રિવ્યુ (Calculated)</h4>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Based on ₹1,000 Sample</p>
                                </div>
                                <template x-if="enabled">
                                    <div class="flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                                        <span class="text-[10px] font-black tracking-widest uppercase">Active</span>
                                    </div>
                                </template>
                            </div>

                            <div class="space-y-6">
                                <div class="flex justify-between items-center bg-white p-5 rounded-2xl border border-slate-200">
                                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest">CGST Amount</span>
                                    <span class="text-xl font-black text-slate-900" x-text="'₹ ' + (enabled ? calcCgst.toFixed(2) : '0.00')"></span>
                                </div>
                                <div class="flex justify-between items-center bg-white p-5 rounded-2xl border border-slate-200">
                                    <span class="text-[11px] font-black text-slate-500 uppercase tracking-widest">SGST Amount</span>
                                    <span class="text-xl font-black text-slate-900" x-text="'₹ ' + (enabled ? calcSgst.toFixed(2) : '0.00')"></span>
                                </div>
                                <div class="p-6 rounded-2xl border-2 flex justify-between items-center shadow-sm"
                                    :class="enabled ? 'bg-primary text-white border-primary shadow-primary/20' : 'bg-slate-100 text-slate-400 border-slate-200'">
                                    <span class="text-[12px] font-black uppercase tracking-widest">Total GST (<span x-text="enabled ? totalGst : 0"></span>%)</span>
                                    <span class="text-2xl font-black" x-text="'₹ ' + (enabled ? gstAmount.toFixed(2) : '0.00')"></span>
                                </div>
                            </div>

                            <div class="pt-8 border-t-2 border-dashed border-slate-200 text-center">
                                <span class="text-[11px] font-black text-slate-400 uppercase tracking-widest block mb-2">Grand Payable Total</span>
                                <div class="text-5xl font-black text-slate-900 tracking-tighter mb-8" x-text="'₹ ' + (enabled ? finalTotal : 1000).toLocaleString('en-IN', {minimumFractionDigits: 2})"></div>
                                
                                <p class="text-[11px] font-bold text-slate-500 gujarati-text leading-relaxed bg-white/50 p-4 rounded-xl border border-white">
                                    <template x-if="!enabled">
                                        <span>GST ગણતરી બંધ છે, માત્ર મૂળ કિંમત લાગુ થશે.</span>
                                    </template>
                                    <template x-if="enabled">
                                        <span>
                                            <span x-show="type === 'inclusive'">નોંધ: કિંમત (₹1,000) માં ટેક્સ સામેલ છે, ફાઇનલ રકમ બદલાશે નહીં.</span>
                                            <span x-show="type === 'exclusive'">નોંધ: કિંમત (₹1,000) ઉપરાંત ટેક્સ અલગથી ગણાશે.</span>
                                        </span>
                                    </template>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FINAL BOTTOM SAVE BUTTON -->
            <div class="mt-16 text-center pb-20">
                <button type="submit" class="primary-btn inline-flex items-center justify-center gap-4 px-24 py-6 rounded-[2.5rem] shadow-2xl shadow-primary/30 transition-all hover:scale-[1.02] active:scale-95">
                    <span class="material-symbols-outlined text-[30px]">save_as</span>
                    <span class="text-2xl font-black gujarati-text tracking-tighter">માહિતી સાચવો (SAVE SETTINGS)</span>
                </button>
            </div>
        </form>
    </div>

    <style>
        .gujarati-text { font-family: 'Hind Vadodara', sans-serif; }
    </style>
</x-app-layout>