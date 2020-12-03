<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetail extends Model
{
    use HasFactory;
    public function getInvoiceService(){
        return $this->belongsTo(Service::class, 'service_id');
    }
}
