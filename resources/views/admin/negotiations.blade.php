<!-- resources/views/admin/negotiations/index.blade.php -->
<x-app-layout>
<x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Negotiations') }}
        </h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Reviews Section -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Negotiations</h2>

        <!-- Negotiations Table -->
        <div class="overflow-x-auto rounded-lg shadow-sm">
                    <table class="min-w-full table-auto border-collapse bg-white">
                        <thead class="bg-gradient-to-r from-orange-400 to-red-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left font-medium text-sm">#</th>
                    <th class="px-6 py-3 text-left font-medium text-sm">Title</th>
                    <th class="px-6 py-3 text-left font-medium text-sm">Owner</th>
                    <th class="px-6 py-3 text-left font-medium text-sm">Offer Amount</th>
                    <th class="px-6 py-3 text-left font-medium text-sm">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($negotiations as $negotiation)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm">{{ $negotiation->negotiationID }}</td>
                        <td class="px-6 py-3 text-sm">{{ $negotiation->listing->title }}</td>
                        <td class="px-6 py-3 text-sm">{{ $negotiation->receiver->firstName }} {{ $negotiation->receiver->lastName }}</td>
                        <td class="px-6 py-3 text-sm">₱{{ number_format($negotiation->offerAmount, 2) }}</td>
                        <td class="px-6 py-3 text-sm">{{ ucfirst($negotiation->negoStatus) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
