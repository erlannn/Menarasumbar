<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'MenaraSumbar') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-[#001F3F]">

        {{-- <div class="static flex justify-start">
            <p class=" text-white bg-blue-800 w-[280px] h-[65px] absolute mt-96 border-blue-950 border-4 pt-1 rounded-md">Waktu yang dibutuhkan : {{ round((microtime(true) - LARAVEL_START), 3) }} detik.</p>
        </div> --}}

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white overflow-hidden sm:rounded-lg shadow-xl shadow-gray-900">
                <div class="justify-items-center">
                    <img class="w-[200px] mb-6 mt-6" src="img/Logobaru.png" alt="Logo MenaraSumbar">
                </div>
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
