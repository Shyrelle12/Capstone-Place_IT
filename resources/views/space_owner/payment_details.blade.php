<title>Payment Details</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Details') }}
        </h2>
    </x-slot>

    <div class="w-full py-6 flex justify-center bg-gray-50">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-4xl border border-gray-200">
            <div class="p-8">
                <!-- Loop through each group of negotiations by business owner -->
                @foreach($negotiationsByOwner as $ownerID => $negotiations)
                    @php
                        $businessOwner = $negotiations->first()->sender;
                        $listing = $negotiations->first()->listing;
                    @endphp

                    <!-- Business Owner Details Section -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-semibold text-gray-700 mb-4">Business Owner Information</h3>
                        <table class="min-w-full bg-gray-100 rounded-lg shadow-sm divide-y divide-gray-200 mb-4">
                            <tbody class="text-sm text-gray-700">
                                <tr class="hover:bg-gray-200 transition-colors duration-150">
                                    <td class="px-6 py-3 font-semibold">Name</td>
                                    <td class="px-6 py-3">{{ ucwords($businessOwner->firstName) . " " . ucwords($businessOwner->lastName) }}</td>
                                </tr>
                                <tr class="hover:bg-gray-200 transition-colors duration-150">
                                    <td class="px-6 py-3 font-semibold">Email</td>
                                    <td class="px-6 py-3">{{ $businessOwner->email }}</td>
                                </tr>
                                <tr class="hover:bg-gray-200 transition-colors duration-150">
                                    <td class="px-6 py-3 font-semibold">Phone</td>
                                    <td class="px-6 py-3">{{ $businessOwner->mobileNumber }}</td>
                                </tr>
                                <tr class="hover:bg-gray-200 transition-colors duration-150">
                                    <td class="px-6 py-3 font-semibold">Space Title</td>
                                    <td class="px-6 py-3">{{ $listing->title }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Billing Details Section -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-semibold text-gray-700 mb-4">Billing Details</h3>
                        @php
                            $billingDetail = $billingDetails->firstWhere('rental_agreement_id', $negotiations->first()->negotiationID);
                        @endphp
                        @if($billingDetail)
                        <table class="min-w-full bg-gray-100 rounded-lg shadow-sm divide-y divide-gray-200">
                            <tbody class="text-sm text-gray-700">
                                <tr class="hover:bg-gray-200 transition-colors duration-150">
                                    <td class="px-6 py-3 font-semibold">GCash Number</td>
                                    <td class="px-6 py-3">{{ $billingDetail->gcash_number }}</td>
                                </tr>
                            </tbody>
                        </table>
                        @else
                        <p class="text-gray-600 italic">No billing details found.</p>
                        @endif
                    </div>

                    <!-- Negotiation Offers Section -->
                    @if($negotiations->count())
                    <div class="mb-8">
                        <h3 class="text-2xl font-semibold text-gray-700 mb-4">Payments</h3>
                        <div class="mb-8 overflow-x-auto rounded-lg">
                            <table class="min-w-full bg-gray-100 rounded-lg shadow-sm divide-y divide-gray-200">
                                <thead>
                                    <tr class="bg-gray-200">
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Proof of Payment</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Download Invoice</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm text-gray-700">
                                    @foreach($negotiations as $negotiation)
                                        <tr class="hover:bg-gray-200 transition-colors duration-150">
                                            <td class="px-6 py-4 font-medium text-gray-900">P{{ number_format($negotiation->offerAmount, 2) }}</td>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                @php
                                                    $payment = $payments->firstWhere(function ($payment) use ($negotiation) {
                                                        return $payment->rentalAgreementID == $negotiation->negotiationID && $payment->amount == $negotiation->offerAmount;
                                                    });
                                                @endphp
                                                {{ $payment ? ucwords($payment->status) : 'No Payment' }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if($payment && $payment->admin_proof)
                                                    <a href="{{ asset('storage/' . $payment->admin_proof) }}" target="_blank" class="inline-block transform hover:scale-105 transition-transform">
                                                        <img src="{{ asset('storage/' . $payment->admin_proof) }}" alt="Admin Proof of Payment" class="w-20 h-20 object-cover rounded-lg shadow-md" />
                                                    </a>
                                                @else
                                                    <p class="text-gray-600 italic">No proof of payment uploaded yet.</p>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-left">
                                                @if($payment && $payment->status == 'transferred')
                                                    <form action="{{ route('payments.approve', $payment->paymentID) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-green-600 transition-all duration-200">
                                                            Approve Payment
                                                        </button>
                                                    </form>
                                                @elseif($payment && $payment->status == 'confirmed')
                                                    <p class="text-gray-600 italic">Submit proof of meetup, and wait for confirmation.</p>
                                                @elseif($payment && $payment->status == 'pending')
                                                    <p class="text-gray-600 italic">Payment not yet confirmed.</p>
                                                @elseif($payment && $payment->status == 'partial_payment')
                                                    <p class="text-gray-600 italic">Remaining payment will be sent after transaction.</p>    
                                                @else
                                                    <p class="text-gray-600 italic">Payment received.</p>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-left">
                                                @if($payment && $payment->status == 'received')
                                                    <a href="{{ route('payment.download', $payment->paymentID) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-600 transition-all duration-200 inline-block text-center">
                                                        Download Invoice
                                                    </a>
                                                @else
                                                    <p class="text-gray-600 italic">No receipt available.</p>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                        <p class="text-gray-600 italic">No negotiations found for this business owner.</p>
                    @endif

                    <hr class="my-6 border-gray-300">
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>