<!-- resources/views/space_owner/post-new-space.blade.php -->
<title>Post New Space</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Post New Space') }}
        </h2>
    </x-slot>

    <div class="w-full py-6 flex justify-center">
    <div class="max-w-auto mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6 overflow-hidden shadow-sm sm:rounded-lg">
            <form method="POST" action="{{ route('space.new.store') }}" enctype="multipart/form-data">
                @csrf
                <div>
                    <p class="font-bold text-xl">Space for rent</p>
                    <span class="text-gray-500">Be descriptive as possible</span>
                </div>
                <hr class="mb-4 bg-gray-800 h-0.5">

                <!-- Container to align form fields in two columns -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Left side fields (Title, Location, Upload Images) -->
                    <div>
                        <div class="mb-4">
                            <label class="block text-gray-700">Title</label>
                            <input type="text" name="title" class="text-sm w-full p-3 border rounded-md" placeholder="e.g Gate Space" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700">Location</label>
                            <input type="text" name="location" class="text-sm w-full p-3 border rounded-md" placeholder="e.g Pardo Cebu City" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700">Upload Images</label>
                            <input type="file" name="images[]" id="imageInput" class="w-full p-3 border rounded-md" multiple onchange="displayFileNames()">
                            <div id="fileNames" class="mt-2 text-gray-600"></div>
                        </div>
                    </div>

                    <!-- Right side (Description) -->
                    <div>
                        <label class="block text-gray-700">Description</label>
                        <textarea name="description" class="text-sm w-full p-3 border rounded-md h-32" placeholder="Short Description..." required></textarea>
                    </div>
                </div>

                <!-- Centered button -->
                <div class="flex justify-center mt-4">
                    <button type="submit" class="bg-orange-400 text-white py-2 px-4 rounded-md">Post Space</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script>
    let selectedFiles = [];

    function displayFileNames() {
        const input = document.getElementById('imageInput');
        const fileNamesDiv = document.getElementById('fileNames');
        selectedFiles = Array.from(input.files); // Store the selected files

        fileNamesDiv.innerHTML = ''; // Clear previous file names

        if (selectedFiles.length > 0) {
            const fileList = document.createElement('ul');
            fileList.className = 'list-disc list-inside';

            selectedFiles.forEach((file, index) => {
                const listItem = document.createElement('li');
                listItem.className = 'flex justify-between items-center';

                listItem.innerHTML = 
                    <span>${file.name}</span>
                    <button onclick="removeFile(${index})" class="text-red-500 ml-2 hover:text-red-700">
                        Remove
                    </button>
                ;
                
                fileList.appendChild(listItem);
            });

            fileNamesDiv.appendChild(fileList);
        } else {
            fileNamesDiv.textContent = 'No files selected';
        }
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1); // Remove the file from the array
        updateFileInput(); // Update the file input to reflect the changes
        displayFileNames(); // Re-display the file names
    }

    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        document.getElementById('imageInput').files = dataTransfer.files;
    }
</script>


</x-app-layout>