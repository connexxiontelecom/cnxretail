<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuotationDetail extends Model
{
    use HasFactory;

    public function getQuotationService(){
        return $this->belongsTo(Service::class, 'service_id');
    }
}
