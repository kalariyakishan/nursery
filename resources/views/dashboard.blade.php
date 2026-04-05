<x-app-layout>
    <div class="mb-10 flex flex-col md:flex-row md:justify-between md:items-end gap-6 animate-fade-in">
        <div>
            <h2 class="text-3xl font-black text-primary-dark gujarati-text tracking-tight mb-2">ડેશબોર્ડ (Nursery Overview)</h2>
            <p class="text-text-secondary font-semibold tracking-wider text-xs uppercase opacity-70">વિગતવાર અહેવાલ અને પ્રવૃત્તિઓ</p>
        </div>
        <div class="bg-white px-6 py-4 rounded-2xl border border-primary/10 shadow-premium flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-[24px]" style="font-variation-settings: 'FILL' 1;">calendar_month</span>
            </div>
            <div>
                <p class="text-[10px] uppercase font-black text-primary/60 tracking-widest">આજની તારીખ (Today)</p>
                <p class="text-lg font-black text-primary-dark tracking-tight">{{ date('d F, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <!-- Total Invoices -->
        <div class="card-surface p-6 group hover:translate-y-[-6px] transition-all duration-500 border-b-4 border-primary">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all duration-300 shadow-sm">
                    <span class="material-symbols-outlined text-[28px]">receipt_long</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-primary px-3 py-1 bg-primary/5 rounded-full border border-primary/10 uppercase tracking-widest">Invoices</span>
                </div>
            </div>
            <h3 class="text-4xl font-black text-primary-dark mb-1 tracking-tighter">{{ number_format($stats['totalInvoices']) }}</h3>
            <p class="text-xs font-bold text-text-secondary gujarati-text opacity-70 uppercase tracking-widest">કુલ ઇન્વોઇસ (Total Bills)</p>
        </div>

        <!-- Total Sales -->
        <div class="card-surface p-6 group hover:translate-y-[-6px] transition-all duration-500 border-b-4 border-amber-500">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-600 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    <span class="material-symbols-outlined text-[28px]">payments</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-amber-600 px-3 py-1 bg-amber-500/5 rounded-full border border-amber-500/10 uppercase tracking-widest">Revenue</span>
                </div>
            </div>
            <h3 class="text-4xl font-black text-primary-dark mb-1 tracking-tighter">₹ {{ number_format($stats['totalSales'] / 1000, 1) }}k</h3>
            <p class="text-xs font-bold text-text-secondary gujarati-text opacity-70 uppercase tracking-widest">કુલ વેચાણ (Total Revenue)</p>
        </div>

        <!-- Total Plants -->
        <div class="card-surface p-6 group hover:translate-y-[-6px] transition-all duration-500 border-b-4 border-emerald-500">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-600 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    <span class="material-symbols-outlined text-[28px]">potted_plant</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-emerald-600 px-3 py-1 bg-emerald-500/5 rounded-full border border-emerald-500/10 uppercase tracking-widest">Stock Items</span>
                </div>
            </div>
            <h3 class="text-4xl font-black text-primary-dark mb-1 tracking-tighter">{{ number_format($stats['totalPlants']) }}</h3>
            <p class="text-xs font-bold text-text-secondary gujarati-text opacity-70 uppercase tracking-widest">કુલ છોડ (Total Varieties)</p>
        </div>

        <!-- Total Customers -->
        <div class="card-surface p-6 group hover:translate-y-[-6px] transition-all duration-500 border-b-4 border-indigo-500">
            <div class="flex items-center justify-between mb-6">
                <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-600 group-hover:bg-indigo-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    <span class="material-symbols-outlined text-[28px]">group</span>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-black text-indigo-600 px-3 py-1 bg-indigo-500/5 rounded-full border border-indigo-500/10 uppercase tracking-widest">Clientele</span>
                </div>
            </div>
            <h3 class="text-4xl font-black text-primary-dark mb-1 tracking-tighter">{{ number_format($stats['totalCustomers']) }}</h3>
            <p class="text-xs font-bold text-text-secondary gujarati-text opacity-70 uppercase tracking-widest">કુલ ગ્રાહકો (Total Customers)</p>
        </div>
    </div>

    <!-- Data Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Recent Invoices -->
        <div class="lg:col-span-2 card-surface overflow-hidden shadow-premium">
            <div class="p-8 border-b border-border-light/50 flex justify-between items-center bg-primary/[0.01]">
                <h4 class="text-xl font-black text-primary-dark gujarati-text flex items-center gap-3">
                    <div class="w-2 h-8 bg-primary rounded-full"></div>
                    તાજેતરના ઇન્વોઇસ (Recent Sales)
                </h4>
                <a href="{{ route('invoices.index') }}" class="text-xs font-black text-primary hover:text-primary-dark uppercase tracking-widest flex items-center gap-2 group">
                    બધા જુઓ (View All)
                    <span class="material-symbols-outlined text-[16px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-primary/5 text-[10px] font-black text-primary uppercase tracking-[0.2em]">
                            <th class="py-4 px-8">Bill No</th>
                            <th class="py-4 px-4">Customer Name (ગ્રાહક)</th>
                            <th class="py-4 px-4">Amount (કુલ)</th>
                            <th class="py-4 px-4">Date (તારીખ)</th>
                            <th class="py-4 px-8 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-light/40">
                        @forelse($recentInvoices as $invoice)
                        <tr class="hover:bg-primary/[0.02] transition-colors group">
                            <td class="py-6 px-8">
                                <span class="font-black text-xs text-text-primary">#INV-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="py-6 px-4">
                                <p class="font-black text-lg gujarati-text text-primary-dark leading-none">{{ $invoice->customer_name }}</p>
                                <p class="text-[10px] text-text-secondary mt-1 font-bold">{{ $invoice->phone ?: 'No Phone' }}</p>
                            </td>
                            <td class="py-6 px-4 font-black text-xl text-primary">₹ {{ number_format($invoice->total, 2) }}</td>
                            <td class="py-6 px-4 font-bold text-xs text-text-secondary">{{ $invoice->created_at->format('d M, Y') }}</td>
                            <td class="py-6 px-8 text-right">
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-primary/10 text-primary hover:bg-primary hover:text-white transition-all shadow-sm">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-20 text-center">
                                <p class="text-text-secondary font-bold gujarati-text">કોઈ ડેટા ઉપલબ્ધ નથી (No Invoices Found)</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Inventory Summary -->
        <div class="card-surface overflow-hidden shadow-premium">
            <div class="p-8 border-b border-border-light/50 bg-primary-light/[0.01]">
                <h4 class="text-xl font-black text-primary-dark gujarati-text flex items-center gap-3">
                    <div class="w-2 h-8 bg-primary-light rounded-full"></div>
                    પ્રોડક્ટ્સ (Inventory)
                </h4>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentProducts as $product)
                    <div class="p-4 rounded-2xl bg-background border border-border-light/50 hover:border-primary-light/30 transition-all group">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center text-primary-light text-xl font-black">
                                    {{ substr($product->name, 0, 1) }}
                                </div>
                                <div>
                                    <h5 class="font-black gujarati-text text-primary-dark leading-tight">{{ $product->name }}</h5>
                                    <p class="text-[10px] font-bold text-text-secondary uppercase">Last Added: {{ $product->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('products.edit', $product->id) }}" class="opacity-0 group-hover:opacity-100 transition-opacity w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-text-secondary hover:text-primary">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                        </div>
                    </div>
                    @empty
                    <p class="text-center py-10 text-text-secondary font-bold">સ્ટોક ખાલી છે (Empty Inventory)</p>
                    @endforelse
                </div>
            </div>
            <div class="p-8 bg-background/50 border-t border-border-light/50">
                <a href="{{ route('products.index') }}" class="primary-btn w-full justify-center">
                    બધો સ્ટોક જુઓ (Manage Inventory)
                </a>
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-app-layout>
