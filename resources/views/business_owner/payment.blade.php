<title>Proceed to Payment</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Negotiations') }}
        </h2>
    </x-slot>

    <div class="w-full py-6 flex justify-center">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-bold mb-4">Payment</h3>
                
                <!-- GCash QR Code Section -->
                <div class="text-center mb-4">
                    <img src="{{ asset('qr.png') }}" alt="GCash Payment QR Code" class="w-full max-w-xs h-auto mx-auto object-contain">
                    <p class="text-lg text-sky-500 font-bold mt-2">Lemarc Eyac</p>
                    <p class="text-lg text-gray-400 font-semibold mt-2">+0922*****77</p> 
                </div>


                <!-- Payment Form -->
                <form action="{{ route('businessOwner.storeProofOfPayment', ['negotiationID' => $negotiation->negotiationID]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="proof" class="block text-sm font-semibold text-gray-700">Proof of Payment:</label>
                        <input type="file" name="proof" id="proof" class="w-full p-2 border border-gray-300 rounded-lg" required>
                    </div>

                    <div class="mb-4">
                        <label for="details" class="block text-sm font-semibold text-gray-700">Other details:</label>
                        <textarea name="details" id="details" rows="3" class="w-full p-2 border border-gray-300 rounded-lg"></textarea>
                    </div>

                    <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-600 w-full">Send Proof of Payment</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
