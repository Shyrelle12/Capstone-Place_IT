<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetupProof extends Model
{
    use HasFactory;

    protected $fillable = ['rental_agreement_id', 'proof_image', 'status'];

    protected $table = 'meetup_proofs';

    protected $primaryKey = 'id';

    public function rentalAgreement()
    {
        return $this->belongsTo(RentalAgreement::class,'rental_agreement_id', 'rentalAgreementID');
    }
}
