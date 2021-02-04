<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    public function getConversations(){
        return $this->hasMany(Conversation::class, 'contact_id');
    }

    public function getContactInvoices(){
        return $this->hasMany(InvoiceMaster::class, 'contact_id');
    }
    public function getPaymentHistory(){
        return $this->hasMany(PaymentHistory::class, 'contact_id');
    }
    public function getContactReceipts(){
        return $this->hasMany(ReceiptMaster::class, 'contact_id');
    }
}
