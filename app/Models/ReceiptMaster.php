<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReceiptMaster extends Model
{
    use HasFactory;

    public function contact(){
        return $this->belongsTo(Contact::class, 'contact_id');
    }
     //top-performers-invoice relationship
     public function converter(){
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function getCurrency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function getBank(){
        return $this->belongsTo(Bank::class, 'bank_id');
    }

}
