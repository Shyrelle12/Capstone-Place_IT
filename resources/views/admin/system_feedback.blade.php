<title>System Feedback</title>
<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Feedback') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Reviews Section -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">System Feedback</h2>

        <!-- Negotiations Table -->
        <div class="overflow-x-auto rounded-lg shadow-sm">
                    <table class="min-w-full table-auto border-collapse bg-white">
                        <thead class="bg-gradient-to-r from-orange-400 to-red-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-sm">#</th>
                    <th class="px-6 py-3 text-left font-medium text-sm">User</th>
                    <th class="px-6 py-3 text-left font-medium text-sm">Feedback</th>
                    <th class="px-6 py-3 text-left font-medium text-sm">Rating</th>
                    <th class="px-6 py-3 text-left font-medium text-sm">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($feedbacks as $feedback)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm">{{ $feedback->feedbackID}}</td>
                        <td class="px-6 py-3 text-sm">{{ $feedback->spaceOwner->firstName }} {{ $feedback->spaceOwner->lastName }}</td>
                        <td class="px-6 py-3 text-sm">{{ $feedback->feedback_content }}</td>
                        <td class="px-6 py-3 text-sm">{{ $feedback->rating }}</td>
                        <td class="px-6 py-3 text-sm">{{ $feedback->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
