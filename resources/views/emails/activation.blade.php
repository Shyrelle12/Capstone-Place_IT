<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activation</title>
    <style>
        @import url('https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css');
    </style>
</head>
<body class="bg-gray-100 py-8">
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-2xl font-semibold text-gray-800">Account Activation</h1>
        <p class="mt-2 text-gray-600">Dear {{ $firstName }},</p>
        <p class="mt-2 text-gray-600">
            We are pleased to inform you that your account has been successfully activated. You can now log in and enjoy our services.
        </p>

        <div class="mt-4 bg-gray-50 border border-gray-300 rounded-lg p-4 text-center">
            <p class="text-lg font-bold text-green-500">Account Activated Successfully!</p>
        </div>

        <p class="mt-4 text-gray-600">
            If you have any questions or need assistance, please do not hesitate to contact our support team.
        </p>

        <p class="mt-6 text-sm text-gray-500">
            Thank you for choosing us!
        </p>

        <div class="mt-8 border-t border-gray-200 pt-6 text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} PlaceIt. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
