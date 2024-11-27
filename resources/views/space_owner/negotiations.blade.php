<title>Negotiations</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Negotiations') }}
        </h2>
    </x-slot>

    <div class="w-full py-4 flex justify-center px-4">
        <div class="w-full max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex flex-col sm:flex-row sm:justify-between items-start sm:items-center mb-6">
                    <!-- Active Negotiations Heading -->
                    <h3 class="text-2xl font-extrabold mb-4 sm:mb-0">Active Negotiations</h3>
                    <!-- Search Bar -->
                    <div class="w-full sm:w-auto">
                        <input type="text" id="searchInput" 
                            class="w-full sm:w-80 px-4 py-2 border rounded-md focus:ring focus:ring-orange-400"
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
                    <div class="bg-gray-300 p-6 rounded-lg shadow-md space-y-4">
                        <div class="h-6 bg-gray-400 rounded w-3/4"></div>
                        <div class="h-4 bg-gray-400 rounded w-5/6"></div>
                        <div class="h-4 bg-gray-400 rounded w-2/3"></div>
                        <div class="h-4 bg-gray-400 rounded w-1/2"></div>
                    </div>
                    <div class="bg-gray-300 p-6 rounded-lg shadow-md space-y-4">
                        <div class="h-6 bg-gray-400 rounded w-3/4"></div>
                        <div class="h-4 bg-gray-400 rounded w-5/6"></div>
                        <div class="h-4 bg-gray-400 rounded w-2/3"></div>
                        <div class="h-4 bg-gray-400 rounded w-1/2"></div>
                    </div>
                    <!-- Repeat Skeleton Card as needed -->
                </div>

                <!-- Grid Container for Real Data -->
                <div id="negotiationGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 hidden">
                    @forelse($negotiations as $negotiation)
                        <!-- Negotiation Card -->
                        <a href="{{ route('space.negotiation.show', ['negotiationID' => $negotiation->negotiationID]) }}"
                            class="block bg-gray-100 p-6 rounded-lg shadow-lg hover:bg-gray-200 hover:shadow-lg hover:border-2 hover:border-orange-400 transition duration-200 ease-in-out negotiation-card">
                            <h4 class="text-xl font-bold text-orange-600 mb-2">{{ $negotiation->listing->title }}</h4>
                            <p class="text-sm text-gray-700 mb-2">
                                <strong>Business Owner:</strong> {{ ucwords($negotiation->sender->firstName) . ' ' . ucwords($negotiation->sender->lastName) }}
                            </p>
                            <p class="text-sm text-gray-700">
                                <strong>Offer:</strong> ₱{{ number_format($negotiation->offerAmount, 2) }}
                            </p>
                            <p class="text-sm mt-2 text-gray-700">
                                {{ $negotiation->created_at->format('F j, Y') }}
                            </p>
                        </a>
                    @empty
                        <!-- Empty State -->
                        <div class="text-center col-span-full">
                            <p class="text-gray-700 text-lg">No active negotiations found.</p>
                        </div>
                    @endforelse
                </div>

                <!-- Not Found Placeholder -->
                <div id="notFound" class="text-center col-span-full hidden">
                    <p class="text-red-700 text-lg font-bold">No Negotiations found</p>
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
                const owner = card.querySelector('p:nth-of-type(1)').innerText.toLowerCase();
                const offer = card.querySelector('p:nth-of-type(2)').innerText.toLowerCase();

                if (title.includes(searchInput) || owner.includes(searchInput) || offer.includes(searchInput)) {
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
