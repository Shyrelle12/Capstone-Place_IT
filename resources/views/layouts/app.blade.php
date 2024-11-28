<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('placeholder.png') }}" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="{{ asset('jquery.js') }}"></script>
    <script src="{{ asset('sweetalert2.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100">
        <!-- Static Navigation -->
        <div id="navigation-wrapper">
            @if (auth()->check() && auth()->user()->role === 'space_owner')
                @include('layouts.space-navigation')
            @elseif (auth()->check() && auth()->user()->role === 'business_owner')
                @include('layouts.business-navigation')
            @elseif (auth()->check() && auth()->user()->role === 'admin')
                @include('layouts.admin-navigation')
            @else
                @include('layouts.navigation')
            @endif
        </div>

        @if (isset($header))
            <header class="bg-gradient-to-r from-orange-400 to-red-600 shadow">
                <div class="font-bold max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif
        @if(session('success'))
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: '{{ session('success') }}',
                                    timer: 2000, // The alert will auto-close after 5 seconds
                                    showConfirmButton: false
                                });
                            });
                        </script>
                    @endif
        <main id="content-area">
            {{ $slot }}
        </main>
    </div>

    <!-- Include the welcome modal -->
    @if(auth()->check() && !auth()->user()->terms_accepted)
        @include('welcome-modal')
    @endif
</body>

</html>
