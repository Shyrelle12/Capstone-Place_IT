<?php

namespace App\Http\Controllers;

use App\Models\RentalAgreement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Listing;
use App\Models\ListingImages;
use App\Models\Reviews;
use App\Models\User;
use App\Models\Notification;
use App\Models\MeetUpProof;


class SpaceOwnerController extends Controller
{
    /**
     * Display the dashboard for space owners.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $listings = Listing::with('images')->where('ownerID', Auth::id())->orderBy('dateCreated','desc')->get();
        return view('dashboard.space', compact('listings'));
    }

    public function newspaces()
    {
        $listings = Listing::where('ownerID', Auth::id())->get();
        return view('space_owner.newspaces', compact('listings'));
    }
    public function negotiations()
    {
        $listings = Listing::where('ownerID', Auth::id())->get();
        return view('space_owner.negotiations', compact('listings'));
    }

    public function edit($listingID)
    {
        $listing = Listing::findOrFail($listingID);
        return view('space_owner.edit', compact('listing'));
    }

    public function destroy($listingID)
    {
        // Find the listing by its ID
        $listing = Listing::findOrFail($listingID); // Throws a 404 if not found

        // Update status to 'Deactivated' before soft delete
        $listing->status = 'Deactivated';
        $listing->save();

        // Redirect back with a success message or JSON response
        return redirect()->route('space.dashboard')->with('success', 'Listing deactivated successfully.');
    }

    public function restore($listingID) 
    {
        $listing = Listing::findOrFail($listingID); // Throws a 404 if not found

            // Update status to 'Vacant' before soft delete
            $listing->status = 'Vacant';
            $listing->save();
            return redirect()->route('space.dashboard')->with('success', 'Listing Restored successfully.');
    }

    public function update(Request $request, $listingID)
    {
        $listing = Listing::findOrFail($listingID); // Get the listing by ID

        $request->validate([
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Update the listing with the new data
        $listing->update([
            'title' => $request->title,
            'location' => $request->location,
            'description' => $request->description,
        ]);

        return redirect()->route('space.dashboard')->with('success', 'Listing updated successfully.');
    }
    public function deleteImage($listingImageID)
    {
        // Find the image record by ID
        $image = ListingImages::findOrFail($listingImageID);

        // Construct the full file path
        $filePath = public_path('storage/images/' . $image->image_path);

        // Check if the file exists and delete it
        if (file_exists($filePath)) {
            unlink($filePath);  // Delete the file from the file system
        }

        // Delete the image record from the database
        $image->delete();

        return redirect()->back()->with('success', 'Image removed successfully.');
    }

    public function addImage(Request $request, $listingID)
    {
        $request->validate([
            'new_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        $file = $request->file('new_image');
        $imagePath = time() . '_' . $file->getClientOriginalName();  // Unique filename

        // Move the file to the public directory (public/storage/images/)
        $file->move(public_path('storage/images'), $imagePath);

        // Save the image record in the database
        ListingImages::create([
            'imageID' => $listingID, // Assuming you have a listing_id in your table
            'image_path' => $imagePath,  // Store the filename in the database
        ]);

        return redirect()->back()->with('success', 'Image added successfully.');
    }
    public function submit(Request $request)
    {   
        // Validate the incoming request
        $validated = $request->validate([
            'rate' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
            'rentalAgreementID' => 'nullable|exists:rental_agreements,rentalAgreementID',
        ]);

        $validated['renterID'] = auth()->user()->userID;
        $validated['rentalAgreementID'] = 3; // Automatically set renterID from logged-in user
        

        // Create a new feedback entry
        Reviews::create($validated);

        // Redirect with a success message
        return redirect()->route('space.dashboard')->with('success', 'Feedback submitted successfully.');
    }
    public function storeProofOfMeetup(Request $request, $negotiationID)
    {
        $request->validate([
            'proofFile' => 'required|image|mimes:jpg,jpeg,png|max:2048', // Validate image
        ]);

        // Store the image in storage
        $path = $request->file('proofFile')->store('meetup_proofs', 'public');

        // Create a new MeetupProof entry
        $meetup = MeetupProof::create([
            'rental_agreement_id' => $negotiationID,
            'proof_image' => $path,
            'status' => 'pending',
        ]);

        $this->notifyAdminMeetup($meetup);

        return redirect()->back()->with('success', 'Proof of meetup sent successfully.');
    }
    protected function notifyAdminMeetup($meetup)
    {
    // Find the space owner based on the ownerID in the Listing model
        $adminUsers = User::where('role', 'admin')->get(); // Adjust based on your role management

        foreach ($adminUsers as $admin) {
            Notification::create([
                'n_userID' => $admin->userID,  // The space owner's user ID
                'data' => $meetup->rentalAgreement->spaceOwner->firstName . ' ' . $meetup->rentalAgreement->spaceOwner->lastName,  // Store the title in the notification's data field as JSON
                'type' => 'meetup_submitted',  // Notification type
                ]);
        }   
    }

    public function reports(Request $request)
    {
        $ownerID = Auth::id();
        $search = $request->input('search'); 

        $agreements = RentalAgreement::where('ownerID', $ownerID)
            ->with(['renter', 'listing']) 
            ->when($search, function ($query, $search) {
                $query->whereHas('listing', function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%'); 
                });
            })
            ->get();

        foreach ($agreements as $agreement) {
            $agreement->amountReceivable = $agreement->offerAmount * 0.9;
        }

        $totalRevenue = $agreements->where('isPaid', true)->sum('amountReceivable');

        return view('space_owner.reports', [
            'agreements' => $agreements,
            'totalRevenue' => $totalRevenue,
            'search' => $search, 
        ]);
    }
}
