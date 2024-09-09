<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalAgreement extends Model
{
    use HasFactory;
    

    protected $table = 'rental_agreements';
    protected $primaryKey = 'rentalAgreementID';

    protected $fillable = [
    'ownerID',
    'renterID',
    'listingID',
    'rentalTerm',
    'dateCreated',
    'offerAmount',
    'dateStart',
    'dateEnd',
    'status',
    ];

    public function listing() {
        return $this->belongsTo(Listing::class, 'listingID');
    }

    public function owner() {
        return $this->belongsTo(User::class, 'ownerID');
    }

    public function renter() {
        return $this->belongsTo(User::class, 'renterID');
    }
    public function space()
    {
        return $this->belongsTo(Listing::class, 'listingID');
    }
}