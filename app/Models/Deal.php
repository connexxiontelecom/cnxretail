<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;
    public function getContact(){
        return $this->belongsTo(Contact::class, 'client_id');
    }
}
