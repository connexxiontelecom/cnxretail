<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillMaster extends Model
{
    use HasFactory;

    public function getVendor(){
        return $this->belongsTo(Contact::class, 'vendor_id');
    }

    public function issuedBy(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function billedTo(){
        return $this->belongsTo(Contact::class, 'billed_to');
    }

    public function billItems(){
        return $this->hasMany(BillDetail::class, 'bill_id');
		}
		//currency
		public function getCurrency(){
			return $this->belongsTo(Currency::class, 'currency_id');
	}
}
