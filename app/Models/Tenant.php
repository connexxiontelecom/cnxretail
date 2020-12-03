<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

         //general settings
         public function getGeneralSettings(){
            return $this->belongsTo(GeneralSetting::class, 'tenant_id', 'tenant_id');
        }

        //currency-tenant relationship
        public function currency(){
            return $this->belongsTo(Currency::class, 'currency_id');
        }

        public function getUsers(){
            return $this->hasMany(User::class, 'tenant_id');
        }
}
