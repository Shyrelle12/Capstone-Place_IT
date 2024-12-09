<!-- resources/views/welcome-modal.blade.php -->
<!-- Welcome Modal -->
<div id="welcomeModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="px-6 py-4 border-b">
            <h5 class="text-4xl font-semibold text-orange-500" id="welcomeModalLabel">Welcome to PlaceIt</h5>
            <button type="button" class="absolute top-3 right-3 text-gray-600 hover:text-gray-900" onclick="toggleModal()">
                &times;
            </button>
        </div>
        <div class="px-6 py-4">
            <!-- User-specific introductory message --> 
            @php
                $user = auth()->user();
            @endphp

            @if($user->role === 'space_owner')
                <h3 class="text-xl font-semibold">Welcome, {{ ucwords($user->firstName) }}  {{ ucwords($user->lastName) }}!</h3>
                <p>PlaceIT is a platform where you can list and rent out your available spaces to businesses. Our goal is to help you easily connect with business owners who are looking for spaces for their ventures. Whether it's a short-term lease or a long-term rental, we’re here to help make the process smoother for you.</p>
            @elseif($user->role === 'business_owner')
                <h3 class="text-xl font-semibold">Welcome, {{ ucwords($user->firstName)}} {{ ucwords($user->lastName) }}!</h3>
                <p>PlaceIT is designed to help you find the perfect space for your business. Whether you're looking for a retail shop, office, or warehouse, you can browse our listings from trusted space owners. We’re dedicated to providing a seamless experience for your space rental needs.</p>
            @endif

            <!-- General message for all users -->
            <h4 class="mt-4 text-lg font-semibold">Important Information About Transactions</h4>
            <p>As you navigate through PlaceIT, please be aware of the following legal requirements and warnings regarding financial transactions:</p>
            
            <h5 class="mt-4 font-semibold">Articles and Laws Regarding Transactions, Scams, and Frauds</h5>
            <ul class="list-disc pl-5">
                <li><strong>Philippine E-Commerce Act of 2000 (Republic Act No. 8792)</strong> - This law regulates online transactions, including contracts and fraud prevention in digital spaces. Be aware that any act of fraudulence, scamming, or misrepresentation during the transaction process is punishable by law.</li>
                <li><strong>Anti-Cybercrime Law (Republic Act No. 10175)</strong> - This law penalizes any fraudulent activity committed over electronic platforms, such as PlaceIT. Users who attempt to scam or mislead others could face imprisonment or hefty fines.</li>
                <li><strong>Revised Penal Code on Estafa (Fraud)</strong> - Committing acts of estafa, such as falsely obtaining money through deceit, is punishable by imprisonment under the Revised Penal Code of the Philippines.</li>
            </ul>

            <h5 class="mt-4 font-semibold">Warnings and Guidelines</h5>
            <p>We highly advise all users to adhere to the following guidelines to avoid disputes and potential legal consequences:</p>
            <ul class="list-disc pl-5">
                <li>Always verify the identity of the person or entity you are transacting with.</li>
                <li>Avoid making advance payments until a contract has been signed or legal obligations are clear.</li>
                <li>Report any suspicious activity to our team immediately through the provided channels.</li>
                <li>Please be aware that any confirmed instances of scamming or fraud may lead to the immediate deactivation of your account.</li>
            </ul>

            <h5 class="mt-4 font-semibold">Contract Agreements</h5>
            <p>All agreements made on this platform must be backed by a legal contract. Both parties (space owners and business owners) are required to read and agree to the terms outlined in the contract before proceeding with any financial transactions. Failure to do so may void your protection under the law.</p>

            <p class="mt-4">Please ensure that all contracts include the following details:</p>
            <ul class="list-disc pl-5">
                <li>Complete identification of both parties</li>
                <li>Clear description of the rental terms and conditions</li>
                <li>Rental amounts and payment schedules</li>
                <li>Termination and renewal clauses</li>
            </ul>
            @if($user->role === 'business_owner')
            <h5 class="mt-4 font-semibold">Refund Policy</h5>
            <ul class="list-disc pl-5">
                <li>Refunds are allowed only if a user files a complaint within 7 days after the scheduled booking date.</li>
                <li>Refunds will not be processed if the user fails to provide valid reasons or evidence to support their complaint.</li>
            </ul>
            <p class="mt-4">Investigation Process:</p>
            <ul class="list-disc pl-5">
                <li>All complaints will be reviewed by PlaceIt's support team.</li>
                <li>The investigation may include:
                    <ul>
                        <li>Review of submitted evidence (e.g., photos, videos).</li>
                        <li>Communication with the Space Owner and the user.</li>
                        <li>Verification of the space's condition or booking status.</li>
                    </ul>
                </li>
            </ul>
            <p class="mt-4">Refund Decisions:</p>
            <ul class="list-disc pl-5">
                <li>Refunds may be partial or full, depending on the outcome of the investigation.</li>
                <li>If the complaint is deemed invalid, the user will not receive a refund.</li>
            </ul>
            <p class="mt-4">Non-Refundable Situations:</p>
            <ul class="list-disc pl-5">
                <li>Failure to use the booking without prior notice or valid reasons.</li>
                <li>Cancellations made outside the allowable cancellation period.</li>
            </ul>
            <h5 class="mt-4 font-semibold">Complaint Policy</h5>
            <p class="mt-4">Filing Complaints:</p>
            <ul class="list-disc pl-5">
                <li>Users can submit complaints via the platform’s dedicated email address, providing:
                    <ul>
                        <li>Booking details.</li>
                        <li>Description of the issue.</li>
                        <li>Evidence (if applicable).</li>
                    </ul>
                </li>
            </ul>
            <p class="mt-4">Resolution Timeline:</p>
            <ul class="list-disc pl-5">
                <li>PlaceIt will review complaints within 5–10 business days and notify the user of the resolution.
                </li>
            </ul>
            <p class="mt-4">Outcome of Complaints:</p>
            <ul class="list-disc pl-5">
                <li>Complaints resulting in approved refunds will follow the Refund Policy.</li>
                <li>Complaints against Space Owners may result in penalties, account suspension, or removal from the platform.</li>
            </ul>
            <p class="mt-4">Final Decision</p>
            <ul class="list-disc pl-5">
                <li>PlaceIt's decision on complaints and refunds is final and binding.</li>
            </ul>
            @elseif($user->role === 'space_owner')
            <h5 class="mt-4 font-semibold">Payout Policy for Space Owners</h5>
            <p>Earnings Hold Period:</p>
            <ul class="list-disc pl-5">
                <li>Payments received from Business owners/renter for bookings will be held by PlaceIt until the booking is completed and verified.</li>
                <li>Verification occurs when the Business owners/renter confirms successful usage of the space or if no complaints are filed within the specified period.</li>
            </ul>
            <p class="mt-4">Transfer Schedule:</p>
            <ul class="list-disc pl-5">
                <li>Space Owners will receive their earnings (less the commission fee) via Gcash right after you send proof of meetup.</li>
            </ul>
            <p class="mt-4">Fees and Deductions:</p>
            <ul class="list-disc pl-5">
                <li>PlaceIt reserves the right to deduct additional fees (e.g., penalties for violations) before transferring earnings to the Space Owner.</li>
            </ul>
            <p class="mt-4">Disputes:</p>
            <ul class="list-disc pl-5">
                <li>In the case of any dispute regarding payments, the decision of PlaceIt's administrative team will be final.</li>
            </ul>
            @endif
        </div>
        <div class="px-6 py-4 border-t">
            <button type="button" class="btn btn-primary w-full font-bold bg-blue-500 text-black py-2 rounded hover:bg-blue-600" onclick="acceptTerms()">I Agree the Terms and Conditions</button>
        </div>
    </div>
</div>

<!-- Script to show/hide the modal -->
<script>
    function toggleModal() {
        const modal = document.getElementById('welcomeModal');
        modal.classList.toggle('hidden');
    }

    // Automatically show the modal when the user logs in
    $(document).ready(function() {
        $('#welcomeModal').removeClass('hidden'); // Show the modal
    });

    function acceptTerms() {
        $.ajax({
            url: '{{ route('terms.accept') }}', // Define the route for accepting terms
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}', // Include CSRF token
                terms_accepted: true,
            },
            success: function(response) {
                $('#welcomeModal').addClass('hidden'); // Hide the modal
                // Optionally, you can add a success message or redirect
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                // Handle error if needed
            }
        });
    }
</script>
