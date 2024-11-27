<title>Booking History</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Booking History') }}
        </h2>
    </x-slot>

    <div class="w-full py-6 flex justify-center">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg">
            <h3 class="text-lg font-bold mb-4">My Booking History</h3>
                <div class="overflow-x-auto rounded-lg">    
                    <table class="w-full border-separate border-spacing-0 text-sm sm:text-base">
                        <thead>
                            <tr class="bg-orange-400 text-black">
                                <th class="px-4 py-2 sm:px-6 sm:py-4 w-40 sm:w-60 rounded-tl-2xl">Space Title</th>
                                <th class="px-4 py-2 sm:px-6 sm:py-4 w-40 sm:w-60">Owner</th>
                                <th class="px-4 py-2 sm:px-6 sm:py-4 w-40 sm:w-60">Rental Term</th>
                                <th class="px-4 py-2 sm:px-6 sm:py-4 w-40 sm:w-60">Amount</th>
                                <th class="px-4 py-2 sm:px-6 sm:py-4 w-40 sm:w-60">Date</th>
                                <th class="px-4 py-2 sm:px-6 sm:py-4 w-40 sm:w-60 rounded-tr-2xl">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bhistory as $history)
                            <tr class="bg-orange-200">
                                <td class="px-4 py-2 sm:px-6 sm:py-4 text-center border-b border-orange-300">{{ $history->rentalAgreement->listing->title ?? 'N/A' }}</td>
                                <td class="px-4 py-2 sm:px-6 sm:py-4 text-center border-b border-orange-300">{{ ucwords($history->spaceOwner->firstName ?? 'N/A') }} 
                                {{ ucwords($history->spaceOwner->lastName ?? 'N/A') }}</td>
                                <td class="px-4 py-2 sm:px-6 sm:py-4 text-center border-b border-orange-300">{{ ucwords($history->rentalAgreement->rentalTerm ?? 'N/A') }}</td>
                                <td class="px-4 py-2 sm:px-6 sm:py-4 text-center border-b border-orange-300">{{ number_format($history->amount, 2) }}</td>
                                <td class="px-4 py-2 sm:px-6 sm:py-4 text-center border-b border-orange-300">{{ $history->date->format('Y-m-d') ?? 'N/A' }}</td>
                                <td class="px-4 py-2 sm:px-6 sm:py-4 text-center">
                                    <a href="{{ route('place.detail', ['listingID' => $history->rentalAgreement->space->listingID]) }}"
                                        class="inline-block py-1 px-3 sm:py-2 sm:px-4 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-700">
                                        Book Again!
                                    </a>
                                </td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-2 sm:px-6 sm:py-4 text-center bg-orange-100">No booking history found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>