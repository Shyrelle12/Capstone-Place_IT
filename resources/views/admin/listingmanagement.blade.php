<title>Admin Listing Management</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Listing Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- Pending Listings Section -->
                    <h3 class="text-lg font-semibold mb-4">Pending Listings</h3>

                    @if($pendingListings->isEmpty())
                        <p class="text-gray-500 py-2 pb-2">No pending listings for approval.</p>
                    @else
                        <div class="mb-8 overflow-x-auto rounded-lg">
                        <table class="min-w-full table-auto mb-6 border-collapse" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border w-1/4">Title</th>
                                    <th class="px-6 py-3 border w-1/4">Location</th>
                                    <th class="px-6 py-3 border w-1/4">Owner</th>
                                    <th class="px-6 py-3 border w-1/4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingListings as $listing)
                                    <tr>
                                        <td class="border px-6 py-3">{{ $listing->title }}</td>
                                        <td class="border px-6 py-3">{{ $listing->location }}</td>
                                        <td class="border px-6 py-3">{{ $listing->owner->firstName }} {{ $listing->owner->lastName }}</td>
                                        <td class="border px-6 py-3">
                                            <div class="flex space-x-2">
                                                <form method="POST" action="{{ route('admin.approveListing', $listing->listingID) }}">
                                                    @csrf
                                                    <button class="bg-green-500 text-white px-4 py-2 rounded w-full">
                                                        Approve
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.disapproveListing', $listing->listingID) }}">
                                                    @csrf
                                                    <button class="bg-red-500 text-white px-4 py-2 rounded w-full">
                                                        Disapprove
                                                    </button>
                                                </form>
                                            </div>
                                                <button onclick="openModal({{ $listing->listingID }})" class="bg-blue-500 text-white px-4 py-2 rounded w-full">
                                                    View
                                                </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @endif

                    <!-- All Listings Section -->
                    <h3 class="text-lg font-semibold mb-4">All Listings</h3>

                    @if($allListings->isEmpty())
                        <p class="text-gray-500">No listings available.</p>
                    @else
                        <div class="mb-8 overflow-x-auto rounded-lg">
                        <table class="min-w-full table-auto border-collapse" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border w-1/4">Title</th>
                                    <th class="px-6 py-3 border w-1/4">Location</th>
                                    <th class="px-6 py-3 border w-1/4">Owner</th>
                                    <th class="px-6 py-3 border w-1/4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($allListings as $listing)
                                    <tr>
                                        <td class="text-center border px-6 py-3">{{ $listing->title }}</td>
                                        <td class="text-center border px-6 py-3">{{ $listing->location }}</td>
                                        <td class="text-center border px-6 py-3">{{ $listing->owner->firstName }} {{ $listing->owner->lastName }}</td>
                                        <td class="text-center border px-6 py-3">
                                            <span class="
                                                {{ $listing->status === 'Pending' ? 'text-gray-600' : '' }}
                                                {{ $listing->status === 'Disapproved' ? 'text-red-600' : '' }}
                                                {{ $listing->status === 'Vacant' ? 'text-green-600' : '' }}
                                                {{ $listing->status === 'Occupied' ? 'text-blue-600' : '' }}
                                                {{ $listing->status === 'Deactivated' || $listing->status === 'Another Term' ? 'text-red-600' : '' }}
                                                font-bold">
                                                {{ $listing->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    @endif

                   <!-- Modal -->
<div id="modal" class="flex fixed inset-0 bg-black bg-opacity-50 items-center justify-center hidden">
    <div class="bg-white rounded-lg w-1/2 p-8 relative overflow-auto max-h-[90vh]">
        <!-- X Button in the top-right corner -->
        <button onclick="closeModal()" class="absolute top-2 right-4 text-gray-500 hover:bg-red-500  hover:text-white text-2xl font-bold">
            &times;
        </button>

        <h3 class="text-xl font-bold mb-4">Listing Details</h3>
        <div id="modal-content">
            <!-- Content dynamically loaded via JavaScript -->
        </div>
        <div id="modal-images" class="mt-4">
            <!-- Carousel images will be injected here -->
        </div>
        <div id="image-navigation" class="mt-6 flex justify-center space-x-2">
            <button id="prevButton" onclick="prevImage()" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-full"><</button>
            <button id="nextButton" onclick="nextImage()" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-full">></button>
        </div>
    </div>
</div>

            </div>
        </div>
    </div>

    <!-- JavaScript for Modal -->
    <script>
    let currentImageIndex = 0;
let images = [];

function openModal(listingID) {
    fetch(`/admin/listingmanagement/view/${listingID}`)
        .then(response => response.json())
        .then(data => {
            let imageContent = '';

            // If the listing has images, show them
            if (data.images.length > 0) {
                images = data.images.map(image => `/storage/images/${image.image_path}`);
                currentImageIndex = 0; // Reset the index for new listing

                // Display the first image
                imageContent = `<img src="${images[currentImageIndex]}" alt="Listing Image" class="w-full h-auto rounded-lg">`;

                // Show or hide the navigation buttons based on the number of images
                if (images.length > 1) {
                    document.getElementById('image-navigation').style.display = 'flex';
                } else {
                    document.getElementById('image-navigation').style.display = 'none';
                }
            } else {
                imageContent = '<p>No images available for this listing.</p>';
                document.getElementById('image-navigation').style.display = 'none'; // Hide navigation if no images
            }

            document.getElementById('modal-content').innerHTML = `
                <p><strong>Title:</strong> ${data.title}</p>
                <p><strong>Location:</strong> ${data.location}</p>
                <p><strong>Description:</strong> ${data.description}</p>
                <p><strong>Owner:</strong> ${data.owner.firstName} ${data.owner.lastName}</p>
                <p><strong>Status:</strong> ${data.status}</p>
            `;
            document.getElementById('modal-images').innerHTML = imageContent;
            document.getElementById('modal').classList.remove('hidden'); // Show modal
        })
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden'); // Hide modal
}

function nextImage() {
    if (images.length > 0) {
        currentImageIndex = (currentImageIndex + 1) % images.length;
        document.getElementById('modal-images').innerHTML = `<img src="${images[currentImageIndex]}" alt="Listing Image" class="w-full h-auto rounded-lg">`;
    }
}

function prevImage() {
    if (images.length > 0) {
        currentImageIndex = (currentImageIndex - 1 + images.length) % images.length;
        document.getElementById('modal-images').innerHTML = `<img src="${images[currentImageIndex]}" alt="Listing Image" class="w-full h-auto rounded-lg">`;
    }
}

    </script>
</x-app-layout>
