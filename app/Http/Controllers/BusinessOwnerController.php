<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use App\Models\History;
use App\Models\Negotiation;
use App\Models\Payment;
use App\Models\Reviews;
use App\Models\RentalAgreement;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class BusinessOwnerController extends Controller
{
    /**
     * Display the dashboard for business owners.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
            // Define the locations you want to filter
            $locations = ['Cebu City', 'Mandaue City', 'Talisay City', 'Lapu-Lapu City', 'Naga City', 'Minglanilla City', 'Toledo City', 'Carcar', 'Asturias', 'Dumanjug', 'Barili', 'Danao'];
            
            // Initialize an array to store the counts
            $listingsCount = [];

            // Loop through the locations and get the count for each
            foreach ($locations as $location) {
                // Count listings for the current location, excluding Pending and Deactivated statuses
                $listingsCount[$location] = Listing::where('location', 'LIKE', '%' . $location . '%')
                                                    ->whereNotIn('status', ['Deactivated', 'Pending', 'Disapproved'])
                                                    ->count();
            }

        // Return the count to the view
        return view('dashboard.business', compact('listingsCount'));
    }


    public function showByLocation($location)
    {
        // Fetch all listings for the specific location
        $listings = Listing::with('owner')
                          ->where('location', 'LIKE', '%' . $location . '%')
                          ->whereNotIn('status', ['Deactivated', 'Pending', 'Disapproved'])
                          ->get();

        // Pass the listings and the location to the view
        return view('place.showByLocation', compact('listings', 'location'));
    }
    public function storeProofOfPayment(Request $request, $negotiationID)
    {
        // Validate the request input
        $validated = $request->validate([
            'proof' => 'required|mimes:jpg,jpeg,png,pdf|max:2048', // Allow images or PDFs up to 2MB
            'details' => 'nullable|string|max:255',
        ]);
        
        $negotiation = Negotiation::findOrFail($negotiationID);
        // Store the proof of payment file
        if ($request->hasFile('proof')) {   
            $proofPath = $request->file('proof')->store('payments', 'public'); // Save in storage/app/public/payments
        }

        // Create the payment record in the database
        $payment = Payment::create([
            'rentalAgreementID' => $negotiationID, // This can be linked to your rental agreement/negotiation ID
            'renterID' => Auth::id(),
            'amount' => $negotiation->offerAmount,// Assuming amount is already saved elsewhere
            'date' => now(),
            'proof' => $proofPath, // Save the proof path
            'details' => $request->details ?? '',
            'status' => 'pending',
        ]);
    
        $this->notifyAdminPayment($payment);

        // Redirect back with a success message
        return redirect()->route('business.dashboard', ['negotiationID' => $negotiationID])
                         ->with('success', 'Proof of payment submitted successfully!');
    }

    public function detail($listingID)
    {
    $listing = Listing::with(['owner', 'rentalAgreements.reviews'])->findOrFail($listingID);
    $ratings = $listing->rentalAgreements->flatMap->reviews; // Get all reviews related to the listing
    $averageRating = $ratings->avg('rate');
    return view('place.detail', compact('listing','averageRating'));
    }

    public function negotiations()
    {
        return view('negotiations.business');
    }
    public function bookinghistory()
    {
        $bhistory = Payment::with(['rentalAgreement.listing', 'rentalAgreement.spaceOwner'])  // Change 'spaceOwner' to 'owner' (for the space owner)
            ->where('renterID', auth()->id())
            ->where('status', 'received')  // Filter payments that have been received
            ->orderBy('date', 'desc')  // Order by the payment date in descending order
            ->get();
        
        return view('business_owner.bookinghistory', compact('bhistory'));
    }
    


    /**
     * Display rental agreements for feedback.
     *
     * @return \Illuminate\View\View
     */
    public function feedback($rentalAgreementID = null)
    {
        $ownerID = Auth::id(); // Get the authenticated user's ID

        // Fetch rental agreements where the user is either the owner or the renter
        $rentalAgreements = RentalAgreement::where('ownerID', $ownerID)
            ->orWhere('renterID', $ownerID)
            ->orderBy('dateCreated','desc')
            ->get();

        if ($rentalAgreementID) {
            $selectedAgreement = RentalAgreement::findOrFail($rentalAgreementID);
            
            // Check for feedback for the selected rental agreement
            $feedbackExists = Reviews::where('renterID', auth()->id())
                ->where('rentalAgreementID', $rentalAgreementID)
                ->exists();

            return view('business_owner.dashboard', compact('rentalAgreements', 'selectedAgreement', 'feedbackExists'));
        }

        $feedbacks = Reviews::where('renterID', auth()->id())->get()->keyBy('rentalAgreementID');

        // Pass the rental agreements and feedbacks to the view
        return view('business_owner.view_feedback', compact('rentalAgreements', 'feedbacks'));
    }


    public function action($negotiationID) {
        $rentalAgreement = RentalAgreement::findOrFail($negotiationID);
        return view('business_owner.feedback', compact('rentalAgreement'));
    }
    /**
     * Store the submitted feedback.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submit(Request $request)
    {   
        // Validate the incoming request
        $validated = $request->validate([
            'renterID' => 'required|exists:users,userID',
            'rentalAgreementID' => 'required|exists:rental_agreements,rentalAgreementID',
            'rate' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ]);

        // Check if feedback already exists
        $existingFeedback = Reviews::where('renterID', $validated['renterID'])
                                    ->where('rentalAgreementID', $validated['rentalAgreementID'])
                                    ->first();

        if ($existingFeedback) {
            return redirect()->back()->withErrors(['feedback' => 'You have already submitted feedback for this rental agreement.']);
        }

        // Create a new review
        Reviews::create($validated);

        // Redirect with a success message
        return redirect()->route('business.dashboard')->with('success', 'Feedback submitted successfully.');
    }
    public function proceedToPayment($negotiationID)
    {
        // You can retrieve the negotiation details by negotiation ID
        $negotiation = Negotiation::findOrFail($negotiationID);

        // Additional logic for processing payments could be implemented here

        // For now, we'll just return a view where you could show the payment form or details
        return view('business_owner.payment', compact('negotiation'));
    }

    protected function notifyAdminPayment($payment)
    {
    // Find the space owner based on the ownerID in the Listing model
        $adminUsers = User::where('role', 'admin')->get(); // Adjust based on your role management

        foreach ($adminUsers as $admin) {
            Notification::create([
                'n_userID' => $admin->userID,  // The space owner's user ID
                'data' => $payment->renter->firstName . ' ' . $payment->renter->lastName,  // Store the title in the notification's data field as JSON
                'type' => 'payment_submitted',  // Notification type
                ]);
        }   
    }
}    
