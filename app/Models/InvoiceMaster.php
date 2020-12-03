<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceMaster extends Model
{
    use HasFactory;
     //Client-invoice relationship
     public function contact(){
        return $this->belongsTo(Contact::class, 'contact_id');
    }

    //invoiceItem-invoice relationship
    public function invoiceDetail(){
        return $this->hasMany(InvoiceDetail::class, 'invoice_id');
    }
    //invoiceItem-invoice relationship
    public function productDescription(){
        return $this->hasMany(Service::class, 'invoice_id');
    }

    //invoice-client
    public function contacts(){
        return $this->hasMany(Contact::class, 'contact_id');
    }

    //top-performers-invoice relationship
    public function converter(){
        return $this->belongsTo(User::class, 'issued_by');
    }
    //currency
    public function getCurrency(){
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
