<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'SignalApp Citoyen' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="font-sans antialiased">
    @if (session('success'))
        <div x-data x-init="setTimeout(() => $el.remove(), 4000)" class="fixed top-4 right-4 z-50 px-4 py-3 rounded-xl bg-emerald-500 text-white text-sm shadow-lg">
            {{ session('success') }}
        </div>
    @endif
    @if (session('warning'))
        <div x-data x-init="setTimeout(() => $el.remove(), 4000)" class="fixed top-4 right-4 z-50 px-4 py-3 rounded-xl bg-amber-500 text-white text-sm shadow-lg">
            {{ session('warning') }}
        </div>
    @endif
    @if (session('error'))
        <div x-data x-init="setTimeout(() => $el.remove(), 4000)" class="fixed top-4 right-4 z-50 px-4 py-3 rounded-xl bg-red-500 text-white text-sm shadow-lg">
            {{ session('error') }}
        </div>
    @endif
    {{ $slot }}
    @livewireScripts
    @stack('scripts')
</body>
</html>
