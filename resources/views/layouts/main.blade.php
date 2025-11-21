<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      
      <title>{{ config('app.name', 'MenaraSumbar') }}</title>

      <!-- Fonts -->
      <link rel="preconnect" href="https://fonts.bunny.net">
      <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

      @stack('styles')
      @stack('scripts')
      <!-- Styles / Scripts -->
      @vite(['resources/css/app.css', 'resources/js/app.js'])
      
  </head>
  <body class="bg-white font-sans antialiased">
      
      <div class="min-h-screen bg-gray-100">
          @yield('content')
          @include('layouts.navbar')

          <!-- Page Content -->
          <main>

            {{-- <div class=" static flex justify-start">
                <p class=" text-white bg-blue-800 w-[280px] h-[43px] absolute mt-96 border-blue-950 border-4 pt-1 rounded-md">Waktu yang dibutuhkan : {{ round((microtime(true) - LARAVEL_START), 3) }} detik.</p>
            </div> --}}

              {{ $slot }}
          </main>
      </div>


      <footer class="border border-gray-300">
        <div class="flex justify-center font-bold">
          <p class=" text-gray-950 m-5 flex justify-between">Create with <img src="/img/love.svg" alt="love" class="w-[25px] p-1"> by Menara Sumbar</p>
        </div>
      </footer>
  
  </body>  
</html>