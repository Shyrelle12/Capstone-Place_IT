<title>Locations in {{ $location }}</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Spaces in ' . $location) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Skeleton Loader -->
            <div id="loadingSkeleton" class="grid grid-cols-1 gap-2">
                @for ($i = 0; $i < 5; $i++)
                    <div class="block border border-gray-300 rounded-lg shadow-md p-4 bg-gray-200 animate-pulse">
                        <div class="flex flex-col md:flex-row md:justify-between md:px-6">
                            <!-- Title -->
                            <div class="w-full md:w-1/4 text-left mb-2 md:mb-0 px-2">
                                <div class="bg-gray-400 h-4 w-3/4 rounded"></div>
                            </div>
                            <!-- Owner -->
                            <div class="w-full md:w-1/4 text-left mb-2 md:mb-0 px-2">
                                <div class="bg-gray-400 h-4 w-1/2 rounded"></div>
                            </div>
                            <!-- Location -->
                            <div class="w-full md:w-1/4 text-left mb-2 md:mb-0 px-2">
                                <div class="bg-gray-400 h-4 w-1/2 rounded"></div>
                            </div>
                            <!-- Date -->
                            <div class="w-full md:w-1/4 text-left px-2">
                                <div class="bg-gray-400 h-4 w-1/3 rounded"></div>
                            </div>
                            <!-- Status -->
                            <div class="w-full md:w-1/4 text-center px-2">
                                <div class="bg-gray-400 h-4 w-1/4 mx-auto rounded"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>

            <!-- Actual Content -->
            <div id="listingContainer" class="hidden">
                @if($listings->isEmpty())
                    <div class="bg-white shadow-sm sm:rounded-lg p-6 text-gray-500">
                        No listings found for {{ $location }}.
                    </div>
                @else
                    <!-- Label for the cards (hidden on mobile) -->
                    <div class="hidden md:flex font-semibold text-gray-800 mb-4">
                        <div class="w-1/4 text-left px-2">Title</div>
                        <div class="w-1/4 text-left px-2">Owner</div>
                        <div class="w-1/4 text-left px-2">Location</div>
                        <div class="w-1/4 text-left px-2">Date Created</div>
                        <div class="w-1/4 text-center px-2">Status</div>
                    </div>

                    <!-- Cards -->
                    <div class="grid grid-cols-1 gap-2">
                        @foreach($listings as $listing)
                            <a href="{{ route('place.detail', ['listingID' => $listing->listingID]) }}" 
                               class="block border border-gray-300 rounded-lg shadow-md p-4 bg-white hover:bg-gray-50">
                                <div class="flex flex-col  md:flex-row md:justify-between md:px-6">
                                    <!-- Description -->
                                    <div class="w-full md:w-1/4 text-left mb-2 md:mb-0 px-2">
                                        <p class="text-gray-800"><strong>{{ $listing->title }}</strong></p>
                                    </div>
                                    <!-- Owner -->
                                    <div class="w-full md:w-1/4 text-left mb-2 md:mb-0 px-2">
                                        <p class="text-gray-800">{{ ucwords($listing->owner->firstName) }}</p>
                                    </div>
                                    <!-- Location -->
                                    <div class="w-full md:w-1/4 text-left mb-2 md:mb-0 px-2">
                                        <p class="text-gray-800">{{ $listing->location }}</p>
                                    </div>
                                    <!-- Date -->
                                    <div class="w-full md:w-1/4 text-left px-2">
                                        <p class="text-gray-800">{{ $listing->dateCreated->format('F j, Y') }}</p>
                                    </div>
                                    <!-- Status -->
                                    <div class="w-full md:w-1/4 text-center px-2">
                                        <p class="text-gray-800">{{ $listing->status }}</p>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simulate loading with a timeout
            setTimeout(() => {
                document.getElementById('loadingSkeleton').classList.add('hidden'); // Hide skeleton
                document.getElementById('listingContainer').classList.remove('hidden'); // Show actual content
            }, 700); // Simulate 2 seconds of loading
        });
    </script>
</x-app-layout>
