<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('storage/images/placeholder.png') }}" type="image/png">
    <title>Login</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js']) <!-- Ensure Tailwind is loaded -->
</head>
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
            <input type="email" id="email" name="email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-500" value="{{ old('email') }}" required autofocus placeholder="Enter email..">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4 relative">
            <label for="password" class="block text-gray-700 font-bold mb-2">Password</label>
            <input type="password" id="password" name="password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-red-500" required placeholder="Enter password..">
            <span id="togglePassword" class="absolute right-3 top-10 text-gray-400 cursor-pointer select-none">Show</span>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember" {{ old('remember') ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <!-- Forgot Password & Login Button -->
        <div class="flex items-center justify-between mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-red-500 hover:text-red-700" href="{{ route('password.request') }}">
                    {{ __('Forgot Password?') }}
                </a>
            @endif

            <x-primary-button class="ml-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Show/Hide Password Script -->
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            // Toggle the text between Show and Hide
            togglePassword.textContent = type === 'password' ? 'Show' : 'Hide';
        });

        // Autofill credentials if remember token exists
        document.addEventListener('DOMContentLoaded', function () {
            if (localStorage.getItem('rememberMe') === 'true') {
                const email = localStorage.getItem('email');
                const password = localStorage.getItem('password');
                if (email && password) {
                    document.getElementById('email').value = email;
                    document.getElementById('password').value = password;
                    document.getElementById('remember_me').checked = true;
                }
            }
        });

        // Optionally store the user's credentials locally when they choose to remember them
        document.getElementById('remember_me').addEventListener('change', function () {
            if (this.checked) {
                const email = document.getElementById('email').value;
                const password = document.getElementById('password').value;
                localStorage.setItem('rememberMe', 'true');
                localStorage.setItem('email', email);
                localStorage.setItem('password', password);
            } else {
                localStorage.removeItem('rememberMe');
                localStorage.removeItem('email');
                localStorage.removeItem('password');
            }
        });
    </script>
</x-guest-layout>
