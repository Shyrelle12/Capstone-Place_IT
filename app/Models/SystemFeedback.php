<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemFeedback extends Model
{
    protected $table = 'system_feedback';
    
    protected $primaryKey = 'feedbackID';

    // Fields that are mass assignable
    protected $fillable = [
        'space_owner_id',
        'feedback_content',
        'rating',
    ];
    public function spaceOwner()
    {
        return $this->belongsTo(User::class, 'space_owner_id', 'userID');
    }
}
