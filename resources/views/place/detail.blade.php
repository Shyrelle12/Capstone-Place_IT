<title>Details of {{ $listing->title }}</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Details of ' . $listing->title) }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white">
                    <!-- Title and Owner -->
                    <div class="flex justify-between">
                        <h3 class="text-2xl font-semibold mb-2">{{ $listing->title }}</h3>
                        <p class="text-black-700"><strong>{{ ucwords($listing->owner->firstName) . " " . ucwords($listing->owner->lastName) }}</strong></p>
                    </div>

                    <!-- Images with Modal -->
                    <div class="relative">
                        <div class="flex space-x-4">
                            @foreach($listing->images as $image)
                                <!-- Clickable Image -->
                                <img src="{{ asset('storage/images/' . $image->image_path) }}" alt="Image" class="rounded-lg mb-4 cursor-pointer" 
                                    style="width: 300px; height: 200px; object-fit: cover;" 
                                    onclick="openModal('{{ asset('storage/images/' . $image->image_path) }}')">
                            @endforeach
                        </div>
                    </div>

                    <!-- Description and Details -->
                    <div class="mt-1">
                        <h4 class="font-semibold text-lg">Description</h4>
                        <p>{{ $listing->description }}</p>

                        <h4 class="font-semibold mt-2 text-lg">Status</h4>
                        <span class="
                            {{ $listing->status === 'Vacant' ? 'text-green-600' : '' }}
                            {{ $listing->status === 'Occupied' ? 'text-red-600' : '' }}
                            {{ $listing->status === 'Deactivated' || $listing->status === 'Another Term' ? 'text-red-600' : '' }}
                            font-bold">
                            {{ $listing->status }}
                        </span>

                        <h4 class="font-semibold mt-2 text-lg">Location</h4>
                        <p>{{ $listing->location }}</p>

                        <h4 class="font-semibold mt-4 text-lg">User Rating</h4>
                        <div class="flex items-center">
                        @if($averageRating)
    <div class="flex items-center">
        <!-- Display Stars -->
        @for ($i = 1; $i <= 5; $i++)
            @php
                $fullStar = $i <= floor($averageRating);
                $halfStar = !$fullStar && $i - 0.5 < $averageRating;
            @endphp
            @if ($fullStar)
                            <!-- Full Star -->
                            <svg class="w-6 h-6 text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927C11.23 2.549 11.772 2.549 11.953 2.927L14.734 7.455L19.882 7.782C20.304 7.818 20.447 8.407 20.174 8.758L16.84 11.711L17.675 16.909C17.717 17.333 17.209 17.656 16.865 17.323L12 13.996L7.135 17.323C6.791 17.656 6.283 17.333 6.325 16.909L7.16 11.711L3.826 8.758C3.553 8.407 3.696 7.818 4.118 7.782L9.266 7.455L12.047 2.927z" />
                            </svg>
                        @elseif ($halfStar)
                            <!-- Half Star -->
                            <svg class="w-6 h-6 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <defs>
                                    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="0%">
                                        <stop offset="50%" stop-color="yellow" />
                                        <stop offset="50%" stop-color="gray" />
                                    </linearGradient>
                                </defs>
                                <path fill="url(#grad)" d="M11.049 2.927C11.23 2.549 11.772 2.549 11.953 2.927L14.734 7.455L19.882 7.782C20.304 7.818 20.447 8.407 20.174 8.758L16.84 11.711L17.675 16.909C17.717 17.333 17.209 17.656 16.865 17.323L12 13.996L7.135 17.323C6.791 17.656 6.283 17.333 6.325 16.909L7.16 11.711L3.826 8.758C3.553 8.407 3.696 7.818 4.118 7.782L9.266 7.455L12.047 2.927z" />
                            </svg>
                        @else
                            <!-- Empty Star -->
                            <svg class="w-6 h-6 text-gray-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927C11.23 2.549 11.772 2.549 11.953 2.927L14.734 7.455L19.882 7.782C20.304 7.818 20.447 8.407 20.174 8.758L16.84 11.711L17.675 16.909C17.717 17.333 17.209 17.656 16.865 17.323L12 13.996L7.135 17.323C6.791 17.656 6.283 17.333 6.325 16.909L7.16 11.711L3.826 8.758C3.553 8.407 3.696 7.818 4.118 7.782L9.266 7.455L12.047 2.927z" />
                            </svg>
                        @endif
                    @endfor

                    <!-- Display Decimal Rating -->
                    <span class="ml-2 text-gray-600">{{ number_format($averageRating, 1) }} / 5</span>
                </div>
            @else
                <span>No Ratings available</span>
            @endif

                        </div>
                    </div>
                    
                    <!-- Map -->
                    <div class="mt-4">
                        <iframe src="https://www.google.com/maps?q={{ urlencode($listing->location) }}&output=embed" 
                            class="w-full h-60 rounded-lg" 
                            allowfullscreen="" 
                            loading="lazy"></iframe>
                    </div>

                    <!-- Negotiate Button -->
                    <div class="mt-6 text-center">
                        <button onclick="openNegotiationModal()" class="bg-blue-600 text-white py-2 px-4 rounded-full hover:bg-blue-700">Negotiate this Space</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Negotiation Modal -->
    <div id="negotiationModal" class="fixed inset-0 z-50 flex bg-black bg-opacity-75 items-center justify-center hidden">
        <div class="relative bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <!-- Close Button -->
            <button onclick="closeNegotiationModal()" 
                    class="absolute top-0 right-0 mt-2 mr-2 text-gray-700 text-xl z-10 
                           bg-gray-300 hover:bg-gray-400 rounded-full h-8 w-8 flex items-center justify-center">
                &times;
            </button>
            @if ($listing->status == 'Occupied' || $listing->status == 'Deactivated')
                <h2 class="text-lg font-semibold mb-2">Negotiation Unavailable</h2>
                <p class="text-gray-600">This space is currently {{ strtolower($listing->status) }}. You cannot negotiate for it.</p>
            @else
                <h2 class="text-lg font-semibold mb-2">Negotiate for {{ $listing->title }}</h2>
                <form action="{{ route('negotiation.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="listingID" value="{{ $listing->listingID }}">
                    <input type="hidden" name="receiverID" value="{{ $listing->owner->userID }}">
                                        

                                        <!-- Rental Term -->
                                        <div class="flex flex-col">
                                            <label for="rentalTerm" class="block font-semibold text-gray-700">Rental Term:</label>
                                            <select class="p-2 border border-gray-300 rounded-lg" name="rentalTerm" id="rentalTerm" required>
                                                <option value="">Choose...</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                                <option value="yearly">Yearly</option>
                                            </select>
                                        </div>

                                        <!-- Start Date -->
                                        <div class="flex flex-col mt-2">
                                            <label for="startDate" class="block mb-2 font-semibold text-gray-700">Start Date:</label>
                                            <input type="date" name="startDate" id="startDate" class="p-2 border border-gray-300 rounded-lg" required>
                                        </div>

                                        <!-- End Date -->
                                        <div class="flex flex-col mt-2">
                                            <label for="endDate" class="block mb-2 font-semibold text-gray-700">End Date:</label>
                                            <input type="date" name="endDate" id="endDate" class="mb-2 p-2 border border-gray-300 rounded-lg" required>
                                        </div>
                    <!-- Offer Amount -->
                    <div class="mb-4">
                        <label class="block text-gray-700">Offer Amount</label>
                        <input type="text" name="offerAmount" placeholder="e.g. ₱000.00" class="w-full p-3 border rounded-md" required>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-full hover:bg-green-700">Send Offer</button>
                    </div>
            </form>
            @endif
        </div>
    </div>

    <!-- Modal Scripts -->
    <script>
        function openNegotiationModal() {
            document.getElementById('negotiationModal').classList.remove('hidden');
        }

        function closeNegotiationModal() {
            document.getElementById('negotiationModal').classList.add('hidden');
        }
    </script>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 items-center justify-center flex">
        <div class="relative max-w-full max-h-full">
            <!-- Close Button with Circular Gray Background -->
            <button onclick="closeModal()" 
                    class="absolute top-0 right-0 mt-2 mr-2 text-white text-xl z-10 
                           bg-gray-700 bg-opacity-100 hover:bg-opacity-50 rounded-full h-10 w-10 flex items-center justify-center">
                &times;
            </button>
            <!-- Modal Image -->
            <img id="modalImage" src="" alt="Modal Image" class="rounded-lg" style="max-width: 90vw; max-height: 90vh; object-fit: contain;">
        </div>
    </div>
    
    <script>
        function openModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }
    </script>

</x-app-layout>
