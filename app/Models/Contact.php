<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'added_by',
        'company_name',
        'email',
        'company_phone',
        'website',
        'address',
        'contact_full_name',
        'contact_email',
        'contact_mobile',
        'whatsapp_contact',
        'slug',
        'contact_position',
        'communication_channel',
    ];
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
