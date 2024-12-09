<?php

use App\Http\Controllers\NegotiationController;
use App\Http\Controllers\OtpVerifyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SpaceOwnerController;
use App\Http\Controllers\BusinessOwnerController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CreateListingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SystemFeedbackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('register/space', [RegisteredUserController::class, 'createSpaceOwner'])->name('space.register');
Route::post('register/space', [RegisteredUserController::class, 'storeSpaceOwner'])->name('space.register.post');

Route::get('register/business', [RegisteredUserController::class, 'createBusinessOwner'])->name('business.register');
Route::post('register/business', [RegisteredUserController::class, 'storeBusinessOwner'])->name('business.register.post');

// Redirect to the appropriate dashboard based on the user's role
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'space_owner') {
        return redirect()->route('space.dashboard');
    } elseif ($user->role === 'business_owner') {
        return redirect()->route('business.dashboard');
    } elseif ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    else {
        abort(403, 'Unauthorized');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Space Owner Dashboard Route
Route::get('/space/dashboard', [SpaceOwnerController::class, 'index'])
    ->name('space.dashboard')
    ->middleware(['auth', 'verified', 'role:space_owner']);

// Business Owner Dashboard Route
Route::get('/business/dashboard', [BusinessOwnerController::class, 'index'])
    ->name('business.dashboard')
    ->middleware(['auth', 'verified', 'role:business_owner']);

// Admin Dashboard Route
Route::get('/admin/dashboard', [AdminController::class, 'index'])
    ->name('admin.dashboard')
    ->middleware(['auth', 'verified', 'role:admin']);

// Admin Navbars 
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/usermanagement', [AdminController::class, 'users'])->name('admin.usermanagement');
    Route::get('/admin/usermanagement/space-owners', [AdminController::class, 'spaceOwners'])->name('admin.spaceOwners');
    Route::get('/admin/usermanagement/business-owners', [AdminController::class, 'businessOwners'])->name('admin.businessOwners');
    Route::get('/admin/usermanagement/admins', [AdminController::class, 'adminUsers'])->name('admin.adminUsers');
    Route::post('/admin/usermanagement/deactivate/{userID}', [AdminController::class, 'deactivate'])->name('admin.deactivate');
    Route::post('/admin/usermanagement/activate/{userID}', [AdminController::class, 'activate'])->name('admin.activate');

    // Add new admin
    Route::get('/admin/usermanagement/admins/add', [AdminController::class, 'create'])->name('admin.create');
    Route::post('/admin/usermanagement/admins', [AdminController::class, 'store'])->name('admin.store');

    Route::get('/admin/dashboard/{role}', [AdminController::class, 'getUsersByRole']);
});

// Admin Listings
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/listingmanagement', [AdminController::class, 'listing'])->name('admin.listingmanagement');
    Route::post('/admin/listingmanagement/approve-listing/{listingID}', [AdminController::class, 'approveListing'])->name('admin.approveListing');
    Route::post('/admin/listingmanagement/disapprove-listing/{listingID}', [AdminController::class, 'disapproveListing'])->name('admin.disapproveListing');
    Route::get('/admin/listingmanagement/view/{listingID}', [AdminController::class, 'viewListing'])->name('admin.viewListing');
    Route::get('/admin/reports', [AdminController::class, 'showReports'])->name('admin.reports.dashboard');
    Route::get('/admin/reports/transactions', [AdminController::class, 'transactionReport'])->name('admin.transaction');
    Route::get('/admin/reports/listings', [AdminController::class, 'listingReport'])->name('admin.listings');
    Route::get('/admin/reports/negotiations', [AdminController::class, 'negotiationReport'])->name('admin.negotiations');
    Route::get('/admin/reports/feedbacks', [AdminController::class, 'systemFeedbackReport'])->name('admin.feedbacks');
    Route::get('/admin/reports/reviews', [AdminController::class, 'reviewsReport'])->name('admin.reviews');
    Route::get('/admin/reports/payments', [AdminController::class, 'paymentHistoryReport'])->name('admin.payments');
});

// Admin Payments
Route::get('/admin/payment', [AdminController::class, 'payment'])
    ->name('admin.payment')
    ->middleware(['auth', 'verified', 'role:admin']);

Route::put('/admin/payments/{paymentID}', [AdminController::class, 'updatePaymentStatus'])->name('admin.payments.update');
Route::post('/admin/payments/transfer', [AdminController::class, 'transfer'])->name('admin.payments.transfer');
Route::post('/admin/payments/transfer-full', [AdminController::class, 'transferFull'])->name('admin.payments.full');



Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/admin/notifications/create', [NotificationController::class, 'create'])->name('admin.notifications.create');
    Route::post('/admin/notifications', [NotificationController::class, 'store'])->name('admin.notifications.store');
});

// Business Owner Routes
Route::middleware(['auth', 'verified', 'role:business_owner'])->group(function () {
    Route::get('/business/place/{location}', [BusinessOwnerController::class, 'showByLocation'])->name('place.showByLocation');
    Route::get('/business/place/detail/{listingID}', [BusinessOwnerController::class, 'detail'])->name('place.detail');
    Route::get('/business/negotiations', [NegotiationController::class, 'index'])->name('business.negotiations');
    Route::get('/business/negotiations/{negotiationID}', [NegotiationController::class, 'show'])->name('business.negotiation.show');
    Route::put('/business/negotiations/{negotiationID}/updateOfferAmount', [NegotiationController::class, 'updateOfferAmount'])->name('business.updateOfferAmount');
    Route::get('/business/negotiations/payment/{negotiationID}', [BusinessOwnerController::class, 'proceedToPayment'])->name('business.proceedToPayment');
    Route::post('/business/negotiations/payment/{negotiationID}', [BusinessOwnerController::class, 'storeProofOfPayment'])->name('businessOwner.storeProofOfPayment');
    Route::get('/business/bookinghistory', [BusinessOwnerController::class, 'bookinghistory'])->name('business.bookinghistory');
    Route::get('/business/feedback', [BusinessOwnerController::class, 'feedback'])->name('business.feedback');
    Route::get('/business/feedback/{rentalAgreementID}', [BusinessOwnerController::class, 'action'])->name('business.action');
    Route::post('/business/feedback/submit', [BusinessOwnerController::class, 'submit'])->name('business.submit');
    Route::post('/business/negotiations/{negotiationID}/reply', [App\Http\Controllers\NegotiationController::class, 'reply'])->name('bnegotiation.reply');
    Route::post('/business/negotiations/{negotiationID}/{rentalAgreementID}/edit', [App\Http\Controllers\NegotiationController::class, 'edit'])->name('rentalAgreement.edit');
    Route::put('/business/negotiations/{negotiationID}/{rentalAgreementID}/update', [App\Http\Controllers\NegotiationController::class, 'update'])->name('rentalagreement.update');
    Route::post('/business/negotiations/{negotiationID}/rent-agreement', [App\Http\Controllers\NegotiationController::class, 'rentAgree'])->name('negotiation.rentAgree');
    Route::post('/business/negotiations/store', [App\Http\Controllers\NegotiationController::class, 'store'])->name('negotiation.store');
    Route::get('/business/reports', [BusinessOwnerController::class, 'reports'])->name('business.reports');
});

// Space Owner Routes
Route::middleware(['auth', 'verified', 'role:space_owner'])->group(function () {
    Route::get('/space/newspaces', [SpaceOwnerController::class, 'newspaces'])->name('space.newspaces');
    Route::post('/space/dashboard', [CreateListingController::class, 'store'])->name('space.new.store');
    Route::get('/spaces/{listingID}/edit', [SpaceOwnerController::class, 'edit'])->name('space_owner.edit');
    Route::post('/spaces/{listingID}/edit', [SpaceOwnerController::class, 'update'])->name('space_owner.update');
    Route::post('/spaces/listings/{listingID}', [SpaceOwnerController::class, 'destroy'])->name('listings.destroy');
    Route::post('/spaces/listings/{listingID}/restore', [SpaceOwnerController::class, 'restore'])->name('listings.restore');
    Route::delete('/spaces/image/{listingImageID}', [SpaceOwnerController::class, 'deleteImage'])->name('space_owner.delete_image');
    Route::post('/spaces/{listingID}/add_image', [  SpaceOwnerController::class, 'addImage'])->name('space_owner.add_image');
    Route::get('/space/negotiations', [NegotiationController::class, 'index'])->name('space.negotiations');
    Route::get('/space/negotiations/{negotiationID}', [NegotiationController::class, 'show'])->name('space.negotiation.show');
    Route::get('/space/reviews', [SystemFeedbackController::class, 'index'])->name('space.reviews');
    Route::post('/space/reviews/submit', [SystemFeedbackController::class, 'store'])->name('space.submit');
    Route::get('/space/payment', [NegotiationController::class, 'showPaymentDetails'])->name('space.business_details');
    Route::get('/space/payment/download/{paymentID}', [NegotiationController::class, 'downloadReceipt'])->name('payment.download');
    Route::put('/space/payment/{payment}/approve', [NegotiationController::class, 'approve'])->name('payments.approve');
    Route::post('space/rentalagreement/{rentalAgreementID}/approve', [NegotiationController::class, 'approveRentalAgreement'])->name('rentalagreement.approve');
    Route::post('/space/negotiations/{negotiationID}/reply', [App\Http\Controllers\NegotiationController::class, 'reply'])->name(name: 'negotiation.reply');
    Route::post('/space/negotiations/{negotiationID}/status', [App\Http\Controllers\NegotiationController::class, 'updateStatus'])->name('negotiation.updateStatus');
    Route::post('/space/negotiations/{negotiationID}/billingStore', [NegotiationController::class, 'storeDB'])->name('billing.store');
    Route::post('/space/negotiations/{negotiationID}/meetup_store', [SpaceOwnerController::class, 'storeProofOfMeetup'])->name('meetup.store');
    Route::post('/space/negotiations/{negotiationID}/updateStatus', [App\Http\Controllers\NegotiationController::class, 'updateStatus'])->name('negotiation.updateStatus');
    Route::get('/space/reports', [SpaceOwnerController::class, 'reports'])->name('space.reports');
});

Route::post('/terms/accept', [UserController::class, 'acceptTerms'])->name('terms.accept');
Route::get('/negotiations/{negotiationID}/reply', [App\Http\Controllers\NegotiationController::class, 'getMessages'])->name('negotiation.getMessages');


// Notifications
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications.all');
    Route::get('/space/notifications/unread', [NotificationController::class, 'getUnreadNotifications'])->name('notifications.unread');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
});

// Send Email for Verification
Route::get('verify-otp', function () {
    return view('emails.verify_email_otp'); 
})->name('otp.verify');

Route::post('send-email-verification', [OtpVerifyController::class, 'store'])->name('email.send');
Route::post('otp-verify', [OtpVerifyController::class, 'verifyOtp'])->name('otp.verify.submit');

// Profile routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

