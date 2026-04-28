<x-app-layout>
    <div class="w-full px-4 py-8">
        
        <!-- Page Title -->
        <div class="text-center mb-10">
            <h2 class="text-3xl font-black text-slate-800 gujarati-text tracking-tighter">Google Sheets Integration</h2>
            <div class="w-20 h-1.5 bg-primary mx-auto mt-2 rounded-full"></div>
        </div>

        <div class="max-w-[1000px] mx-auto space-y-8">
            
            @if(session('success'))
                <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl border border-emerald-200 font-bold mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 text-red-700 p-4 rounded-xl border border-red-200 font-bold mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white border-2 border-slate-100 rounded-[2.5rem] p-10 shadow-sm">
                
                <div class="flex items-center gap-6 mb-8 border-b-2 border-slate-100 pb-8">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/30/Google_Sheets_logo_%282014-2020%29.svg" alt="Google Sheets" class="w-16 h-16">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Connect Google Sheets</h3>
                        <p class="text-sm font-bold text-slate-400 mt-1">Export your data automatically to Google Sheets.</p>
                    </div>
                </div>

                @if($integration)
                    <!-- Connected State -->
                    <div class="space-y-8">
                        <div class="flex flex-wrap gap-4 items-center justify-between bg-emerald-50/50 p-6 rounded-2xl border-2 border-emerald-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[24px]">check_circle</span>
                                </div>
                                <div>
                                    <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Connected Account</p>
                                    <p class="text-lg font-black text-slate-800">{{ $integration->google_email }}</p>
                                </div>
                            </div>
                            <form action="{{ route('google.sync.disconnect') }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-2.5 bg-red-50 text-red-600 font-bold rounded-xl hover:bg-red-100 transition-colors border border-red-200 text-sm">
                                    Disconnect Account
                                </button>
                            </form>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Auto Sync Toggle -->
                            <div class="bg-slate-50 p-6 rounded-2xl border-2 border-slate-100">
                                <div class="flex justify-between items-center mb-4">
                                    <div>
                                        <h4 class="font-black text-slate-800">Auto Sync (Daily)</h4>
                                        <p class="text-xs text-slate-500 font-medium mt-1">Sync data every night via cronjob.</p>
                                    </div>
                                    <form action="{{ route('google.sync.toggle_auto') }}" method="POST">
                                        @csrf
                                        <button type="submit" name="auto_sync" value="{{ $integration->auto_sync ? '0' : '1' }}" 
                                            class="w-14 h-8 rounded-full transition-colors relative"
                                            style="{{ $integration->auto_sync ? 'background-color: #10b981;' : 'background-color: #cbd5e1;' }}">
                                            <div class="w-6 h-6 bg-white rounded-full absolute top-1 transition-transform shadow-sm"
                                                style="{{ $integration->auto_sync ? 'transform: translateX(1.75rem);' : 'transform: translateX(0.25rem);' }}">
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Manual Sync Action (Generic) -->
                            <div class="bg-slate-50 p-6 rounded-2xl border-2 border-slate-100">
                                <div class="flex justify-between items-center h-full">
                                    <div>
                                        <h4 class="font-black text-slate-800">Generic Data Sync</h4>
                                        <p class="text-xs text-slate-500 font-medium mt-1">
                                            Sync basic system data.
                                        </p>
                                    </div>
                                    <form action="{{ route('google.sync.manual') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-6 py-2.5 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 transition-colors shadow-lg shadow-primary/20 text-sm flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px]">sync</span>
                                            Sync Now
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Rojmel Yearly Sync -->
                            <div class="bg-slate-50 p-6 rounded-2xl border-2 border-slate-100">
                                <div class="flex justify-between items-center h-full">
                                    <div>
                                        <h4 class="font-black text-slate-800">Rojmel Yearly Sync</h4>
                                        <p class="text-xs text-slate-500 font-medium mt-1">
                                            Sync full year cash book data.
                                        </p>
                                    </div>
                                    <form action="{{ route('google.sync.manual.rojmel') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200 text-sm flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px]">account_balance_wallet</span>
                                            Sync Rojmel
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Labour Report Sync -->
                            <div class="bg-slate-50 p-6 rounded-2xl border-2 border-slate-100">
                                <div class="flex justify-between items-center h-full">
                                    <div>
                                        <h4 class="font-black text-slate-800">Labour Report Sync</h4>
                                        <p class="text-xs text-slate-500 font-medium mt-1">
                                            Sync labour settlement data.
                                        </p>
                                    </div>
                                    <form action="{{ route('google.sync.manual.labour') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-6 py-2.5 bg-orange-500 text-white font-bold rounded-xl hover:bg-orange-600 transition-colors shadow-lg shadow-orange-200 text-sm flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px]">engineering</span>
                                            Sync Labour
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @if($integration->sheets->count() > 0)
                            <div class="space-y-3">
                                <h5 class="text-sm font-black text-slate-400 uppercase tracking-widest">Active Google Sheets</h5>
                                @foreach($integration->sheets as $sheet)
                                    <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-100 rounded-xl">
                                        <div class="flex items-center gap-3 text-blue-800">
                                            <span class="material-symbols-outlined">{{ $sheet->sheet_type == 'rojmel_yearly' ? 'account_balance_wallet' : ($sheet->sheet_type == 'labour_report' ? 'engineering' : 'table') }}</span>
                                            <div>
                                                <span class="font-bold text-sm block">{{ ucwords(str_replace('_', ' ', $sheet->sheet_type)) }}</span>
                                                <span class="text-[10px] opacity-70">Last synced: {{ $sheet->last_synced_at ? $sheet->last_synced_at->diffForHumans() : 'Never' }}</span>
                                            </div>
                                        </div>
                                        <a href="{{ $sheet->sheet_url }}" target="_blank" class="text-sm font-black text-blue-600 hover:text-blue-800 underline">
                                            Open Sheet
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                @else
                    <!-- Not Connected State -->
                    <div class="text-center py-10">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="material-symbols-outlined text-[40px] text-slate-300">cloud_off</span>
                        </div>
                        <h4 class="text-xl font-black text-slate-800 mb-2">No Account Connected</h4>
                        <p class="text-slate-500 font-medium max-w-sm mx-auto mb-8">
                            Connect your Google account to automatically sync your system data with Google Sheets.
                        </p>
                        <a href="{{ route('google.sync.redirect') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-white border-2 border-slate-200 text-slate-800 font-black rounded-2xl hover:border-slate-300 hover:bg-slate-50 transition-all shadow-sm">
                            <img src="https://www.google.com/favicon.ico" alt="Google" class="w-5 h-5">
                            Connect Google Account
                        </a>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</x-app-layout>
