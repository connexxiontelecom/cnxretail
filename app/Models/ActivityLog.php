<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;


    public function getTenant(){
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }

    public function setNewActivityLog($subject, $message){
        $log = new ActivityLog;
        $log->subject = $subject;
        $log->tenant_id = Auth::user()->tenant_id;
        $log->log = Auth::user()->full_name.": ".$message;
        $log->save();
    }
}
