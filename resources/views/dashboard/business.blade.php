<title>Business Owner Dashboard</title>
<x-app-layout>
    <div class="flex flex-col h-screen">
        <!-- Main content area -->
        <div class="flex-grow py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg pl-10">
                    <div class="p-6 text-gray-900">
                        <form method="GET" action="{{ route('business.dashboard') }}" class="relative w-85">
                            <input class="bg-gray-50 pl-10 pr-10 py-2 w-full rounded-xl border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" 
                                   type="text" 
                                   name="search" 
                                   placeholder="Type a city.."
                                   value="{{ request('search') }}">
                            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500"></i>
                            <button type="submit" class="hidden">Search</button>
                        </form>
                    </div>
                </div>
                <div class="p-4">
                    <!-- Responsive Grid for Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 border-gray-200">
                        @php
                            // Define the list of locations
                            $locations = ['Cebu City', 'Mandaue City', 'Talisay City', 'Naga City', 'Minglanilla City', 'Toledo City', 'Lapu-Lapu City', 'Carcar', 'Asturias', 'Dumanjug', 'Barili', 'Danao'];
                            
                            // Get the search input
                            $search = request('search');
                            
                            // Filter locations based on the search input
                            if ($search) {
                                $locations = array_filter($locations, function($location) use ($search) {
                                    return stripos($location, $search) !== false; 
                                });
                            }
                        @endphp

                        @foreach($locations as $location)
                            @php
                                // Get the count for the current location
                                $count = $listingsCount[$location] ?? 0; // Default to 0 if not set
                            @endphp

                            @if ($count > 0) <!-- Only display the card if there are available spaces -->
                                <a href="{{ route('place.showByLocation', ['location' => $location]) }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100">
                                    <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900">{{ $location }}</h5>
                                    <p class="font-normal text-gray-700">({{ $count }}) Spaces Available</p>
                                    <p class="pt-2 font-normal text-gray-400">Click to view all available spaces</p>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-1">
            <div class="max-w-3xl mx-auto text-center">
                <p class="text-sm">© 2024 PlaceIT. All rights reserved.</p>
                <div class="mt-2">
                    <a href="#" class="text-gray-400 hover:text-gray-300">Privacy Policy</a>
                    <span class="mx-2">|</span>
                    <a href="#" class="text-gray-400 hover:text-gray-300">Terms of Service</a>
                </div>
            </div>
        </footer>
    </div>
</x-app-layout>
