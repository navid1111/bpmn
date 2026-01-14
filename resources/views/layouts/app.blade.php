<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Content -->
        <main class="flex-1 flex flex-col">
            <!-- Header -->
            <x-header />

            <!-- Page Content -->
            <div class="p-6 bg-gray-50 flex-1 overflow-y-auto">
                @if (isset($header))
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">
                        {{ $header }}
                    </h2>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
