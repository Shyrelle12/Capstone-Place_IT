<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Negotiation;
use App\Models\Reply;
use App\Models\RentalAgreement;
use App\Models\BillingDetail;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;
use App\Mail\RentalAgreementMailable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;

class NegotiationController extends Controller
{
    /**
     * Show the list of negotiations for the authenticated user (either space owner or business owner).
     */

    public function index()
    {
        // Calculate the date 30 days ago from today
        $dateLimit = now()->subDays(60);

        // Fetch negotiations where the current user is either the sender (business owner) or receiver (space owner),
        // and where the created_at date is within the last 30 days
        $negotiations = Negotiation::where(function ($query) use ($dateLimit) {
            $query->where('senderID', Auth::id())
                ->where('created_at', '>=', $dateLimit);
        })
        ->orWhere(function ($query) use ($dateLimit) {
            $query->where('receiverID', Auth::id())
                ->where('created_at', '>=', $dateLimit);
        })
        ->with('listing', 'sender', 'receiver')
        ->orderBy('created_at', 'desc')
        ->get();

        // Check role and return appropriate view
        if (Auth::user()->role === 'business_owner') {
            return view('business_owner.negotiations', compact('negotiations'));
        } else {
            return view('space_owner.negotiations', compact('negotiations'));
        }
    }

    /**
     * Show the negotiation details with messages.
     */
    public function show($negotiationID)
    {
        $negotiation = Negotiation::with('listing', 'sender', 'receiver', 'replies', 'meetupProof')->findOrFail($negotiationID);

        $rentalAgreement = $negotiation->rentalAgreement;

        $billing = BillingDetail::where('rental_agreement_id', $negotiation->negotiationID)->first();

        // Conditionally return the correct view based on the user's role
        if (Auth::user()->role === 'business_owner') {
            return view('business_owner.messagedetail', compact('negotiation', 'rentalAgreement','billing'));
        } elseif (Auth::user()->role === 'space_owner') {
            return view('space_owner.messagedetail', compact('negotiation', 'rentalAgreement', 'billing'));
        }

        abort(403, 'Unauthorized access');
    }

    public function edit($rentalAgreementID)
    {
        // Fetch the rental agreement by ID
        $rentalAgreement = RentalAgreement::findOrFail($rentalAgreementID);

        // Check if the user is authorized to edit this agreement
        if (Auth::id() !== $rentalAgreement->ownerID && Auth::id() !== $rentalAgreement->renterID) {
            abort(403, 'Unauthorized access');
        }

        // Return the edit view with the rental agreement data
        return view('rental_agreements.edit', compact('rentalAgreement'));
    }

    public function update(Request $request, $negotiationID, $rentalAgreementID)
    {
        $validated = $request->validate([
            'rentalTerm' => 'required|in:weekly,monthly,yearly',
            'dateStart' => 'required|date',
            'dateEnd' => 'required|date|after_or_equal:dateStart',
        ]);

        $rentalAgreement = RentalAgreement::findOrFail($rentalAgreementID);

        $rentalAgreement->update([
            'rentalTerm' => $validated['rentalTerm'],
            'dateStart' => $validated['dateStart'],
            'dateEnd' => $validated['dateEnd'],
        ]);

        return redirect()->back()->with('success', 'Rental Agreement updated successfully.');
    }

    protected function showForBusinessOwner($negotiation)
    {
        return view('business_owner.messagedetail', compact('negotiation'));
    }

    protected function showForSpaceOwner($negotiation)
    {
        return view('space_owner.messagedetail', compact('negotiation'));
    }
    public function updateOfferAmount(Request $request, $negotiationID)
    {
        $request->validate([
            'offerAmount' => 'required|numeric|min:0',
        ]);

        $negotiation = Negotiation::findOrFail($negotiationID);
        $rentalAgreement = RentalAgreement::where('rentalAgreementID', $negotiationID)->firstOrFail();
        $negotiation->offerAmount = $request->input('offerAmount');
        $rentalAgreement->offerAmount = $request->input('offerAmount');

        // Save both updates
        $negotiation->save();
        $rentalAgreement->save();

        return redirect()->back()->with('success', 'Offer amount updated successfully.');
    }

    public function reply(Request $request, $negotiationID)
    {
        $request->validate([
            'message' => 'nullable|string|max:1000',
            'aImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $negotiation = Negotiation::findOrFail($negotiationID);

        if (Auth::id() !== $negotiation->senderID && Auth::id() !== $negotiation->receiverID) {
            abort(403, 'Unauthorized');
        }

        // Handle the image upload
        $imageName = null;
        if ($request->hasFile('aImage')) {
            $image = $request->file('aImage');
            $imageName = $image->getClientOriginalName();
            $image->storeAs('negotiation_images', $imageName, 'public');
        }

        // Save the reply
        $replyData = [
            'negotiationID' => $negotiationID,
            'senderID' => Auth::id(),
            'message' => $imageName ?? $request->input('message'),
        ];

        Reply::create($replyData);

        // Determine the recipient
        $receiverID = (Auth::id() === $negotiation->senderID)
            ? $negotiation->receiverID
            : $negotiation->senderID;

        // Check for an existing unread notification
        $existingNotification = Notification::where('n_userID', $receiverID)
            ->where('type', 'message')
            ->whereJsonContains('data->negotiationID', $negotiationID)
            ->first();

            if ($existingNotification) {
                $existingNotification->update([
                    'data' => json_encode([
                        'message' => 'You have new messages in your negotiation.',
                        'negotiationID' => $negotiationID,
                        'senderName' => Auth::user()->firstName,
                        'updated_at' => now(),
                    ]),
                    'read_at' => null,  // Reset read status, mark as unread
                ]);
            } else {
                // If no existing notification, create a new one
                Notification::create([
                    'n_userID' => $receiverID,
                    'read_at' => null,  // Set as unread initially
                    'data' => json_encode([
                        'message' => 'You have new messages in your negotiation.',
                        'negotiationID' => $negotiationID,
                        'senderName' => Auth::user()->firstName,
                    ]),
                    'type' => 'message',
                ]);
            }
        
            // Redirect based on user role
            if (Auth::user()->role === 'business_owner') {
                return redirect()->route('business.negotiation.show', ['negotiationID' => $negotiationID]);
            } elseif (Auth::user()->role === 'space_owner') {
                return redirect()->route('space.negotiation.show', ['negotiationID' => $negotiationID]);
            } else {
                abort(403, 'Unauthorized');
            }
        }


    /**
     * Get all messages for a negotiation (for API or dynamic loading purposes).
     */
    public function getMessages($negotiationID)
    {
        $replies = Reply::where('negotiationID', $negotiationID)->get();
        return response()->json($replies);
    }
    public function store(Request $request)
    {
        $request->validate([
            'listingID' => 'required|exists:listing,listingID',
            'receiverID' => 'required|exists:users,userID',
            'offerAmount' => 'required|numeric|min:1',
            'rentalTerm' => 'required|string|in:1 Week,2 Weeks,3 Weeks,monthly',
            'startDate' => 'required|date|after_or_equal:today',
            'endDate' => 'required|date|after:startDate',
            'visit_date' => 'required|date|after_or_equal:today',
        ]);

        try {
            // Create a negotiation entry
            $negotiation = Negotiation::create([
                'listingID' => $request->listingID,
                'senderID' => Auth::id(),
                'receiverID' => $request->receiverID,
                'negoStatus' => 'Pending',
                'offerAmount' => $request->offerAmount,
                'visit_date' => $request->visit_date,
                'visitStatus' => 'Pending'
            ]);

            // Create a rental agreement tied to this negotiation
            RentalAgreement::create([
                'listingID' => $request->listingID,
                'ownerID' => $request->receiverID,
                'renterID' => Auth::id(),
                'rentalTerm' => $request->rentalTerm,
                'offerAmount' => $request->offerAmount,
                'dateStart' => $request->startDate,
                'dateEnd' => $request->endDate,
                'status' => 'Pending',
                'isPaid' => false,
            ]);

            Notification::create([
                'n_userID' => $request->receiverID,  // Notify the space owner (receiver)
                'type' => 'negotiation',  // You can define this type for negotiation
                'data' => $negotiation->listing->title, // Custom message
                'created_at' => now(),
            ]);

            return redirect()->route('business.negotiations')->with('success', 'Negotiation request sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while processing your request.');
        }
    }
    public function storeDB(Request $request, $negotiationID)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'gcashNumber' => 'required|max:255',
            'myCheckbox' => 'required'
        ]);

        // Create a new billing detail
        BillingDetail::create([
            'user_id' => Auth::id(),
            'rental_agreement_id' => $negotiationID,  
            'gcash_number' => $validatedData['gcashNumber'],
        ]);

        // Redirect or send back a response after successful creation
        return redirect()->route('space.business_details')->with('success', 'Billing details have been saved successfully.');
    }

    public function updateStatus(Request $request, $negotiationID)
    {
        // Validate the status field
        $request->validate([
            'status' => 'required|in:Pending,Approved,Declined',
        ]);

        // Find the negotiation by ID
        $negotiation = Negotiation::findOrFail($negotiationID);

        // Ensure the current user is either the receiver (space owner) or the sender (business owner)
        if (Auth::id() != $negotiation->receiverID && !Auth::user()->hasRole('business_owner')) {
            abort(403, 'Unauthorized');
        }

        // Update the status
        $negotiation->negoStatus = $request->input('status');
        $negotiation->save();

        if ($negotiation->negoStatus === 'Approved') {
            $this->updateSpaceStatus($negotiation->listingID);
        }

        // Notify the Business Owner
        $this->notifyBusinessOwner($negotiation, $request->input('status'));

        // Redirect back with a success message
        if (Auth::user()->role === 'business_owner') {
            return redirect()->route('business.negotiation.show', ['negotiationID' => $negotiationID]);
        } elseif (Auth::user()->role === 'space_owner') {
            return redirect()->route('space.negotiation.show', ['negotiationID' => $negotiationID]);
        } else {
            abort(403, 'Unauthorized'); // If role is not authorized
        }
    }

    public function updateSpaceStatus($listingID)
    {
        // Find the listing (space) by its ID
        $listing = Listing::findOrFail($listingID);

        // Ensure the listing is currently Vacant before changing the status
        if ($listing->status === 'Vacant') {
            // Update the space status to "Occupied"
            $listing->status = 'Occupied';
            $listing->save();
        }
    }

    public function rentAgree(Request $request, $negotiationID)
    {
        // Validate the input fields
        $request->validate([
            'ownerID' => 'required|exists:users,userID',
            'renterID' => 'required|exists:users,userID',
            'listingID' => 'required|exists:listing,listingID',
            'rentalTerm' => 'required|in:weekly,monthly,yearly',
            'offerAmount' => 'required|numeric',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate', // Ensure end date is after start date
        ]);

        // Create a new rental agreement and insert into the database
        RentalAgreement::create([
            'ownerID' => $request->input('ownerID'),
            'renterID' => $request->input('renterID'),
            'listingID' => $request->input('listingID'),
            'rentalTerm' => $request->input('rentalTerm'),
            'dateCreated' => now(),
            'offerAmount' => $request->input('offerAmount'),
            'dateStart' => $request->input('startDate'),
            'dateEnd' => $request->input('endDate'),
            'status' => 'pending', // Set status to 'Ongoing' by default
        ]);

        // Redirect to the business owner dashboard after successful insert
        return redirect()->back()->with('success', 'Rental Agreement created successfully.');
    }

    public function approveRentalAgreement($rentalAgreementID)
    {
        // Find the rental agreement by its ID
        $rentalAgreement = RentalAgreement::where('rentalAgreementID', $rentalAgreementID)
                                            ->firstOrFail();

        // Check if it's already approved
        if ($rentalAgreement->status === 'approved') {
            return redirect()->back()->with('info', 'Rental Agreement is already approved.');
        }

        // Update the rental agreement status to approved
        $rentalAgreement->status = 'approved';
        $this->notifyBusinessOwnerAgreement($rentalAgreement);
        $rentalAgreement->save();

        // Fetch negotiation details
        $negotiation = $rentalAgreement->negotiationMail; // Assuming RentalAgreement is related to Negotiation
        $listing = $negotiation->listing; // Assuming Negotiation is related to Listing
        $renter = $negotiation->sender; // Assuming Negotiation is related to Renter
        $spaceOwner = $listing->owner; // Assuming Listing is related to Space Owner

        // Prepare data for the email
        $agreementData = [
            'negotiationID' => $negotiation->negotiationID,
            'renter' => $renter->firstName . ' ' . $renter->lastName,
            'spaceOwner' => $spaceOwner->firstName . ' ' . $spaceOwner->lastName,
            'listingName' => $listing->title,
            'rentalTerm' => $rentalAgreement->rentalTerm,
            'startDate' => $rentalAgreement->dateStart->format('F j, Y'),
            'endDate' => $rentalAgreement->dateEnd->format('F j, Y'),
            'status' => $rentalAgreement->status,
            'offerAmount' => $rentalAgreement->offerAmount,
            'dateCreated' => $rentalAgreement->created_at->format('F j, Y'),
            'visit_date' => $negotiation->visit_date 
                ? $negotiation->visit_date->format('F j, Y') 
                : 'N/A',
        ];

        $pdf = Pdf::loadView('emails.rental_agreements', compact('agreementData'));
        $pdfPath = storage_path('app/public/rental_agreements/agreement_' . $rentalAgreementID . '.pdf');
        $pdf->save($pdfPath);

        // Send email to both users
        Mail::to($renter->email)->send(new RentalAgreementMailable($agreementData, $pdfPath));
        Mail::to($spaceOwner->email)->send(new RentalAgreementMailable($agreementData, $pdfPath));

        return redirect()->back()->with('success', 'Rental Agreement approved successfully, and emails sent.');
    }

    public function showPaymentDetails(Request $request)
    {
        // Fetch negotiations where the authenticated user is involved (either as sender or receiver)
        $negotiations = Negotiation::where('senderID', Auth::id())
            ->orWhere('receiverID', Auth::id())
            ->with(['listing', 'sender', 'receiver', 'payment']) 
            ->get();

        // Fetch all payments
        $payments = Payment::whereIn('rentalAgreementID', $negotiations->pluck('negotiationID'))
        ->with(['renter', 'listing', 'rentalAgreement', 'spaceOwner'])
        ->get();

        // Fetch all billing details for the authenticated user
        $billingDetails = BillingDetail::where('user_id', Auth::id())
            ->with('rentalAgreement.negotiation') // Eager load relationships
            ->get();

        // Combine negotiations by senderID (assuming the sender is the Business Owner)
        $negotiationsByOwner = $negotiations->groupBy('senderID');

        // Pass all necessary data to the view
        return view('space_owner.payment_details', compact('negotiations', 'billingDetails', 'negotiationsByOwner', 'payments'));
    }

    protected function notifyBusinessOwner($negotiation, $newStatus)
    {
        // Find the business owner (sender of the negotiation)
        $businessOwner = $negotiation->sender;

        // Check if the business owner exists
        if ($businessOwner) {
            // Create a notification for the business owner
            Notification::create([
                'n_userID' => $businessOwner->userID,  // The business owner's user ID
                'data' => $negotiation->listing->title,  // Custom message
                'type' => 'negotiation_status_update',  // Define the type of notification
            ]);
        }
    }
    protected function notifyBusinessOwnerAgreement($rentalAgreement)
    {
        // Find the business owner (sender of the negotiation)
        $negotiation = Negotiation::where('negotiationID', $rentalAgreement->rentalAgreementID)
                              ->with('sender', 'receiver') // Include sender and receiver for notification
                              ->firstOrFail();

        // Find the business owner (sender of the negotiation)
        $businessOwner = $negotiation->sender;

        // Check if the business owner exists
        if ($businessOwner) {

            $spaceOwner = $negotiation->receiver;
            // Create a notification for the business owner
            Notification::create([
                'n_userID' => $businessOwner->userID,  // The business owner's user ID
                'data' =>   $spaceOwner->firstName . ' ' . $spaceOwner->lastName,// Custom message
                'type' => 'agreement_approved',  // Define the type of notification
            ]);
        }
    }

    public function approve($paymentID)
    {
        $payment = Payment::findOrFail($paymentID);
        // Check if the payment status is 'Transferred' before approving
        if ($payment->status == 'transferred') {
            $payment->status = 'received';
            $payment->save();

            return back()->with('success', 'Payment approved successfully.');
        }

        return back()->with('error', 'Unable to approve payment.');
    }

    public function downloadReceipt($paymentID)
    {
        $payment = Payment::findOrFail($paymentID);
        $negotiation = Negotiation::findOrFail($payment->rentalAgreementID);

        // Prepare data for the receipt
        $data = [
            'payer' => $negotiation->sender->firstName . ' ' . $negotiation->sender->lastName,
            'payerAddress' => $negotiation->sender->email ?? 'N/A',
            'payee' => $negotiation->receiver->firstName . ' ' . $negotiation->receiver->lastName,
            'payeeAddress' => $negotiation->receiver->email ?? 'N/A',
            'amount' => $payment->amount,
            'purpose' => $negotiation->listing->title,
            'receiptNumber' => $payment->billing->gcash_number,
            'mobileNumber' => $payment->renter->mobileNumber,
            'status' => $payment->status,
            'negostatus' => $negotiation->negoStatus,
            'date' => $payment->date,
            'dategen' => now()->format('Y-m-d H:i:s'),
            'uniqueKey' => Str::uuid(),
        ];

        // Load view and generate PDF
        $pdf = PDF::loadView('emails.receipts', $data);

        // Return the PDF for download
        return $pdf->download('Payment_Receipt_' . $payment->paymentID . '.pdf');
    }
}