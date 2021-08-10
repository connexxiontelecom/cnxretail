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

        public function getContactPerson(){
            return $this->hasMany(Contact::class, 'tenant_id');
        }

        public function getTenantConversations(){
            return $this->hasMany(AdminTenantConversationLog::class, 'tenant_id', 'tenant_id');
        }

        public function getSender(){
            return $this->belongsTo(AdminUser::class, 'admin_user');
        }


        public function getBanks(){
             return $this->hasMany(Bank::class, 'tenant_id', 'tenant_id');
        }


        /*  public function getSubscriptions(){
            return $this->hasMany(Membership::class, 'tenant_id', 'tenant_id');
        } */
    
    
    public function setNewOnlineTenant(){
        /*$tenant = new Tenant;
        $tenant->tenant_id = $tenant_id;
        $tenant->company_name = $metadata['company_name'];
        $tenant->email = $metadata['email'];
        $tenant->phone = $metadata['phone_no'];
        $tenant->address = $metadata['address'];
        $tenant->nature_of_business = $metadata['nature_of_business'];
        $tenant->plan_id = $metadata['plan'];
        $tenant->start = now();*/
    }
}
