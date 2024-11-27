<title>Space Owner Negotiation Details</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Negotiation Details') }}
        </h2>
    </x-slot>
    <style>
        @media (max-width: 768px) {
        .chat-box {
            max-height: calc(100vh - 200px); /* Adjust based on your header/footer height */
            height: auto; /* Let the content determine height */
                }
        }

    </style>
    <div class="w-full py-2 flex justify-center px-2">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-7xl">
        <div class="flex flex-col lg:flex-row lg:h-[500px] flex-wrap">
            <!-- Chat Section -->
            <div class="w-full lg:w-2/3 p-4 border-b lg:border-b-0 lg:border-r border-gray-300">
                <div class="h-full flex flex-col justify-between">
                    <!-- Name Section -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-300">
                        <div class="flex items-center text-gray-800">
                            <a href="{{ route('space.negotiations') }}" class="flex items-center text-orange-500 hover:text-orange-800 hover:bg-gray-200 rounded-3xl p-2">
                                <span class="flex items-center">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                </span>
                            </a>
                            <span class="font-semibold p-4 text-lg sm:text-xl">
                                {{ ucwords($negotiation->sender->firstName) }} {{ ucwords($negotiation->sender->lastName) }}
                            </span>
                        </div>
                    </div>

                    <!-- Messages Section -->
                    <div class="space-y-4 overflow-y-auto flex-1 chat-box pt-4" style="height: calc(100vh - 100px); min-height: 200px;" >
                        @foreach($negotiation->replies as $reply)
                            <div class="flex {{ $reply->senderID == Auth::id() ? 'justify-end' : 'justify-start' }}">
                                <div class="p-4 rounded-lg shadow-lg {{ $reply->senderID == Auth::id() ? 'bg-blue-500 text-white' : 'bg-gray-200' }}">
                                    @if (preg_match('/\.(jpeg|jpg|png|gif)$/i', $reply->message))
                                        <img src="{{ asset('storage/negotiation_images/' . $reply->message) }}" alt="Image" class="max-w-full sm:max-w-xs rounded-lg cursor-pointer" onclick="openModal('{{ asset('storage/negotiation_images/' . $reply->message) }}')">
                                    @else
                                        <p class="text-sm">{{ $reply->message }}</p>
                                    @endif
                                    <small class="text-xs">{{ $reply->created_at->format('h:i A') }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>


                    <!-- Message Input -->
                    <form action="{{ route('negotiation.reply', ['negotiationID' => $negotiation->negotiationID]) }}" method="POST" enctype="multipart/form-data" class="mt-4">
                        @csrf
                        <div class="flex items-center mb-10 bg-gray-100 p-2 rounded-lg space-x-2">
                            
                            <!-- File Attachment -->
                            <label for="aImage" class="cursor-pointer flex items-center justify-center bg-gray-200 text-gray-600 p-2 rounded-lg hover:bg-gray-300">
                                <i class="fas fa-paperclip"></i>
                            </label>
                            <input type="file" name="aImage" id="aImage" class="hidden" onchange="showFileName()"/>

                            <!-- File Name Display -->
                            <span id="fileName" class="text-gray-600 text-sm"></span>

                            <!-- Clear File Button -->
                            <button type="button" id="clearFileBtn" class="hidden text-red-500 text-sm underline" onclick="clearFileSelection()">Clear</button>

                            <!-- Message Input -->
                            <input 
                                type="text" 
                                name="message" 
                                id="messageInput" 
                                class="flex-grow p-2 border rounded-lg focus:outline-none focus:ring focus:ring-blue-500" 
                                placeholder="Type your message..."
                            />

                            <!-- Send Button -->
                            <button 
                                type="submit" 
                                class="bg-blue-600 text-white p-2 rounded-lg hover:bg-blue-700 flex items-center"
                            >
                                Send
                            </button>
                        </div>
                    </form>

                </div>
            </div>


                <!-- Negotiation Details Section -->
                <div class="w-full lg:w-1/3 p-4 overflow-y-auto max-h-screen">
                    <!-- Amount and Status Section -->
                    <div class="flex flex-col h-full justify-between">
                        <div class="mb-4">
                            <div class="flex items-center mb-2">
                                <div class="mr-4">
                                    <h4 class="text-lg font-semibold pl-2">Amount Offered</h4>
                                    <input class="pl-7 text-2xl bg-gray-100 rounded-md w-40 font-bold" value="₱{{ number_format($negotiation->offerAmount, 2) }}" readonly>
                                </div>
                                <div class="w-1/2 text-right mt-4 md:mt-0">
                                    <h4 class="text-lg font-semibold pr-4">Status</h4>
                                    <span class="
                                        {{ $negotiation->negoStatus === 'Approved' ? 'text-xl font-bold text-green-600' : '' }}
                                        {{ $negotiation->negoStatus === 'Pending' ? 'text-xl font-bold text-blue-600' : '' }}
                                        {{ $negotiation->negoStatus === 'Declined' || $negotiation->negoStatus === 'Another Term' ? 'text-xl font-bold text-red-600' : '' }}
                                        font-bold">
                                        {{ $negotiation->negoStatus }}
                                    </span>
                                </div>
                            </div>
                        </div>

                            <!-- Rental Agreement Section -->
                            <div class="mb-1">
                                <h4 class="text-lg font-semibold">Rental Agreement</h4>
                                @if($negotiation->rentalAgreement)
                                    <div class="bg-gray-100 p-6 rounded-lg">
                                        <p><strong>Rental Term:</strong> {{ ucfirst($negotiation->rentalAgreement->rentalTerm) }}</p>
                                        <p><strong>Offer Amount:</strong> ₱{{ number_format($negotiation->rentalAgreement->offerAmount, 2) }}</p>
                                        <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($rentalAgreement->dateStart)->format('M d, Y') }}</p>
                                        <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($rentalAgreement->dateEnd)->format('M d, Y') }}</p>

                                        @if($negotiation->rentalAgreement->status !== 'approved')
                                            <!-- Approve Button -->
                                            <form action="{{ route('rentalagreement.approve', ['negotiationID' => $negotiation->negotiationID, 'rentalAgreementID' => $rentalAgreement->rentalAgreementID]) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 w-full mt-4">
                                                    Approve Rental Agreement
                                                </button>
                                            </form>
                                        @else
                                            <!-- Guide Message -->
                                            <p class="text-green-600 font-light mb-2">Rental Agreement has been approved. You may now proceed with the next steps.</p>
                                            @if(!$billing)  <!-- Check if GCash details have NOT been submitted -->
                                                <button id="openModalButton" data-offer-amount="{{ $negotiation->offerAmount }}" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 w-full text-center">
                                                    Click to send GCash details
                                                </button>
                                            @elseif($billing && !isset($negotiation->meetupProof))  <!-- Check if GCash details were submitted but proof of meetup NOT uploaded -->
                                                <p class="text-blue-600 font-light mb-2">GCash details submitted. Please send proof of meetup.</p>
                                                <button id="openProofButton" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 w-full text-center">
                                                    Click to send proof of meetup
                                                </button>
                                            @elseif(isset($negotiation->meetupProof))  <!-- Check if proof of meetup has been uploaded -->
                                                <p class="text-blue-600 font-light mb-2">Proof of meetup was sent/uploaded. Wait for confirmation of the admin.</p>
                                            @endif
                                        @endif
                                    </div>
                                @else
                                    <p class="text-red-500">No rental agreement submitted yet.</p>
                                @endif
                            </div>
                            <div class="mt-auto pb-4">
                            <!-- Update Negotiation Status Section -->
                            @if($negotiation->negoStatus !== 'Approved' && $negotiation->negoStatus !== 'Declined')
                                @if($negotiation->rentalAgreement && $billing && isset($negotiation->meetupProof))
                                    <!-- Show the Update Negotiation Status form only after all required actions are completed -->
                                    <form action="{{ route('negotiation.updateStatus', ['negotiationID' => $negotiation->negotiationID]) }}" method="POST">
                                        @csrf
                                        <div class="mb-4">
                                            <label for="status" class="block text-lg font-semibold">Update Negotiation Status:</label>
                                            <select name="status" id="status" class="form-select mt-1 block w-full">
                                                <option value="Pending" {{ $negotiation->negoStatus === 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Approved" {{ $negotiation->negoStatus === 'Approved' ? 'selected' : '' }}>Approve</option>
                                                <option value="Declined" {{ $negotiation->negoStatus === 'Declined' ? 'selected' : '' }}>Decline</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="bg-green-600 text-white mb-6 py-2 px-4 rounded-lg hover:bg-green-700 w-full">Submit</button>
                                    </form>
                                @else
                                    <!-- Placeholder message guiding the user -->
                                    <p class="text-gray-600 text-sm mb-2">Complete the approval, GCash details, and meetup proof steps to enable negotiation status update.</p>
                                @endif
                            @elseif($negotiation->negoStatus === 'Declined')
                                <p class="text-red-600 font-light mb-2">
                                    Negotiation status declined. This space is not available for further transactions. Please contact support for more details.
                                </p>
                            @else
                                <!-- Message displayed after the negotiation status is approved -->
                                <p class="text-green-600 font-light mb-2 md:mb-9">
                                    Negotiation status approved. The space is now occupied and cannot be rented by others.
                                </p>
                              
                            @endif
                        </div>
                        </div>
                        <form id="myForm" action="{{ route('billing.store', ['negotiationID' => $negotiation->negotiationID]) }}" method="POST">
                        @csrf
                        <div id="detailsModal" class="fixed inset-0 bg-gray-500 flex bg-opacity-75 items-center justify-center hidden">
                            <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full relative">
                                <button onclick="closeDetailsModal()" 
                                        class="absolute top-0 right-0 mt-2 mr-2 text-gray-600 text-xl z-10 
                                            hover:bg-gray-200 rounded-full h-8 w-8 flex items-center justify-center">
                                    &times;
                                </button>
                                <h3 class="text-lg font-semibold mb-4">Send Details</h3>
                                <div class="mb-4">
                                    <label for="amountSent" class="block text-sm font-semibold">Amount Sent:</label>
                                    <input type="text" id="amountSent" name="amountSent" class="form-input mt-1 block w-full" disabled>
                                </div>
                                <div class="mb-4">
                                    <label for="taxPayment" class="block text-sm font-semibold">Tax Payment (10%):</label>
                                    <input type="text" id="taxPayment" name="taxPayment" class="form-input mt-1 block w-full" disabled>
                                </div>
                                <div class="mb-4">
                                    <label for="total" class="block text-sm font-semibold">Total:</label>
                                    <input type="text" id="total" name="total" class="form-input mt-1 block w-full" disabled>
                                </div>
                                <div class="mb-4">
                                    <label for="gcashNumber" class="block text-sm font-semibold">Gcash Number:</label>
                                    <input type="text" id="gcashNumber" name="gcashNumber" class="form-input mt-1 block w-full" placeholder="0911-222-3333" required>
                                </div>
                                <div class="mb-4">
                                    <h4 class="text-sm font-semibold">Terms and Conditions:</h4>
                                    <p class="text-xs text-gray-600 mt-2">
                                        The 10% tax deduction is applied to cover commission fees and other associated costs. This ensures that the transaction can proceed smoothly and all necessary charges are accounted for. The total amount reflects the final amount after the deduction of these fees.
                                    </p>
                                    <input type="checkbox" id="myCheckbox" name="myCheckbox" required>
                                    <label for="myCheckbox" class="text-sm">I agree to the terms and conditions</label>
                                </div>
                                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 w-full">Submit</button>
                            </div>
                        </div>
                        </form>
                        <!-- Modal Structure -->
                        <div id="proofModal" class="hidden flex fixed z-50 inset-0 items-center justify-center bg-gray-900 bg-opacity-75">
                            <div class="bg-white rounded-lg shadow-lg p-6 w-1/3">
                                <!-- Modal Header -->
                                <div class="flex justify-between items-center pb-2 border-b">
                                    <h2 class="text-lg font-semibold">Send Proof of Meetup</h2>
                                    <button id="closeModalButton" class="text-gray-500 hover:text-gray-700">X</button>
                                </div>
                                
                                <!-- Modal Body -->
                                <div class="mt-4">
                                    <form id="proofForm" action="{{ route('meetup.store', ['negotiationID' => $negotiation->negotiationID]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf    
                                        <label for="proofFile" class="block text-sm font-medium text-gray-700">Upload Proof (image)</label>
                                        <input type="file" name="proofFile" id="proofFile" accept="image/*" class="mt-2 w-full border border-gray-300 rounded-md p-2">
                                        <div class="mt-4">
                                            <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 w-full">
                                                Submit Proof
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
    document.addEventListener("DOMContentLoaded", function () {
    // Gcash Modal
    const gcashModal = document.getElementById("detailsModal");
    const openModalButton = document.getElementById("openModalButton");
    const offerAmountInput = document.getElementById("amountSent");
    const taxPaymentInput = document.getElementById("taxPayment");
    const totalInput = document.getElementById("total");

    openModalButton?.addEventListener("click", function () {
        const offerAmount = this.getAttribute("data-offer-amount");
        const taxAmount = (offerAmount * 0.1).toFixed(2);
        const totalAmount = (offerAmount - taxAmount).toFixed(2);

        offerAmountInput.value = `₱${offerAmount}`;
        taxPaymentInput.value = `₱${taxAmount}`;
        totalInput.value = `₱${totalAmount}`;

        gcashModal.classList.remove("hidden");
    });

    // Close Gcash Modal on clicking outside or using close button
    window.addEventListener("click", function (event) {
        if (event.target === gcashModal) {
            closeDetailsModal();
        }
    });

    function closeDetailsModal() {
        gcashModal.classList.add("hidden");
    }

    // Proof Modal logic remains the same
    const proofModal = document.getElementById("proofModal");
    const openProofButton = document.getElementById("openProofButton");
    const closeModalButton = document.getElementById("closeModalButton");

    openProofButton?.addEventListener("click", () => {
        proofModal.classList.remove("hidden");
        proofModal.classList.add("flex");
    });

    closeModalButton?.addEventListener("click", () => {
        proofModal.classList.add("hidden");
        proofModal.classList.remove("flex");
    });

    window.addEventListener("click", (event) => {
        if (event.target === proofModal) {
            proofModal.classList.add("hidden");
            proofModal.classList.remove("flex");
        }
    });
});
    
    let userScrolledUp = false;

    function fetchMessages() {
        $.ajax({
            url: '{{ route("negotiation.getMessages", ["negotiationID" => $negotiation->negotiationID]) }}',
            method: 'GET',
            success: function(data) {
                updateChat(data);
                scrollToBottom(); // Automatically scroll to the bottom after updating the chat, if user hasn't scrolled up
            }
        });
    }

    function updateChat(messages) {
        let chatBox = $('.chat-box');
        chatBox.empty();

        messages.forEach(function(message) {
            let messageHtml = '';

            if (/\.(jpeg|jpg|png|gif)$/i.test(message.message)) {
                // If the message is an image
                messageHtml = `
                    <div class="flex ${message.senderID == {{ Auth::id() }} ? 'justify-end' : 'justify-start'}">
                        <div class="p-4 rounded-lg shadow-lg ${message.senderID == {{ Auth::id() }} ? 'bg-blue-500 text-white' : 'bg-gray-200'}">
                            <img src="{{ asset('storage/negotiation_images/') }}/${message.message}" alt="Image" class="max-w-xs rounded-lg cursor-pointer" onclick="openModal('{{ asset('storage/negotiation_images/') }}/${message.message}')">
                            <small class="text-xs">${new Date(message.created_at).toLocaleTimeString()}</small>
                        </div>
                    </div>
                `;
            } else {
                messageHtml = `
                    <div class="flex ${message.senderID == {{ Auth::id() }} ? 'justify-end' : 'justify-start'}">
                        <div class="p-4 rounded-lg shadow-lg ${message.senderID == {{ Auth::id() }} ? 'bg-blue-500 text-white' : 'bg-gray-200'}">
                            <p class="text-sm">${message.message}</p>
                            <small class="text-xs">${new Date(message.created_at).toLocaleTimeString()}</small>
                        </div>
                    </div>
                `;
            }

            chatBox.append(messageHtml);
        });

        scrollToBottom(); // Scroll to the bottom if user has not scrolled up
    }

    function scrollToBottom() {
        let chatBox = $('.chat-box');

        // Only scroll to the bottom if user hasn't scrolled up
        if (!userScrolledUp) {
            chatBox.scrollTop(chatBox.prop("scrollHeight"));
        }
    }

    // Detect if user scrolls up in the chat box
    $('.chat-box').on('scroll', function() {
        let chatBox = $(this);

        // If user is near the bottom (within 50px), we consider it as not scrolled up
        if (chatBox.scrollTop() + chatBox.innerHeight() >= chatBox.prop('scrollHeight') - 50) {
            userScrolledUp = false; // User is near the bottom
        } else {
            userScrolledUp = true; // User has scrolled up
        }
    });

    $(document).ready(function() {
        scrollToBottom(); // Immediately scroll to the bottom when the page is ready
    });

    // Fetch messages every 1 second
    setInterval(fetchMessages, 1000);

            function showFileName() {
                    const fileInput = document.getElementById("aImage");
                    const fileNameDisplay = document.getElementById("fileName");
                    const clearFileBtn = document.getElementById("clearFileBtn");
                    const messageInput = document.getElementById("messageInput");

                    if (fileInput.files.length > 0) {
                            const fullFileName = fileInput.files[0].name;
                            const truncatedFileName = truncateFileName(fullFileName, 15); // Adjust the character limit as needed
                            fileNameDisplay.textContent = truncatedFileName;
                            clearFileBtn.classList.remove("hidden");
                            messageInput.classList.add("hidden"); // Hide the message input when an image is selected
                        } else {
                            clearFileSelection();
                        }
                    }

            function truncateFileName(fileName, maxLength) {
                const extension = fileName.slice(fileName.lastIndexOf("."));
                const baseName = fileName.slice(0, fileName.lastIndexOf("."));
                if (baseName.length > maxLength) {
                    return `${baseName.slice(0, maxLength)}...${extension}`;
                }
                return fileName;
            }

        function openModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        function toggleMessageInput() {
        const fileInput = document.getElementById('aImage');
        const messageInput = document.getElementById('messageInput');
        const fileNameDisplay = document.getElementById('fileName');
        const clearFileBtn = document.getElementById('clearFileBtn');

        if (fileInput.files.length > 0) {
            // Hide message input and show file name
            messageInput.classList.add('hidden');
            clearFileBtn.classList.remove('hidden');
            fileNameDisplay.textContent = fileInput.files[0].name;
        } else {
            // Show message input if no file is selected
            messageInput.classList.remove('hidden');
            clearFileBtn.classList.add('hidden');
            fileNameDisplay.textContent = '';
        }
    }

        function clearFileSelection() {
            const fileInput = document.getElementById("aImage");
            const fileNameDisplay = document.getElementById("fileName");
            const clearFileBtn = document.getElementById("clearFileBtn");
            const messageInput = document.getElementById("messageInput");

            fileInput.value = ""; // Clear file input
            fileNameDisplay.textContent = ""; // Clear displayed file name
            clearFileBtn.classList.add("hidden"); // Hide the clear button
            messageInput.classList.remove("hidden"); // Show the message input
        }

    </script>
    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-75 items-center flex justify-center">
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

</x-app-layout>