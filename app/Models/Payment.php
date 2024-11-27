<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments'; 
    protected $primaryKey = 'paymentID';

    public $timestamps = false;
    protected $fillable = [
        'rentalAgreementID',
        'renterID',
        'amount',
        'date',
        'proof',
        'details',
        'status',
        'admin_proof',
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    public function renter()
    {
        return $this->belongsTo(User::class, 'renterID', 'userID');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'senderID', 'userID');
    }

    public function listing()
    {
        return $this->belongsTo(RentalAgreement::class, 'rentalAgreementID', 'rentalAgreementID')->with('listing');
    }

    public function rentalAgreement()
    {
        return $this->belongsTo(RentalAgreement::class, 'rentalAgreementID', 'rentalAgreementID');
    }

    public function spaceOwner()
    {
        return $this->hasOneThrough(
            User::class,  // Final model (User) that you want to retrieve
            RentalAgreement::class,  // Intermediary model (RentalAgreement) between Payment and User
            'rentalAgreementID',  // Foreign key on RentalAgreement
            'userID',  // Foreign key on User (this should be ownerID, not userID)
            'rentalAgreementID',  // Local key on Payment
            'ownerID'  // Local key on RentalAgreement (this should be ownerID)
        );
    }

    public function negotiation()
    {
        return $this->belongsTo(Negotiation::class, 'rentalAgreementID', 'listingID');
    }

    public function billing()
    {
        return $this->hasOne(BillingDetail::class, 'rental_agreement_id', 'rentalAgreementID');
    }

    public function meetupProof()
    {
        return $this->hasOne(MeetupProof::class, 'rental_agreement_id', 'rentalAgreementID');
    }
}
