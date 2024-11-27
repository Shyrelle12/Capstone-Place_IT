<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('storage/images/placeholder.png') }}" type="image/png">
    <title>Email Verification</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Add jQuery CDN -->
    <script src="{{ asset('jquery.js') }}"></script>    
</head>
<x-guest-layout>
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Email Verification</h2>
        <p class="text-sm text-gray-600 mb-6">
            {{ __('Before you get started, please verify your email address. Click the "Send OTP" button below to receive your One-Time Passcode (OTP) via email. Once you\'ve received the OTP, enter it to complete the verification process.') }}
        </p>

        <!-- Status and Error Messages -->
        @if(session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- OTP Verification Form -->
        <form method="POST" action="{{ route('otp.verify.submit') }}" class="mb-3">
            @csrf
            <label for="otp" class="block text-sm font-medium text-gray-600 mb-2">Enter OTP</label>
            <input 
                type="text" 
                name="otp" 
                id="otp" 
                required 
                class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Enter your OTP"
            >
            <button 
                type="submit" 
                class="w-full mt-4 bg-red-500 hover:bg-red-700 text-white font-semibold py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
            >
                Verify OTP
            </button>
        </form>

        <!-- Send OTP Button -->
        <form method="POST" action="{{ route('email.send') }}" class="mb-3">
            @csrf
            <button 
                type="submit" 
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Send OTP
            </button>
        </form>

        <!-- Logout Button -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button 
                type="submit" 
                class="w-full underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
                Log Out
            </button>
        </form>
</x-guest-layout>


