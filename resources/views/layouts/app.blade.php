<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Nursery Dashboard') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Toastr CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
        
        @stack('styles')
    </head>
    <body class="bg-background text-text-primary font-sans antialiased min-h-screen" x-data="{ sidebarOpen: false }">
        
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Header -->
        @include('layouts.header')

        <!-- Main Content -->
        <main class="md:ml-64 pt-40 px-4 md:px-8 lg:px-12 pb-12 min-h-screen flex flex-col transition-all duration-300">
            <div class="max-w-[1600px] w-full flex-1 mx-auto">
                {{ $slot }}
            </div>
        </main>

        <!-- Sidebar Backdrop for Mobile -->
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             class="fixed inset-0 bg-black/50 z-40 md:hidden"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;"></div>

        <!-- jQuery & Toastr JS -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <script>
            $(document).ready(function() {
                // Toastr Configuration
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "300",
                    "hideDuration": "1000",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };

                // Global Toast Helper (for existing code)
                window.showToast = function(message, type = 'success') {
                    if (type === 'success') toastr.success(message);
                    else if (type === 'error') toastr.error(message);
                    else if (type === 'warning') toastr.warning(message);
                    else toastr.info(message);
                };

                // Laravel Session Flash Messages
                @if(session('success'))
                    toastr.success("{{ session('success') }}");
                @endif

                @if(session('error'))
                    toastr.error("{{ session('error') }}");
                @endif

                @if($errors->any())
                    @foreach($errors->all() as $error)
                        toastr.error("{{ $error }}");
                    @endforeach
                @endif
            });
        </script>

        @stack('modals')
        @stack('footer')
    </body>
</html>
