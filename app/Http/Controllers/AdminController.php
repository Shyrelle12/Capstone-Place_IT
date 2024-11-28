<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\ListingImages;
use App\Models\User;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Negotiation;
use App\Models\SystemFeedback;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\AccountDeactivation;
use App\Mail\AccountActivation;

class AdminController extends Controller
{
    /**
     * Display the dashboard for business owners.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get counts for statistics
        $userCount = User::where('role', '!=', 'admin')->count();
        $listingCount = Listing::count();
        $verifiedUsersCount = User::where('isVerified', 1)
                              ->where('role', '!=', 'admin')
                              ->count();
        $spaceOwnersCount = User::where('role', 'space_owner')->count();
        $businessOwnersCount = User::where('role', 'business_owner')->count();
        
        // Pass statistics to the view
        return view('dashboard.admin', compact('userCount', 'listingCount', 'verifiedUsersCount', 'spaceOwnersCount', 'businessOwnersCount'));
    }

    public function users() {
        $userCount = User::where('role', '!=', 'admin')->count();
        $users  = User::where('role', '!=', 'admin')->get();
        return view('admin.usermanagement', compact('userCount','users'));
    }
    // NAVBAR USERS ACCOUNT MANAGEMENT
    public function spaceOwners()
    {
        $users = User::where('role', 'space_owner')->get();
        $userCount = $users->count();
        $currentRole = 'space_owner';
        return view('admin.usermanagement', compact('userCount', 'users', 'currentRole'));
    }

    public function businessOwners()
    {
        $users = User::where('role', 'business_owner')->get();
        $userCount = $users->count();
        $currentRole = 'business_owner';
        return view('admin.usermanagement', compact('userCount', 'users', 'currentRole'));
    }

    public function adminUsers()
    {
        $users = User::where('role', 'admin')->get();
        $userCount = $users->count();
        $currentRole = 'admin';
        return view('admin.usermanagement', compact('userCount', 'users', 'currentRole'));
    }

    public function listing() {
        $pendingListings = Listing::where('status', 'pending')->get();
        $userCount = User::where('role', '!=', 'admin')->count();
        $listingCount = Listing::count();
        $allListings = Listing::orderBy('dateCreated','desc')->get();

        return view('admin.listingmanagement', compact('userCount','listingCount','pendingListings','allListings'));
    }
    public function approveListing($listingID) {
        // Find the listing by listingID, not 'id'
        $listing = Listing::where('listingID', $listingID)->first();
        
        // Approve the listing
        if ($listing) {
            $listing->status = 'Vacant';
            $listing->approvedBy_userID = Auth::id();
            $listing->save();
            $this->notifySpaceOwnerApprove($listing);
        }
    
        // Redirect back to the listing management page with success message
        return redirect()->route('admin.listingmanagement')->with('success', 'Listing approved successfully!');
    }
    
    public function disapproveListing($listingID) {
        $listing = Listing::where('listingID', $listingID)->first();
        if ($listing) {
            $listing->status = 'Disapproved';
            $listing->approvedBy_userID = Auth::id();
            $listing->save();
            $this->notifySpaceOwnerDisapprove($listing);
        }
    
        return redirect()->route('admin.listingmanagement')->with('success', 'Listing disapproved!');
    }
    
    public function viewListing($listingID) {
        // Find the listing by listingID, with its images and owner
        $listing = Listing::with('images', 'owner')->where('listingID', $listingID)->first();
    
        // Check if the listing exists
        if (!$listing) {
            return response()->json(['error' => 'Listing not found'], 404);
        }
    
        // Return the listing details and images as JSON
        return response()->json($listing);
    }

    public function payment() {
        // Fetch all payments with related details
        $payments = Payment::with(['renter', 'rentalAgreement.listing.owner', 'spaceOwner', 'billing', 'negotiation'])
            ->orderBy('date','desc')->get();
    
        $userCount = User::where('role', '!=', 'admin')->count();
    
        return view('admin.payment', compact('userCount', 'payments'));
    }

    public function updatePaymentStatus(Request $request, $paymentID)
    {
        // Find the payment by ID
        $payment = Payment::find($paymentID);

        if (!$payment) {
            return redirect()->back()->with('error', 'Payment not found.');
        }
        
        // Update the status based on form input
        $payment->status = $request->input('status');
        
        // Save the updated payment
        $payment->save();

        if ($payment->status === 'confirmed') {
            // Find the related RentalAgreement
            $rentalAgreement = $payment->rentalAgreement;
    
            if ($rentalAgreement) {
                // Update the isPaid field to true (1)
                $rentalAgreement->isPaid = true; // Or $rentalAgreement->isPaid = 1;
                $rentalAgreement->save();
            }
        }
        
        $this->notifyOwnerPayment($payment);
        $this->notifyBusinessOwnerPayment($payment);

        return redirect()->back()->with('success', 'Payment status updated successfully!');
    }
    public function transfer(Request $request)
    {
        // Find the payment by ID
        $payment = Payment::find($request->paymentID);

        // Handle the proof of payment file upload
        if ($request->hasFile('proof')) {
            $filePath = $request->file('proof')->store('payments', 'public');
            $payment->admin_proof = $filePath; // Save proof uploaded by admin
        }

        $payment->status = 'transferred';  // Update status to transferred
        $payment->save();
        $this->notifyOwnerSentPayment($payment);
        

        return redirect()->back()->with('success', 'Payment transferred successfully!');
    }

    protected function notifySpaceOwnerApprove(Listing $listing)
    {
    // Find the space owner based on the ownerID in the Listing model
    $spaceOwner = User::find($listing->ownerID);  // Assuming ownerID is the space owner's user ID

    // Check if the space owner exists
    if ($spaceOwner) {
        // Create the notification for the space owner
        Notification::create([
            'n_userID' => $spaceOwner->userID,  // The space owner's user ID
            'data' => $listing->title,  // Store the title in the notification's data field as JSON
            'type' => 'listing_approved',  // Notification type
            ]);
        }
    }
    protected function notifySpaceOwnerDisapprove(Listing $listing)
    {
    // Find the space owner based on the ownerID in the Listing model
    $spaceOwner = User::find($listing->ownerID);  // Assuming ownerID is the space owner's user ID

    // Check if the space owner exists
    if ($spaceOwner) {
        // Create the notification for the space owner
        Notification::create([
            'n_userID' => $spaceOwner->userID,  // The space owner's user ID
            'data' => $listing->title,  // Store the title in the notification's data field as JSON
            'type' => 'listing_disapproved',  // Notification type
            ]);
        }
    }

    protected function notifyOwnerPayment(Payment $payment)
    {
    // Find the space owner based on the ownerID in the Listing model
    $spaceOwner = $payment->spaceOwner;  // Assuming ownerID is the space owner's user ID

    // Check if the space owner exists
    if ($spaceOwner) {

        $businessOwner = $payment->renter;
        // Create the notification for the space owner
        Notification::create([
            'n_userID' => $spaceOwner->userID,  // The space owner's user ID
            'data' => $businessOwner->firstName . ' ' . $businessOwner->lastName,   // Store the title in the notification's data field as JSON
            'type' => 'payment_confirmed',  // Notification type
            ]);
        }
    }

    protected function notifyBusinessOwnerPayment(Payment $payment)
    {
    // Find the space owner based on the ownerID in the Listing model
    $renter = $payment->renter;  // Assuming ownerID is the space owner's user ID

    // Check if the space owner exists
    if ($renter) {

        $businessOwner = $payment->renter;
        // Create the notification for the space owner
        Notification::create([
            'n_userID' => $renter->userID,  // The space owner's user ID
            'data' => 'Payment',   // Store the title in the notification's data field as JSON
            'type' => 'payment_confirmed',  // Notification type
            ]);
        }
    }

    protected function notifyOwnerSentPayment(Payment $payment)
    {
    // Find the space owner based on the ownerID in the Listing model
    $spaceOwner = $payment->spaceOwner;  // Assuming ownerID is the space owner's user ID

    // Check if the space owner exists
    if ($spaceOwner) {
        // Create the notification for the space owner
        Notification::create([
            'n_userID' => $spaceOwner->userID,  // The space owner's user ID
            'data' => 'You have received a payment sent by admin, check your GCash.',  // Store the title in the notification's data field as JSON
            'type' => 'payment_sent',  // Notification type
            ]);
        }
    }

    public function create() {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email', // Ensure email is unique
            'password' => 'required|string|min:8|confirmed', // Password confirmation required
            'mobile_number' => 'required|string|max:15|unique:users,mobileNumber', // Ensure mobile number is unique
        ]);

        // Create the admin user
        User::create([
            'firstName' => $request->first_name, // Ensure this matches your database field
            'lastName' => $request->last_name,   // Ensure this matches your database field
            'email' => $request->email,
            'password' => bcrypt($request->password), // Hash the password
            'mobileNumber' => $request->mobile_number, // Ensure this matches your database field
            'role' => 'admin', // Assign the 'admin' role
            'isVerified' => true, // Assuming new admins are verified by default
        ]);

        // Redirect back to the admin list page with a success message
        return redirect()->route('admin.adminUsers')->with('success', 'Admin created successfully!');
    }

    public function activate ($userID) {
        $user = User::find($userID);

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        // Activate the user
        $user->isActive = true;
        $user->save();

        // Optionally, you can send an activation email here
        Mail::to($user->email)->send(new AccountActivation($user->firstName, $user->reason));
        return redirect()->back()->with('success', 'User activated successfully.');
    }
    public function deactivate($userID) {
        $user = User::find($userID);
    
        if ($user) {
            // Set the user as inactive
            $user->isActive = false;
            $user->save();
    
            // Send an email notification to the user
            Mail::to($user->email)->send(new AccountDeactivation($user->firstName, $user->reason));

            return redirect()->back()->with('success', 'User deactivated successfully!');
        } else {
            return redirect()->back()->withErrors(['error' => 'User not found!']);
        }
    }
    public function getUsersByRole($role)
    {
        if (!in_array($role, ['business_owner', 'space_owner'])) {
            return response()->json([], 400); // Invalid role
        }

        $users = User::where('role', $role)->get(['userID', 'firstName', 'lastName']);
        return response()->json($users);
    }
    public function showReports() 
    {
        // Get the required data
        $approvedNegotiations = Negotiation::where('negoStatus', 'approved')->count();
        $pendingNegotiations = Negotiation::where('negoStatus', 'pending')->count();
        $totalNegotiations = Negotiation::count();
        $systemFeedbacks = SystemFeedback::count();
        $reviews = Reviews::count();
        $paymentHistory = Payment::count();
        
        // Calculate Total Earnings (sum of offerAmount - 10%)
        $totalEarnings = Negotiation::where('negoStatus', 'approved')
        ->sum(Negotiation::raw('offerAmount * 0.10'));

        return view('admin.reports', compact(
            'approvedNegotiations', 
            'pendingNegotiations', 
            'totalNegotiations', 
            'systemFeedbacks', 
            'reviews', 
            'paymentHistory', 
            'totalEarnings'
        ));
    }
    public function negotiationReport()
    {
        $negotiations = Negotiation::all(); // Retrieve all negotiations (replace with your data)
        return view('admin.negotiations', compact('negotiations'));
    }

    public function systemFeedbackReport()
    {
        $feedbacks = SystemFeedback::all(); // Retrieve all system feedbacks (replace with your data)
        return view('admin.system_feedback', compact('feedbacks'));
    }

    public function reviewsReport()
    {
        $reviews = Reviews::all(); // Retrieve all reviews (replace with your data)
        return view('admin.reviews', compact('reviews'));
    }

    public function paymentHistoryReport()
    {
        $payments = Payment::all(); // Retrieve all payment history (replace with your data)
        return view('admin.payment_report', compact('payments'));
    }
}
