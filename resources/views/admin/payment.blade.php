<title>Payment Management</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Payment Management') }}
        </h2>
    </x-slot>
    <div class="container mx-auto mt-5 p-5">
        <div class="p-3 bg-white shadow-md rounded-lg">
            <div class="bg-primary text-black font-black text-lg p-4 rounded-t-lg text-center">
                <h3 class="text-lg font-semibold mb-4">Payment Status Management</h3>
                <div class="mb-8 overflow-x-auto rounded-lg">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-orange-400 text-black uppercase text-sm leading-normal">
                            <th class="py-3 px-4 text-left">Space Owner Name</th>
                            <th class="py-3 px-4 text-left">Renter Name</th>
                            <th class="py-3 px-4 text-left">Listing Title</th>
                            <th class="py-3 px-4 text-left">Amount</th>
                            <th class="py-3 px-4 text-left">Date</th>
                            <th class="py-3 px-4 text-left">Status</th>
                            <th class="py-3 px-4 text-left">Proof of Payment</th>
                            <th class="py-3 px-4 text-left">Proof of Meetup</th>
                            <th class="py-3 px-4 text-left">Space Owner Gcash #</th>
                            <th class="py-3 px-4 text-left">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 text-sm font-medium">
                        @foreach($payments as $payment)
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-4">{{ ucwords($payment->spaceOwner->firstName) }} {{ ucwords($payment->spaceOwner->lastName) }}</td>
                                <td class="py-3 px-4">{{ ucwords($payment->renter->firstName) }} {{ ucwords($payment->renter->lastName) }}</td>
                                <td class="py-3 px-4">{{ $payment->rentalAgreement->listing->title }}</td>
                                <td class="py-3 px-4">{{ number_format($payment->amount, 2) }} PHP</td>
                                <td class="py-3 px-4">{{ $payment->date->format('Y-m-d') }}</td>
                                <td class="py-3 px-4">
                                    <span class="badge 
                                        @if($payment->status === 'pending') badge-warning
                                        @elseif($payment->status === 'confirmed') badge-info
                                        @elseif($payment->status === 'partial_payment') badge-warning
                                        @elseif($payment->status === 'transferred') badge-primary
                                        @elseif($payment->status === 'received') badge-success
                                        @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @if($payment->proof)
                                        <a href="{{ asset('storage/' . $payment->proof) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $payment->proof) }}" alt="Proof of Payment" class="w-16 h-16 object-cover rounded" />
                                        </a>
                                    @else
                                        <span class="text-red-600">No proof uploaded</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($payment->meetupProof)
                                        <a href="{{ asset('storage/' . $payment->meetupProof->proof_image) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $payment->meetupProof->proof_image) }}" alt="Proof of Meetup" class="w-16 h-16 object-cover rounded" />
                                        </a>
                                    @else
                                        <span class="text-red-600">No proof uploaded</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($payment->billing)
                                        {{ $payment->billing->gcash_number }}
                                    @else
                                        <span class="text-red-600">Gcash # Not submitted yet.</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    @if($payment->status === 'pending')
                                        <form action="{{ route('admin.payments.update', $payment->paymentID) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="bg-green-500 text-white rounded px-2 py-1 hover:bg-green-600 transition">Confirm Payment</button>
                                        </form>
                                    @elseif($payment->status === 'confirmed')
                                        @if ($payment->billing)
                                            <button type="button" class="bg-blue-500 text-white rounded px-2 py-1 hover:bg-blue-600 transition" 
                                                onclick="openPartialModal({{ $payment->paymentID }}, {{ $payment->amount }}, '{{ $payment->billing->gcash_number ?? '' }}')">
                                                Send Partial Payment
                                            </button>
                                        @else
                                            <span class="text-muted">No payment was made.</span>
                                        @endif
                                    @elseif($payment->status === 'Partial Payment')
                                        @if ($payment->billing)
                                            <button type="button" class="bg-blue-500 text-white rounded px-2 py-1 hover:bg-blue-600 transition" 
                                                onclick="openFullModal({{ $payment->paymentID }}, {{ $payment->amount }}, '{{ $payment->billing->gcash_number ?? '' }}')">
                                                Send Remaining Payment
                                            </button>
                                        @endif
                                    @elseif($payment->status === 'transferred')
                                        <span class="text-muted">Waiting for Space Owner Approval</span>
                                    @elseif($payment->status === 'received')
                                        <span class="text-green-600 font-semibold">Payment Received</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                </div>
           
        </div>
    </div>

    <!-- Modal for transferring partial payment -->
    <div id="partialPaymentModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 items-center justify-center z-50 hidden flex">
        <div class="bg-white rounded-lg p-6 w-1/3">
            <h3 class="text-xl font-bold mb-4 text-center">Send Partial Payment to Space Owner</h3>
            <form action="{{ route('admin.payments.transfer') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="paymentID" name="paymentID" />
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Partial Amount after 10% Commission Fee</label>
                    <input type="text" id="amount" name="amount" class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md px-4 py-2" readonly />
                </div>
                <div class="mb-4">
                    <label for="gcashNumber" class="block text-sm font-medium text-gray-700">Space Owner Gcash Number</label>
                    <input type="text" id="gcashNumber" name="gcashNumber" class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md px-4 py-2" readonly />
                </div>
                <div class="mb-4">
                    <label for="proof" class="block text-sm font-medium text-gray-700">Upload Proof of Payment</label>
                    <input type="file" id="proof" name="proof" class="mt-1 block w-full bg-white border border-gray-300 rounded-md px-4 py-2">
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white rounded px-4 py-2 mr-2" onclick="closeModal('partial')">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white rounded px-4 py-2">Transfer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal for transferring full payment -->
    <div id="fullPaymentModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 items-center justify-center z-50 hidden flex">
        <div class="bg-white rounded-lg p-6 w-1/3">
            <h3 class="text-xl font-bold mb-4 text-center">Send Remaining Payment to Space Owner</h3>
            <form action="{{ route('admin.payments.full') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="fullPaymentID" name="paymentID" />
                <div class="mb-4">
                    <label for="fullAmount" class="block text-sm font-medium text-gray-700">Remaining Partial Amount after 10% Commission Fee</label>
                    <input type="text" id="fullAmount" name="amount" class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md px-4 py-2" readonly />
                </div>
                <div class="mb-4">
                    <label for="fullGcashNumber" class="block text-sm font-medium text-gray-700">Space Owner Gcash Number</label>
                    <input type="text" id="fullGcashNumber" name="gcashNumber" class="mt-1 block w-full bg-gray-100 border border-gray-300 rounded-md px-4 py-2" readonly />
                </div>
                <div class="mb-4">
                    <label for="fullProof" class="block text-sm font-medium text-gray-700">Upload Proof of Payment</label>
                    <input type="file" id="fullProof" name="proof" class="mt-1 block w-full bg-white border border-gray-300 rounded-md px-4 py-2">
                </div>
                <div class="flex justify-end">
                    <button type="button" class="bg-gray-500 text-white rounded px-4 py-2 mr-2" onclick="closeModal('full')">Cancel</button>
                    <button type="submit" class="bg-blue-500 text-white rounded px-4 py-2">Transfer</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPartialModal(paymentID, amount, gcashNumber) {
            const deductedAmount = amount * 0.90; // Deducting 10% commission
            const partialAmount = (deductedAmount / 2).toFixed(2);

            document.getElementById('paymentID').value = paymentID;
            document.getElementById('amount').value = partialAmount;
            document.getElementById('gcashNumber').value = gcashNumber;
            document.getElementById('partialPaymentModal').classList.remove('hidden');
        }

        function openFullModal(paymentID, amount, gcashNumber) {
            const deductedAmount = amount * 0.90; // Deducting 10% commission
            const partialAmount = (deductedAmount / 2).toFixed(2);

            document.getElementById('fullPaymentID').value = paymentID;
            document.getElementById('fullAmount').value = partialAmount;
            document.getElementById('fullGcashNumber').value = gcashNumber;
            document.getElementById('fullPaymentModal').classList.remove('hidden');
        }

        function closeModal(type) {
            if(type === 'partial') {
                document.getElementById('partialPaymentModal').classList.add('hidden');
            } else {
                document.getElementById('fullPaymentModal').classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
