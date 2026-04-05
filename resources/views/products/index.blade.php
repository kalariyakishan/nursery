<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <style>
        .dataTables_wrapper .dataTables_filter { display: none; }
        .dataTables_wrapper .dataTables_length { @apply hidden md:block; }
        .dataTables_wrapper .dataTables_length select {
            @apply rounded-lg border-border-light text-[10px] font-bold px-3 py-1 focus:ring-primary/20 bg-background/50 uppercase tracking-widest outline-none;
        }
        .dataTables_wrapper .dataTables_info {
            @apply text-[10px] font-bold text-text-secondary uppercase tracking-[0.1em] opacity-50 !important;
        }
        .dataTables_wrapper .dataTables_paginate {
            @apply flex items-center gap-1 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply rounded-lg border-none px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-text-secondary hover:bg-primary/5 hover:text-primary transition-all !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            @apply bg-primary/10 text-primary border border-primary/10 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            @apply opacity-30 cursor-not-allowed !important;
        }
        .dataTables_wrapper .bottom {
            @apply flex flex-col md:flex-row justify-between items-center px-8 py-6 border-t border-border-light/40 gap-6;
        }
        table.dataTable thead th { 
            @apply px-8 py-6 border-none !important;
            background-image: none !important; 
        }
        table.dataTable.no-footer { border-bottom: none !important; }
        #productsTable_processing {
            background: white !important;
            @apply shadow-premium border border-primary/20 rounded-xl font-bold text-primary gujarati-text !important;
            z-index: 50;
        }
    </style>
    @endpush

    <div class="mb-10 flex flex-col md:flex-row md:justify-between md:items-end gap-6">
        <div>
            <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tight mb-2">પ્રોડક્ટ મેનેજમેન્ટ (Inventory)</h2>
            <p class="text-text-secondary font-semibold tracking-wider text-xs uppercase opacity-70">બધા છોડ અને વેરિઅન્ટ્સનું સંચાલન</p>
        </div>
        <a href="{{ route('products.create') }}" class="primary-btn px-10 py-4 gujarati-text shadow-xl shadow-primary/20">
            <span class="material-symbols-outlined">add_circle</span>
            નવી પ્રોડક્ટ ઉમેરો
        </a>
    </div>



    <!-- Bulk Import Section -->
    <div class="mb-8 p-8 bg-white rounded-2xl shadow-premium border border-primary/10">
        <!-- Top Row: Icon, Title and Actions -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="flex items-center gap-6">
                <div class="flex-shrink-0 w-20 h-20 rounded-2xl bg-primary/10 flex items-center justify-center text-primary border-4 border-white shadow-xl shadow-primary/10">
                    <span class="material-symbols-outlined text-[40px]" style="font-variation-settings: 'FILL' 1;">upload_file</span>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-primary-dark gujarati-text leading-tight">બલ્ક ઇમ્પોર્ટ (Bulk Product Import)</h3>
                    <p class="text-sm font-semibold text-text-secondary mt-1 uppercase tracking-wider opacity-60">CSV ફાઇલ દ્વારા એકસાથે અનેક પ્રોડક્ટ્સ ઉમેરો</p>
                </div>
            </div>

            <div class="flex items-center gap-4 w-full md:w-auto">
                <a href="{{ asset('sample_products.csv') }}" download class="secondary-btn text-xs px-4 py-3 border-dashed border-2 hover:border-primary hover:text-primary group">
                    <span class="material-symbols-outlined text-[18px] group-hover:animate-bounce">download</span>
                    નમૂનો ડાઉનલોડ કરો (SAMPLE.CSV)
                </a>
                
                <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center">
                    @csrf
                    <div class="relative">
                        <input type="file" name="csv_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                        <div class="bg-background border border-border-light rounded-lg px-4 py-3 text-xs font-bold text-text-secondary flex items-center gap-2 group-hover:border-primary transition-colors">
                            <span class="material-symbols-outlined text-[16px]">file_open</span>
                            ફાઇલ પસંદ કરો (CHOOSE FILE)
                        </div>
                    </div>
                    <button type="submit" class="primary-btn h-12 px-6 shadow-lg shadow-primary/10">
                        ઇમ્પોર્ટ (IMPORT NOW)
                    </button>
                </form>
            </div>
        </div>

        <!-- Bottom Row: Help Steps -->
        <div class="mt-8 pt-6 border-t border-border-light/30 grid grid-cols-2 md:grid-cols-4 gap-4 opacity-60">
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center font-black text-[10px]">1</span>
                <p class="text-[10px] font-bold text-text-secondary gujarati-text">સેમ્પલ CSV ડાઉનલોડ કરો</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center font-black text-[10px]">2</span>
                <p class="text-[10px] font-bold text-text-secondary gujarati-text">Excel માં વિગત લખો</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center font-black text-[10px]">3</span>
                <p class="text-[10px] font-bold text-text-secondary gujarati-text">ફાઇલ પસંદ કરો (Choose File)</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="w-5 h-5 rounded-full bg-primary/10 text-primary flex items-center justify-center font-black text-[10px]">4</span>
                <p class="text-[10px] font-bold text-text-secondary gujarati-text">ઇમ્પોર્ટ બટન દબાવો</p>
            </div>
        </div>
    </div> <!-- End of Bulk Import Card -->

    <!-- Ajax Filters -->

    <!-- Ajax Filters -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-white rounded-xl shadow-premium border border-border-light/40">
        <div class="md:col-span-3">
            <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">પ્રોડક્ટ શોધો (SEARCH PRODUCT)</label>
            <input type="text" id="customSearch" class="input-field text-sm font-bold gujarati-text px-4" placeholder="પ્રોડક્ટ નું નામ કે આઈડી શોધો...">
        </div>
        <div class="flex items-end gap-2">
            <button id="filterBtn" class="primary-btn flex-1 h-12">
                <span class="material-symbols-outlined text-[18px]">search</span>
                ફિલ્ટર (FILTER)
            </button>
            <button id="resetBtn" class="secondary-btn h-12 p-3 text-red-600 hover:bg-red-50">
                <span class="material-symbols-outlined text-[20px]">refresh</span>
            </button>
        </div>
    </div>

    <div class="card-surface shadow-premium">
        <div class="overflow-x-auto no-scrollbar">
            <table id="productsTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-background/50 border-b border-border-light text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em]">
                        <th class="px-8 py-6">પ્રોડક્ટ નું નામ</th>
                        <th class="px-8 py-6">વેરિઅન્ટ વિગતો (Height | Bag | Rate)</th>
                        <th class="px-8 py-6 text-right">ક્રિયાઓ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-light/40">
                    <!-- Loaded via Ajax -->
                </tbody>
            </table>
        </div>
    </div>

    @push('footer')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#productsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('products.data') }}",
                    data: function (d) {
                        d.search_value = $('#customSearch').val();
                    }
                },
                columns: [
                    { 
                        data: 'name',
                        render: function (data, type, row) {
                            return `
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-lg bg-primary/5 text-primary flex items-center justify-center font-black text-xl border border-primary/10">
                                        ${row.name.charAt(0)}
                                    </div>
                                    <div>
                                        <span class="text-lg font-black text-text-primary gujarati-text">${row.name}</span>
                                        <p class="text-[10px] font-bold text-text-secondary opacity-40 uppercase tracking-widest mt-1">PID-${row.id}</p>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    { 
                        data: 'variants',
                        orderable: false
                    },
                    { 
                        data: 'actions', 
                        className: 'text-right',
                        orderable: false, 
                        searchable: false 
                    }
                ],
                order: [[0, 'asc']],
                pageLength: 10,
                language: {
                    processing: "લોડ થઈ રહ્યું છે...",
                    lengthMenu: "_MENU_",
                    zeroRecords: "કોઈ પ્રોડક્ટ મળી નથી",
                    info: "_TOTAL_ માંથી _START_ - _END_",
                    infoEmpty: "0 પ્રોડક્ટ",
                    infoFiltered: "(_MAX_ માંથી)",
                    paginate: {
                        first: "પ્રથમ",
                        last: "અંતિમ",
                        next: "આગળ",
                        previous: "પાછળ"
                    }
                },
                dom: '<"top"r>t<"bottom"ip><"clear">',
                createdRow: function(row, data, dataIndex) {
                    $(row).addClass('hover:bg-background/20 transition-colors group');
                }
            });

            $('#filterBtn').on('click', function() {
                table.draw();
            });

            $('#resetBtn').on('click', function() {
                $('#customSearch').val('');
                table.draw();
            });

            $('#customSearch').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    table.draw();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
