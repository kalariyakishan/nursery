<x-app-layout>
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    @endpush

    <div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-end gap-4">
        <div>
            <h2 class="text-3xl font-black text-text-primary tracking-tight">Offer History</h2>
            <p class="text-xs uppercase tracking-widest text-text-secondary font-bold opacity-70">Search by customer and date</p>
        </div>
        <a href="{{ route('offers.create') }}" class="primary-btn">
            <span class="material-symbols-outlined">add</span>New Offer
        </a>
    </div>

    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-5 rounded-xl border border-border-light">
        <input type="text" id="searchBox" class="input-field" placeholder="Search customer / offer no / phone">
        <input type="text" id="dateRange" class="input-field md:col-span-2" placeholder="Date range">
        <div class="flex gap-2">
            <button id="applyFilter" class="primary-btn flex-1">Filter</button>
            <button id="clearFilter" class="secondary-btn">Reset</button>
        </div>
    </div>

    <div class="card-surface p-4">
        <table id="offersTable" class="w-full text-left">
            <thead>
                <tr class="text-xs uppercase tracking-widest text-text-secondary">
                    <th>Offer</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
        </table>
    </div>

    @push('footer')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script>
            $(function() {
                const start = moment().subtract(29, 'days');
                const end = moment();
                $('#dateRange').daterangepicker({
                    startDate: start,
                    endDate: end,
                    autoUpdateInput: true,
                    locale: { format: 'DD/MM/YYYY' }
                });

                const table = $('#offersTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('offers.data') }}",
                        data: function(d) {
                            d.search_value = $('#searchBox').val();
                            const drp = $('#dateRange').data('daterangepicker');
                            d.start_date = drp.startDate.format('YYYY-MM-DD');
                            d.end_date = drp.endDate.format('YYYY-MM-DD');
                        }
                    },
                    columns: [
                        { data: 'offer_no' },
                        {
                            data: 'customer_name',
                            render: function(data, type, row) {
                                return `<div><strong>${row.customer_name}</strong><div class="text-xs text-gray-500">${row.phone || '-'}</div></div>`;
                            }
                        },
                        { data: 'total' },
                        {
                            data: 'date',
                            render: function(data, type, row) {
                                return `${row.date}<div class="text-xs text-gray-500">${row.time}</div>`;
                            }
                        },
                        { data: 'actions', orderable: false, searchable: false, className: 'text-right' }
                    ]
                });

                $('#applyFilter').on('click', () => table.draw());
                $('#clearFilter').on('click', function() {
                    $('#searchBox').val('');
                    $('#dateRange').data('daterangepicker').setStartDate(start);
                    $('#dateRange').data('daterangepicker').setEndDate(end);
                    table.draw();
                });
                $('#searchBox').on('keyup', function(e) { if (e.key === 'Enter') table.draw(); });
            });
        </script>
    @endpush
</x-app-layout>
