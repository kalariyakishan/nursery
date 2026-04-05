<x-app-layout>
    @push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
        #invoicesTable_processing {
            background: white !important;
            @apply shadow-premium border border-primary/20 rounded-xl font-bold text-primary gujarati-text !important;
            z-index: 50;
        }
        /* Style the action buttons better */
        #invoicesTable .action-btn {
            @apply p-2 rounded-lg transition-all transform hover:-translate-y-0.5 inline-flex items-center justify-center;
        }
    </style>
    @endpush

    <div class="mb-10 flex flex-col md:flex-row md:justify-between md:items-end gap-6">
        <div>
            <h2 class="text-3xl font-black text-text-primary gujarati-text tracking-tight mb-2">ઇન્વોઇસ હિસ્ટ્રી (Invoice List)</h2>
            <p class="text-text-secondary font-semibold tracking-wider text-xs uppercase opacity-70">તાજેતરના તમામ વ્યવહારો અને બિલો</p>
        </div>
        <a href="{{ route('invoices.create') }}" class="primary-btn px-10 py-4 gujarati-text shadow-xl shadow-primary/20">
            <span class="material-symbols-outlined">receipt_long</span>
            નવું ઇન્વોઇસ
        </a>
    </div>



    <!-- Ajax Filters -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-4 gap-4 p-6 bg-white rounded-xl shadow-premium border border-border-light/40">
        <div>
            <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">શોધો (SEARCH)</label>
            <input type="text" id="customSearch" class="input-field text-sm font-bold gujarati-text px-4" placeholder="ગ્રાહક, ફોન કે આઈડી...">
        </div>
        <div class="md:col-span-2">
            <label class="text-[10px] font-bold text-text-secondary uppercase tracking-widest mb-1.5 block">સમયગાળો (DATE RANGE)</label>
            <div class="relative">
                <input type="text" id="dateRange" class="input-field text-sm font-bold px-4 pr-10" placeholder="તારીખ પસંદ કરો...">
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-text-secondary/40 text-[18px]">calendar_today</span>
            </div>
        </div>
        <div class="flex items-end gap-2">
            <button id="filterBtn" class="primary-btn flex-1 h-12">
                <span class="material-symbols-outlined text-[18px]">search</span>
                ફિલ્ટર
            </button>
            <button id="resetBtn" class="secondary-btn h-12 p-3 text-red-600 hover:bg-red-50">
                <span class="material-symbols-outlined text-[20px]">refresh</span>
            </button>
        </div>
    </div>

    <div class="card-surface shadow-premium">
        <div class="overflow-x-auto no-scrollbar">
            <table id="invoicesTable" class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-background/50 border-b border-border-light text-[10px] font-bold text-text-secondary uppercase tracking-[0.2em]">
                        <th class="px-8 py-6">ID / તારીખ (Date)</th>
                        <th class="px-8 py-6">ગ્રાહકની વિગત (Customer)</th>
                        <th class="px-8 py-6 text-right">કુલ રકમ (Total)</th>
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(document).ready(function() {
            var table = $('#invoicesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('invoices.data') }}",
                    data: function (d) {
                        d.search_value = $('#customSearch').val();
                        const drp = $('#dateRange').data('daterangepicker');
                        if (drp && drp.startDate && drp.endDate && $('#dateRange').val() !== "") {
                            d.start_date = drp.startDate.format('YYYY-MM-DD');
                            d.end_date = drp.endDate.format('YYYY-MM-DD');
                        }
                    }
                },
                columns: [
                    { 
                        data: 'date',
                        render: function (data, type, row) {
                            return `
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-primary/5 text-primary flex items-center justify-center font-bold text-xs border border-primary/10">
                                        #${row.id}
                                    </div>
                                    <div>
                                        <span class="text-sm font-black text-text-primary tracking-tight">${row.date}</span>
                                        <p class="text-[10px] font-bold text-text-secondary/60 uppercase tracking-widest mt-0.5">${row.time}</p>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    { 
                        data: 'customer_name',
                        render: function (data, type, row) {
                            return `
                                <div class="flex flex-col">
                                    <span class="text-lg font-black text-text-primary gujarati-text tracking-tight uppercase">${row.customer_name}</span>
                                    <span class="text-[10px] font-bold text-primary tracking-widest mt-0.5 opacity-60 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[12px]">phone</span>
                                        ${row.phone || '-'}
                                    </span>
                                </div>
                            `;
                        }
                    },
                    { 
                        data: 'total',
                        className: 'text-right',
                        render: function (data, type, row) {
                            return `
                                <span class="text-2xl font-black text-primary tracking-tighter">${row.total}</span>
                                <p class="text-[10px] font-bold text-text-secondary/40 uppercase tracking-widest mt-1">Paid Status: Confirmed</p>
                            `;
                        }
                    },
                    { 
                        data: 'actions', 
                        className: 'text-right',
                        orderable: false, 
                        searchable: false 
                    }
                ],
                order: [[0, 'desc']],
                pageLength: 10,
                language: {
                    processing: "લોડ થઈ રહ્યું છે...",
                    lengthMenu: "_MENU_",
                    zeroRecords: "કોઈ ઇન્વોઇસ મળી નથી",
                    info: "_TOTAL_ માંથી _START_ - _END_",
                    infoEmpty: "0 ઇન્વોઇસ",
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
                },
                drawCallback: function() {
                    // Re-bind any tools if needed
                }
            });

            const startRange = moment().subtract(29, 'days');
            const endRange = moment();

            $('#dateRange').daterangepicker({
                startDate: startRange,
                endDate: endRange,
                ranges: {
                   'આજે (Today)': [moment(), moment()],
                   'ગઈકાલ (Yesterday)': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   'છેલ્લા ૭ દિવસ': [moment().subtract(6, 'days'), moment()],
                   'છેલ્લા ૩૦ દિવસ': [moment().subtract(29, 'days'), moment()],
                   'આ મહિને': [moment().startOf('month'), moment().endOf('month')],
                   'ગયા મહિને': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                locale: {
                    format: 'DD/MM/YYYY',
                    applyLabel: "Apply",
                    cancelLabel: "Cancel",
                    fromLabel: "From",
                    toLabel: "To",
                    customRangeLabel: "Custom Range",
                    daysOfWeek: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
                    monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                    firstDay: 1
                },
                autoUpdateInput: true,
            });

            // Set initial value
            $('#dateRange').val(startRange.format('DD/MM/YYYY') + ' - ' + endRange.format('DD/MM/YYYY'));

            $('#dateRange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                table.draw();
            });

            $('#dateRange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                table.draw();
            });

            $('#filterBtn').on('click', function() {
                table.draw();
            });

            $('#resetBtn').on('click', function() {
                $('#customSearch').val('');
                $('#dateRange').data('daterangepicker').setStartDate(startRange);
                $('#dateRange').data('daterangepicker').setEndDate(endRange);
                $('#dateRange').val(startRange.format('DD/MM/YYYY') + ' - ' + endRange.format('DD/MM/YYYY'));
                table.draw();
            });

            // Enter key on search
            $('#customSearch').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    table.draw();
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
