<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negotiation extends Model
{
    use HasFactory;

    protected $table = 'negotiations';
    
    protected $fillable = ['listingID', 'senderID', 'receiverID', 'offerAmount', 'negoStatus','visit_date','visitStatus'];
    protected $primaryKey = 'negotiationID';
    protected $casts = [
        'visit_date' => 'datetime',
    ];

    public function listing() {
        return $this->belongsTo(Listing::class, 'listingID');
    }

    public function sender() {
        return $this->belongsTo(User::class, 'senderID');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiverID');
    }

    public function replies() {
        return $this->hasMany(Reply::class, 'negotiationID', 'negotiationID');
    }
    public function rentalAgreement()
    {
        return $this->hasOne(RentalAgreement::class, 'rentalAgreementID', 'negotiationID');
    }
    public function bill() {
        return $this->hasOne(BillingDetail::class, 'rental_agreement_id', 'rentalAgreementID');
    }
    public function payment() {
        return $this->hasOne(Payment::class, 'rentalAgreementID', 'rentalAgreementID'); // Ensure this matches your foreign keys
    }
    public function meetupProof()
    {
        return $this->hasOne(MeetupProof::class, 'rental_agreement_id', 'negotiationID');
    }
}
