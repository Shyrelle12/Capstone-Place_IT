<title>Business Owner Reports</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rental Agreements Reports') }}
        </h2>
    </x-slot>

    <div class="w-full lg:w-4/5 mx-auto mt-4 sm:px-4 lg:px-9">
        <div class="bg-white p-6 max-w-7xl mx-auto overflow-hidden shadow-sm sm:rounded-lg container w-full">
            <!-- Table -->
            <div class="rounded-lg overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-300 rounded-lg">
                    <thead>
                        <tr class="bg-orange-500 text-center">
                            <th class="px-6 py-3">Space Title</th>
                            <th class="px-6 py-3">Rental Term</th>
                            <th class="px-6 py-3">Payment Due</th>
                            <th class="px-6 py-3">Amount to Pay</th>
                            <th class="px-6 py-3">Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agreements as $agreement)
                        <tr class="text-center">
                            <td class="border border-gray-300 px-6 py-4">{{ $agreement->listing->title }}</td>
                            <td class="border border-gray-300 px-6 py-4">{{ ucwords($agreement->rentalTerm) }}</td>
                            <td class="border border-gray-300 px-6 py-4">{{ $agreement->dateStart->format('F j, Y') }}</td>
                            <td class="border border-gray-300 px-6 py-4">₱{{ number_format($agreement->offerAmount, 2) }}</td>
                            <td class="border border-gray-300 px-6 py-4">
                                @if($agreement->paymentStatus == 'Paid')
                                    <span class="text-green-500 font-bold">Paid</span>
                                @elseif($agreement->paymentStatus == 'Unpaid')
                                    <span class="text-red-500 font-bold">Unpaid</span>
                                @else
                                    <span class="text-yellow-500 font-bold">{{ $agreement->paymentStatus }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
