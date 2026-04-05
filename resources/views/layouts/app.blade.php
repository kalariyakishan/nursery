<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Arjun Nursery Dashboard') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
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

        <!-- Global Toasting System -->
        <div x-data="toastComponent()" class="fixed top-12 right-8 z-[100] flex flex-col gap-4 max-w-sm pointer-events-none">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="toast.visible" 
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="translate-x-full opacity-0"
                     x-transition:enter-end="translate-x-0 opacity-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="translate-x-0 opacity-100"
                     x-transition:leave-end="translate-x-full opacity-0"
                     :class="toast.type === 'success' ? 'bg-primary border-primary/20 shadow-primary/20' : 'bg-red-600 border-red-400/20 shadow-red-500/20'"
                     class="pointer-events-auto shadow-2xl flex items-center gap-4 py-4 px-6 rounded-2xl text-white border backdrop-blur-md min-w-[320px]">
                    
                    <div :class="toast.type === 'success' ? 'bg-white/20' : 'bg-white/10'" class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]" x-text="toast.type === 'success' ? 'check_circle' : 'warning'"></span>
                    </div>

                    <div class="flex-1">
                        <h4 class="text-[10px] font-black uppercase tracking-[0.2em] opacity-60 mb-0.5" x-text="toast.type === 'success' ? 'સફળતા' : 'ભૂલ'"></h4>
                        <p class="font-bold text-sm gujarati-text leading-tight" x-text="toast.message"></p>
                    </div>

                    <button @click="toast.visible = false" class="opacity-40 hover:opacity-100 transition-opacity">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </template>
        </div>

        <script>
            function toastComponent() {
                return {
                    toasts: [],
                    init() {
                        window.showToast = (message, type = 'success') => {
                            const id = Date.now();
                            this.toasts.push({ id, message, type, visible: true });
                            
                            setTimeout(() => {
                                const index = this.toasts.findIndex(t => t.id === id);
                                if (index !== -1) {
                                    this.toasts[index].visible = false;
                                    setTimeout(() => {
                                        this.toasts = this.toasts.filter(t => t.id !== id);
                                    }, 500);
                                }
                            }, 5000);
                        };

                        @if(session('success'))
                            setTimeout(() => window.showToast(@js(session('success')), 'success'), 500);
                        @endif
                        @if(session('error'))
                            setTimeout(() => window.showToast(@js(session('error')), 'error'), 500);
                        @endif
                        @if($errors->any())
                            setTimeout(() => window.showToast(@js($errors->first()), 'error'), 600);
                        @endif
                    }
                }
            }
        </script>

        @stack('footer')
    </body>
</html>
