<title>Feedback</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feedback') }}
        </h2>
    </x-slot>

        <div class="w-full py-6 flex justify-center ">
            <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto rounded-lg">
                        <h3 class="text-lg font-bold mb-4">Provide Feedbacks</h3>
                        <table class="w-full border-separate border-spacing-0 text-sm sm:text-base" style="border-spacing: 0;">
                            <thead>
                                <tr class="bg-orange-400 text-black">
                                    <th class="px-6 py-4 w-44 rounded-tl-2xl">Space Name</th>
                                    <th class="px-4 py-2">Owner</th>
                                    <th class="px-4 py-2">Amount Paid</th>
                                    <th class="px-4 py-2">Rental Term</th>
                                    <th class="px-4 py-2">Date Start</th>
                                    <th class="px-4 py-2">Date End</th>
                                    <th class="px-6 py-4 w-44 rounded-tr-2xl">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rentalAgreements as $agreement)
                                    <tr class="text-center bg-orange-100 border-b-2 border-orange-200">
                                        <td class="px-4 py-2 ">{{ $agreement->listing->title }}</td>
                                        <td class="px-4 py-2 ">{{ ucwords($agreement->spaceOwner->firstName) }} {{ ucwords($agreement->spaceOwner->lastName) }}</td>
                                        <td class="px-4 py-2 ">{{ $agreement->offerAmount }}</td>
                                        <td class="px-4 py-2 ">{{ ucwords($agreement->rentalTerm) }}</td>
                                        <td class="px-4 py-2 ">{{ $agreement->dateStart }}</td>
                                        <td class="px-4 py-2 ">{{ $agreement->dateEnd }}</td>
                                        <td class="px-4 py-2 w-60 ">
                                            @if (isset($feedbacks[$agreement->rentalAgreementID]))
                                                <span colspan="7" class="py-1.5 px-5 me-2 mb-2 text-sm font-medium text-gray-900">Feedback submitted.</span>
                                            @else
                                                <a href="{{ route('business.action', ['rentalAgreementID' => $agreement->rentalAgreementID]) }}" class="inline-block py-1 px-3 sm:py-2 sm:px-4 text-sm font-medium text-white bg-red-500 rounded-lg hover:bg-red-700">
                                                    Give Feedback
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">No rental agreements found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div> 
                </div>
            </div>
        </div>
</x-app-layout>