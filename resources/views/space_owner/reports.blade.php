<title>Reports</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Summary of Reports') }}
        </h2>
    </x-slot>
    <div class="w-full lg:w-4/5 mx-auto mt-4 sm:px-4 lg:px-9">
        <div class="bg-white p-6 max-w-7xl mx-auto overflow-hidden shadow-sm sm:rounded-lg container w-full">
            <div class="flex justify-between items-center mb-2">
                <!-- Search Form -->
                <form method="GET" action="{{ route('space.reports') }}" class="flex gap-4 items-center">
                    <div>
                        <label for="search" class="block font-medium pl-2">Search Listing Title</label>
                        <input type="text" name="search" id="search" 
                            class="border border-gray-300 rounded px-4 py-2" 
                            placeholder="Enter listing title..." 
                            value="{{ request('search') }}">
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 text-white mt-6 px-4 py-2 rounded">Search</button>
                    </div>
                </form>
            </div>

            <!-- Total Revenue -->
            <div class="flex">
                <div class="flex-1 mb-6">
                    <h3 class="text-lg font-semibold">Total Revenue</h3>
                    <p class="text-xl text-green-500 font-bold">₱{{ number_format($totalRevenue, 2) }}</p>
                </div>
            </div>

            <!-- Table -->
            <div class="rounded-lg overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-300 rounded-lg">
                    <thead>
                        <tr class="bg-orange-500">
                            <th class="px-4 py-2">Renter Name</th>
                            <th class="px-4 py-2">Rented Space</th>
                            <th class="px-4 py-2">Rental Term</th>
                            <th class="px-4 py-2">Start Date</th>
                            <th class="px-4 py-2">End Date</th>
                            <th class="px-4 py-2">Amount Receivable</th>
                            <th class="px-4 py-2">Status</th>
                            <th class="px-4 py-2">Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($agreements as $agreement)
                        <tr class="text-center">
                            <td class="border border-gray-300 px-4 py-2">{{ ucwords($agreement->renter->firstName) }} {{ ucwords($agreement->renter->lastName) }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $agreement->listing->title }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ ucwords($agreement->rentalTerm) }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $agreement->dateStart->format('F j, Y') }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $agreement->dateEnd->format('F j, Y') }}</td>
                            <td class="border border-gray-300 px-4 py-2">₱{{ number_format($agreement->amountReceivable, 2) }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ ucfirst($agreement->status) }}</td>
                            <td class="border border-gray-300 px-4 py-2">
                                @if ($agreement->isPaid == 1)
                                <span class="text-green-500 font-bold">Paid</span>
                                @else
                                    <span class="text-red-500 font-bold">Not Paid</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-gray-500 py-4">No matching records found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
