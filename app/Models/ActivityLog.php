<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;


    public function getTenant(){
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }
}
