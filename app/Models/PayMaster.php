<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayMaster extends Model
{
    use HasFactory;


    public function vendor(){
        return $this->belongsTo(Contact::class, 'vendor_id');
    }

     public function paidBy(){
        return $this->belongsTo(User::class, 'issued_by');
    }

     public function getBank(){
        return $this->belongsTo(Bank::class, 'bank_id');
    }
}
