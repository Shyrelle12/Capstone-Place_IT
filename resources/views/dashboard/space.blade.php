<title>Space Owner Dashboard</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Spaces') }}
        </h2>
    </x-slot>
    
    <div class="w-full lg:w-3/4 mx-auto mt-4 sm:px-4 lg:px-9">
        <div class="bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                <!-- Filter Form (Left Side) -->
                <div class="w-full sm:w-auto">
                    <form method="GET" action="{{ route('space.dashboard') }}" class="flex flex-col sm:flex-row space-y-2 sm:space-x-2 sm:space-y-0 w-full">
                        <input type="date" name="date" class="border rounded-md p-2 w-full sm:w-auto" value="{{ request('date') }}">
                        <button type="submit" class="bg-orange-400 text-white py-2 px-4 rounded-md w-full sm:w-auto">Filter</button>
                    </form>
                </div>

                <!-- Live Search Bar (Right Side) -->
                <div class="w-full sm:w-1/3">
                    <input 
                        type="text" 
                        id="liveSearch" 
                        class="border rounded-md p-2 w-full" 
                        placeholder="Search Spaces..."
                    >
                </div>
            </div>

            <!-- Skeleton loader for the listings -->
            <div id="loadingSkeleton" class="mt-4 space-y-4 hidden">
                <div class="bg-neutral-200 text-white rounded-lg shadow-lg flex p-4 items-start flex-col space-y-4 w-full animate-pulse">
                    <div class="flex items-start space-x-6 w-full">
                        <div class="w-40 h-40 bg-gray-300 rounded-md"></div>
                        <div class="flex-grow space-y-2">
                            <div class="h-6 bg-gray-300 rounded-md"></div>
                            <div class="h-4 bg-gray-300 rounded-md w-3/4"></div>
                            <div class="h-4 bg-gray-300 rounded-md w-1/2"></div>
                            <div class="h-4 bg-gray-300 rounded-md w-1/3"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actual Listings -->
            <div class="mt-4 space-y-4" id="listingContainer">
                @php
                    $found = false;
                @endphp
                @foreach ($listings as $listing)
                    @if (!request('date') || $listing->dateCreated->format('Y-m-d') == request('date'))
                        @php
                            $found = true;
                        @endphp
                        <!-- Card Section -->
                        <div class="listing-item bg-neutral-200 text-white rounded-lg shadow-lg flex p-4 items-start flex-col space-y-4 w-full" data-title="{{ $listing->title }}">
                            <div class="flex items-start space-x-6 w-full">
                                <div class="w-40 h-40">
                                    @if($listing->images->count() > 0)
                                        <img src="{{ asset('storage/images/' . $listing->images->first()->image_path) }}" alt="{{ $listing->title }}" class="object-cover rounded-md w-full h-full">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" alt="No Image" class="object-cover rounded-md w-full h-full">
                                    @endif
                                </div>
                                <div class="flex-grow">
                                    <h3 class="text-xl font-bold text-black listing-title">{{ $listing->title }}</h3>
                                    <p class="text-sm text-gray-800 listing-location">{{ $listing->location }}</p>
                                    <p class="text-sm text-gray-800 listing-description mt-1">{{ $listing->description }}</p>
                                    <p class="text-sm text-gray-800 mt-1">Listed on {{ $listing->dateCreated->format('F j, Y') }}</p>
                                    <p class="mt-2 font-semibold text-sm 
                                        {{ $listing->status === 'Vacant' ? 'text-green-500' : '' }}
                                        {{ $listing->status === 'Pending' ? 'text-gray-400' : '' }}
                                        {{ $listing->status === 'Disapproved' ? 'text-red-500' : '' }}
                                        {{ $listing->status === 'Deactivated' ? 'text-red-500' : '' }}
                                        {{ $listing->status === 'Occupied' ? 'text-red-500' : '' }}">
                                        {{ $listing->status }}
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons (Always Visible) -->
                            <div class="flex w-full space-x-2 justify-end">
                                <a href="{{ route('space_owner.edit', ['listingID' => $listing->listingID]) }}" class="bg-orange-500 text-white px-4 py-2 w-40 h-10 rounded-lg hover:bg-orange-400 text-center">
                                    Edit
                                </a>
                                @if ($listing->status === 'Deactivated')
                                <form action="{{ route('listings.restore', ['listingID' => $listing->listingID]) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-green-500 text-white px-4 py-2 w-40 rounded-lg hover:bg-green-700 text-center">
                                        Restore
                                    </button>
                                </form>
                                @elseif ($listing->status === 'Vacant')
                                    <form action="{{ route('listings.destroy', ['listingID' => $listing->listingID]) }}" method="POST" class="inline-block" id="deactivate-form-{{ $listing->listingID }}">
                                        @csrf
                                        <button type="button" class="bg-red-700 text-white px-4 py-2 w-40 rounded-lg hover:bg-red-500 text-center" onclick="confirmDeactivation('{{ $listing->listingID }}', '{{ $listing->title }}')">
                                            Deactivate
                                        </button>
                                    </form>
                                @else
                                @endif
                            </div>

                            <script>
                            function confirmDeactivation(listingID, listingTitle) {
                                Swal.fire({
                                    title: 'Are you sure?',
                                    text: "Deactivate " + listingTitle + "?",
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#3085d6',
                                    confirmButtonText: 'Yes, deactivate it!'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.getElementById('deactivate-form-' + listingID).submit();
                                    }
                                });
                            }
                            </script>
                        </div>
                    @endif
                @endforeach
                @if (!$found)
                    <div class="text-lg text-center py-4 bg-gray-100">
                        No Space Posted. 
                        <a href="{{ route('space.newspaces') }}" class="text-blue-500 hover:text-blue-700">Post a space</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.getElementById('liveSearch').addEventListener('input', function() {
        let searchTerm = this.value.toLowerCase();
        let listings = document.querySelectorAll('.listing-item');
        
        listings.forEach(listing => {
            let title = listing.querySelector('.listing-title').textContent.toLowerCase();
            let location = listing.querySelector('.listing-location').textContent.toLowerCase();
            let description = listing.querySelector('.listing-description').textContent.toLowerCase();
            
            // Filter listings based on title, location, and description
            if (title.includes(searchTerm) || location.includes(searchTerm) || description.includes(searchTerm)) {
                listing.style.display = '';
            } else {
                listing.style.display = 'none';
            }
        });
    });
</script>
