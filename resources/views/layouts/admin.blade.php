<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard - TokoPun' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 font-sans antialiased overflow-hidden" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen w-full relative">
        {{-- Overlay for mobile --}}
        <div x-show="sidebarOpen" 
             x-transition.opacity 
             @click="sidebarOpen = false"
             class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden" 
             style="display: none;"
             x-cloak></div>

        {{-- Sidebar --}}
        <x-admin.sidebar />

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            <x-admin.topbar />
            
            <main class="flex-1 overflow-y-auto px-4 lg:px-8 pb-8">
                {{ $slot }}
            </main>
        </div>
    </div>
    
    <livewire:confirm-modal />
</body>

</html>
