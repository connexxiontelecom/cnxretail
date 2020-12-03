<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillDetail extends Model
{
    use HasFactory;

    public function getBillService(){
        return $this->belongsTo(Service::class, 'service_id');
    }
}
