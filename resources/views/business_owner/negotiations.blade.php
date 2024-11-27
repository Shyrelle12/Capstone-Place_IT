<title>My Sent Negotiations</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Sent Negotiations') }}
        </h2>
    </x-slot>

    <div class="w-full py-6 flex justify-center">
        <div class="w-full max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg">
                @if(session('success'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: '{{ session('success') }}',
                                timer: 3000,
                                showConfirmButton: false
                            });
                        });
                    </script>
                @endif

                <!-- Search bar and title -->
                <div class="flex flex-col sm:flex-row sm:justify-between items-start sm:items-center mb-6">
                    <h3 class="text-lg font-bold mb-4 sm:mb-0">Sent Negotiations</h3>
                    <div class="w-full sm:w-80">
                        <input type="text" id="searchInput" 
                            class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-orange-400"
                            placeholder="Search negotiations..." 
                            onkeyup="filterNegotiations()">
                    </div>
                </div>

                <!-- Skeleton Loading State -->
                <div id="skeletonLoader" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 hidden animate-pulse">
                    <!-- Skeleton Card -->
                    <div class="bg-gray-300 p-6 rounded-lg shadow-md space-y-4">
                        <div class="h-6 bg-gray-400 rounded w-3/4"></div>
                        <div class="h-4 bg-gray-400 rounded w-5/6"></div>
                        <div class="h-4 bg-gray-400 rounded w-2/3"></div>
                        <div class="h-4 bg-gray-400 rounded w-1/2"></div>
                    </div>
                    <!-- Repeat Skeleton Card as needed -->
                </div>

                <!-- Negotiation Cards Grid -->
                <div id="negotiationGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
                    @forelse($negotiations as $negotiation)
                        <div onclick="window.location='{{ route('business.negotiation.show', ['negotiationID' => $negotiation->negotiationID]) }}'"
                             class="cursor-pointer bg-gray-100 p-6 rounded-lg shadow-md hover:bg-gray-200 hover:shadow-lg hover:border-2 hover:border-orange-400 transition duration-200 ease-in-out negotiation-card">
                            <h4 class="text-lg font-semibold">{{ $negotiation->listing->title }}</h4>
                            <p class="text-gray-600 mt-2"><strong>Space Owner:</strong> {{ ucwords($negotiation->receiver->firstName) . ' ' . ucwords($negotiation->receiver->lastName) }}</p>
                            <p class="text-gray-600 mt-2"><strong>Offer Amount:</strong> ₱{{ number_format($negotiation->offerAmount, 2) }}</p>
                            <p class="mt-4 font-bold
                                {{ $negotiation->negoStatus === 'Approved' ? 'text-green-600' : '' }}
                                {{ $negotiation->negoStatus === 'Pending' ? 'text-blue-600' : '' }}
                                {{ $negotiation->negoStatus === 'Disapproved' || $negotiation->negoStatus === 'Another Term' ? 'text-red-600' : '' }}">
                                {{ $negotiation->negoStatus }}
                            </p>
                            <p class="text-sm mt-2 text-gray-700">
                                {{ $negotiation->created_at->format('F j, Y') }}
                            </p>
                        </div>
                    @empty
                        <div class="col-span-full px-6 py-4 text-center bg-orange-100 rounded-lg">
                            You have not sent any negotiations.
                        </div>
                    @endforelse
                </div>

                <!-- Not Found Placeholder -->
                <div id="notFound" class="text-center hidden">
                    <p class="text-gray-700 text-lg font-bold">Not Found</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show skeleton loader initially
        document.getElementById('skeletonLoader').classList.remove('hidden');
        document.getElementById('negotiationGrid').classList.add('hidden');

        // Simulate loading delay and show real data
        setTimeout(function() {
            document.getElementById('skeletonLoader').classList.add('hidden');
            document.getElementById('negotiationGrid').classList.remove('hidden');
        }, 500);  // Adjust the time based on your data fetching time

        function filterNegotiations() {
            const searchInput = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.negotiation-card');
            const notFound = document.getElementById('notFound');
            let anyVisible = false;

            cards.forEach(card => {
                const title = card.querySelector('h4').innerText.toLowerCase();
                const receiver = card.querySelector('p:nth-of-type(1)').innerText.toLowerCase();
                const offer = card.querySelector('p:nth-of-type(2)').innerText.toLowerCase();

                if (title.includes(searchInput) || receiver.includes(searchInput) || offer.includes(searchInput)) {
                    card.style.display = '';
                    anyVisible = true;
                } else {
                    card.style.display = 'none';
                }
            });

            // Toggle "Not Found" message
            if (anyVisible) {
                notFound.classList.add('hidden');
            } else {
                notFound.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>
