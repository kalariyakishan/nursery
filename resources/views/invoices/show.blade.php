@if(isset($isPdf) && $isPdf)
    @extends('layouts.print')
    @section('content')
        @include('invoices.template')
    @endsection
@else
    <x-app-layout>
        <!-- Dynamic Print Styles for Browser Print -->
        <style id="dynamic-page-style">
            @page {
                size: A4;
                margin: 5mm;
            }
        </style>

        <style>
            @media print {
                /* Structurally hide navigation instead of visibility: hidden (which causes blank pages out of bounds) */
                aside, header, .no-print {
                    display: none !important;
                }

                /* Reset Tailwind's layout wrappers so they don't pad or restrict width */
                html, body, main {
                    margin: 0 !important;
                    padding: 0 !important;
                    width: 100% !important;
                    height: auto !important;
                    min-height: auto !important;
                    background: white !important;
                    display: block !important;
                }

                /* Remove flex formatting from parent columns that restricts printing */
                .w-full.md\:w-3\/4.lg\:w-\[78\%\] {
                    width: 100% !important;
                    display: block !important;
                }

                /* Override preview scale transformations and let it be naturally 100% */
                #capture-area {
                    position: relative !important;
                    transform: none !important;
                    border: none !important;
                    box-shadow: none !important;
                    width: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    left: 0 !important;
                    top: 0 !important;
                }

                /* Eliminate any tailwind overflow-hidden masks */
                * {
                    overflow: visible !important;
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }
            }
        </style>

        <div x-data="{ paperSize: 'a4' }" 
         x-init="
            const updatePrintStyle = (size) => {
                const el = document.getElementById('dynamic-page-style');
                el.innerText = `@page { size: ${size.toUpperCase()}; margin: 5mm; }`;
            };
            $watch('paperSize', val => updatePrintStyle(val));
            updatePrintStyle(paperSize);
         " class="min-h-screen">

            <div class="mb-4 flex flex-col md:flex-row md:justify-between md:items-end gap-6 no-print">
                <div class="p-4">
                    <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tighter mb-2">બિલ વિગત (Bill
                        Details)</h2>
                    <p class="text-text-secondary font-bold tracking-widest text-xs uppercase opacity-60">ID
                        #INV-{{ $invoice->id }}</p>
                </div>
                <div class="flex gap-4 p-4">
                    <a href="{{ route('invoices.index') }}" class="secondary-btn rounded-xl">
                        <span class="material-symbols-outlined">arrow_back</span> પાછા જાવ
                    </a>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-8 items-start mb-10 px-4">
                <!-- Left: Sidebar Settings -->
                <div class="w-full md:w-1/4 lg:w-[22%] space-y-4 no-print md:sticky md:top-40">
                    <div
                        class="bg-white/80 backdrop-blur-sm p-5 rounded-[2rem] border-2 border-primary/10 shadow-xl shadow-primary/5 space-y-6">

                        <form action="{{ route('invoices.pdf', $invoice->id) }}" method="GET" target="_blank">
                            <!-- Step 1: Paper Size -->
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20 font-black text-sm">
                                    1
                                </div>
                                <div class="flex flex-col flex-1">
                                    <label class="text-[9px] font-black uppercase tracking-[0.2em] text-primary mb-0.5">પેપર
                                        સાઈઝ (PAPER)</label>
                                    <select name="paper_size" x-model="paperSize"
                                        class="bg-slate-50 border-none rounded-xl p-1.5 font-black text-xs text-text-primary focus:ring-2 focus:ring-primary/20 cursor-pointer w-full">
                                        <option value="a4">A4 (Standard Size)</option>
                                        <option value="a5">A5 (Small Size)</option>
                                        <option value="letter">Letter Size</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Step 2: Print Actions -->
                            <div class="border-t border-primary/10 pt-6 space-y-3 mt-6">
                                <div class="flex items-center gap-3 mb-1">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/20 font-black text-sm">
                                        2
                                    </div>
                                    <div class="flex flex-col">
                                        <label
                                            class="text-[9px] font-black uppercase tracking-[0.2em] text-primary leading-none mb-1">કન્ફર્મ
                                            કરો</label>
                                        <p class="text-[10px] font-bold text-text-secondary opacity-60 leading-none">બધું જ
                                            રેડી છે!</p>
                                    </div>
                                </div>
                                <button type="button" onclick="window.print()"
                                    class="primary-btn w-full py-4 rounded-xl shadow-xl shadow-primary/30 flex items-center justify-center gap-2 text-md mb-2">
                                    <span class="material-symbols-outlined font-bold text-[20px]">print</span>
                                    પ્રિન્ટ (PRINT)
                                </button>
                                <!--
                                <button type="submit"
                                    class="w-full py-3 rounded-xl border border-primary/30 text-primary font-bold flex items-center justify-center gap-2 text-sm hover:bg-primary/5 transition-all">
                                    <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                                    PDF Download
                                </button>
                                -->
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Right: Preview Area (Content Area) -->
                <div class="w-full md:w-3/4 lg:w-[78%]">
                    <div
                        class="flex justify-center items-start bg-slate-100/50 py-8 rounded-[2.5rem] border-2 border-dashed border-primary/10 overflow-hidden print:bg-transparent print:p-0 print:border-none min-h-[900px]">

                        <div class="printable-bill transition-all duration-500 ease-in-out bg-white shadow-[0_30px_60px_-15px_rgba(0,0,0,0.15)] ring-1 ring-black/5 origin-top scale-[0.65] lg:scale-[0.85] print:scale-100 print:shadow-none print:ring-0 print:border-none"
                            id="capture-area">
                            <div id="print-area">
                                @if(isset($isPdf) && $isPdf)
                                    @include('invoices.template')
                                @else
                                    @include('invoices.print_template')
                                @endif
                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </x-app-layout>
@endif