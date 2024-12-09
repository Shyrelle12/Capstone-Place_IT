<title>Customer Reviews</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reviews') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Reviews Section -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Customer Reviews</h2>

                <!-- Reviews Table -->
                <div class="overflow-x-auto rounded-lg shadow-sm">
                    <table class="min-w-full table-auto border-collapse bg-white">
                        <thead class="bg-gradient-to-r from-orange-400 to-red-600 text-white">
                            <tr>
                                <th class="px-6 py-3 text-left font-medium text-sm">Agreement ID</th>
                                <th class="px-6 py-3 text-left font-medium text-sm">Rating</th>
                                <th class="px-6 py-3 text-left font-medium text-sm">Comment</th>
                                <th class="px-6 py-3 text-left font-medium text-sm">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reviews as $review)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-3 text-sm">{{ $review->rentalAgreementID }}</td>
                                    <td class="px-6 py-3 text-sm">
                                        <span class="inline-block py-1 px-3 rounded-full text-white 
                                            {{ $review->rate >= 4 ? 'bg-green-500' : ($review->rate >= 3 ? 'bg-yellow-500' : 'bg-red-500') }}">
                                            {{ $review->rate }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-3 text-sm">{{ $review->comment }}</td>
                                    <td class="px-6 py-3 text-sm">{{ $review->created_at->format('Y-m-d') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
