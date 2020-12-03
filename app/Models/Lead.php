<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    public function getContact(){
        return $this->belongsTo(Contact::class, 'contact_id');
    }
    public function getConvertedBy(){
        return $this->belongsTo(User::class, 'converted_by');
    }

    public function getProspect(){
        return $this->belongsTo(Prospecting::class, 'contact_id');
    }
    public function getAssignedTo(){
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
