<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('placeholder.png') }}" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@700&display=swap" rel="stylesheet">


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans antialiased min-h-screen flex flex-col">
    <!-- Navbar -->
    <nav class="p-4 bg-white shadow md:flex md:justify-between md:items-center border-b-2 border-orange-500">
        <!-- Logo Section with "Get Started" next to it on mobile -->
        <div class="flex items-center space-x-40">
            <a href="{{ url('/') }}" class="text-orange-600 text-3xl font-bold" style="font-family: fredoka">place.it</a>

            <!-- Get Started Button next to the logo on mobile, hidden on larger screens -->
            @if (Route::has('login') && !Auth::check())
                <a href="{{ url('/register') }}" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md block md:hidden">Get Started</a>
            @endif
        </div>

        <!-- Get Started Button on larger screens, hidden on mobile -->
        @if (Route::has('login') && !Auth::check())
            <a href="{{ url('/register') }}" class="font-semibold bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md hidden md:block">Register</a>
        @endif
    </nav>

    <!-- Main Content Section -->
    <div class="-mt-8 container mx-auto flex flex-col lg:flex-row justify-center items-center h-screen space-y-8 lg:space-y-0 lg:space-x-12 px-4">
        <!-- Left Image Section (hidden on mobile, visible on large screens) -->
        <div class="hidden lg:block lg:w-1/2">
            <h1 class="text-4xl md:text-6xl font-bold text-red-600 leading-tight mb-4"><span style="font-family: fredoka">Welcome to PlaceIt</h1>
            <p class="text-black-600 text-base md:text-2xl mb-6">Find the perfect space to advertise your business, or list your available space and start earning today!</p>
        </div>

        <!-- Content (Login Form / Register Form) -->
        <div class="w-full sm:w-2/3 lg:w-1/3 bg-gray-100 rounded-lg p-8 shadow-lg">
            {{ $slot }}
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 py-6 text-center text-white mt-auto">
        <p class="text-sm">Copyright &copy; <a href="#top">PlaceIt</a>, All Rights Reserved.</p>
    </footer>
</body>
</html>
